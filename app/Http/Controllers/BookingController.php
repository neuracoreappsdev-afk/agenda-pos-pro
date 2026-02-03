<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Appointment;
use App\Models\Specialist;
use App\Models\Waitlist;
use DateTime;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Paso 1: Mostrar lista de paquetes
     */
    public function index()
    {
        // 1. Obtenemos todos los paquetes activos
        $packages = Package::where('active', 1)->get();
        // Categorías que tienen al menos un servicio activo
        $categories = $packages->pluck('category')->unique()->filter()->values();
        
        // 2. Obtener categorías "Próximamente" 
        // Son aquellas que tienen servicios pero ninguno está activo actualmente
        $allCategories = Package::distinct()->pluck('category')->filter()->values();
        $upcomingCategories = $allCategories->diff($categories)->values();
        
        // Usar la nueva vista moderna (Micrositio)
        return view('booking.index', compact('packages', 'categories', 'upcomingCategories'));
    }

    /**
     * Paso 1.5: Selección de Especialista
     */
    public function getSpecialist($packageId)
    {
        $package = Package::find($packageId);
        $specialists = Specialist::all();
        return view('selectSpecialist', compact('package', 'specialists'));
    }

    /**
     * Paso 2: Mostrar calendario y horarios para un paquete
     */
    public function getCalendar($package_id, $specialist_id = null)
    {
        $package = Package::find($package_id);
        $specialist = $specialist_id ? Specialist::find($specialist_id) : null;
        
        // Determinar fecha seleccionada o hoy
        $selectedDate = request('date');
        if (!$selectedDate) {
            $selectedDate = \Carbon\Carbon::now()->format('d/m/Y');
        }

        $dateObj = \Carbon\Carbon::createFromFormat('d/m/Y', $selectedDate);
        $dateStr = $dateObj->format('Y-m-d');
        $dayOfWeek = strtolower($dateObj->format('l')); // e.g. 'monday'

        // 1. Bloqueo de Domingos y Festivos
        $isHoliday = \DB::table('holidays')->where('date', $dateStr)->where('active', true)->exists();
        $isSunday = ($dayOfWeek == 'sunday');

        if ($isHoliday || $isSunday) {
            return view('getTimes', [
                'package' => $package,
                'availableTimes' => [],
                'selectedDateFormatted' => $selectedDate,
                'specialist' => $specialist,
                'isHoliday' => true,
                'holidayName' => $isSunday ? 'Domingo' : (\DB::table('holidays')->where('date', $dateStr)->value('name') ?? 'Festivo')
            ]);
        }

        // 2. Fetch business hours (Daily)
        $dailyHours = \App\Models\Setting::get('work_hours_daily', []);
        if (is_string($dailyHours)) $dailyHours = json_decode($dailyHours, true) ?? [];

        $workStart = $dailyHours[$dayOfWeek]['start'] ?? \App\Models\Setting::get('work_hours_start', '09:00');
        $workEnd = $dailyHours[$dayOfWeek]['end'] ?? \App\Models\Setting::get('work_hours_end', '18:00');

        // 3. Generate base times according to business hours
        $baseTimes = [];
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $workStart);
        $endTime = \Carbon\Carbon::createFromFormat('H:i', $workEnd);

        while ($startTime->lt($endTime)) {
            $baseTimes[] = $startTime->format('H:i');
            $startTime->addMinutes(5);
        }

        $availableTimes = $baseTimes;
        $serviceDuration = $package ? $package->package_time : 60;

        // 4. Filtrar horas ocupadas por citas
        try {
            $query = Appointment::where('appointment_datetime', 'LIKE', $dateStr . '%')->with('package');
            if ($specialist_id) {
                $query->where('specialist_id', $specialist_id);
            }
            $bookedAppointments = $query->get();

            // 5. Filtrar por bloqueos temporales (Booking Locks)
            $locks = [];
            try {
                $locks = \DB::table('booking_locks')
                    ->where('datetime', 'LIKE', $dateStr . '%')
                    ->where('expires_at', '>', \Carbon\Carbon::now())
                    ->get();
            } catch (\Exception $e) {
                // Si la tabla no existe aún, ignorar bloqueo temporal
            }

            $availableTimes = array_filter($baseTimes, function($timeSlot) use ($bookedAppointments, $dateStr, $serviceDuration, $locks, $specialist_id) {
                $slotStart = \Carbon\Carbon::parse($dateStr . ' ' . $timeSlot);
                $slotEnd = (clone $slotStart)->addMinutes($serviceDuration);

                // Check physical appointments
                foreach ($bookedAppointments as $appointment) {
                    $appStart = \Carbon\Carbon::parse($appointment->appointment_datetime);
                    $appDuration = $appointment->package ? $appointment->package->package_time : 60;
                    $appEnd = (clone $appStart)->addMinutes($appDuration);
                    if ($slotStart < $appEnd && $slotEnd > $appStart) return false;
                }

                // Check temporary locks
                foreach ($locks as $lock) {
                    if ($specialist_id && $lock->specialist_id != $specialist_id) continue;
                    $lockStart = \Carbon\Carbon::parse($lock->datetime);
                    $lockEnd = (clone $lockStart)->addMinutes(30); // Lock assumes 30min granularity for blocking others
                    if ($slotStart < $lockEnd && $slotEnd > $lockStart) return false;
                }

                return true;
            });

        } catch (\Exception $e) {
            // Error en filtrado, devolver todos los baseTimes
        }

        $selectedDateFormatted = $selectedDate;
        return view('getTimes', compact('package', 'availableTimes', 'selectedDateFormatted', 'specialist'));
    }

    /**
     * Paso 3: Mostrar formulario de datos del cliente
     */
    /**
     * Paso 3: Mostrar formulario de datos del cliente
     */
    public function customerInfo(Request $request)
    {
        $package = Package::find($request->input('package_id'));
        
        if (!$package) {
            return redirect()->route('booking.index');
        }

        $date = $request->input('date'); // d/m/Y
        $time = $request->input('time'); // H:i
        $specialistId = $request->input('specialist_id');
        $specialist = $specialistId ? Specialist::find($specialistId) : null;

        // Formatear fecha para la vista de forma segura
        try {
            $dateObj = \Carbon\Carbon::createFromFormat('d/m/Y', $date);
            $dateFormatted = $dateObj->format('d M, Y'); // Ej: 12 Dec, 2025
            $dateYMD = $dateObj->format('Y-m-d'); // Para el input hidden
        } catch (\Exception $e) {
            $dateFormatted = $date;
            $dateYMD = date('Y-m-d');
        }

        return view('customerInfo', compact('package', 'date', 'time', 'specialist', 'dateFormatted', 'dateYMD'));
    }

    /**
     * Paso 4: Procesar y guardar la reserva
     */
    public function confirm(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'package_id' => 'required|exists:packages,id',
            'date' => 'required',
            'time' => 'required'
        ]);

        DB::beginTransaction();

        try {
            // 1. Crear o actualizar cliente
            $nameParts = explode(' ', $request->input('name'), 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            $customer = Customer::firstOrCreate(
                ['email' => $request->input('email')],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'contact_number' => $request->input('phone'),
                    'wants_updates' => true // Default
                ]
            );

            // 2. Crear Cita
            try {
                $dateObj = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date'));
                $dateStr = $dateObj->format('Y-m-d');
            } catch (\Exception $e) {
                $dateStr = date('Y-m-d'); // Fallback
            }

            $timeStr = $request->input('time');
            $dateTime = $dateStr . ' ' . $timeStr . ':00';
            $confirmToken = md5(uniqid(rand(), true));

            $appointment = Appointment::create([
                'customer_id' => $customer->id,
                'package_id' => $request->input('package_id'),
                'specialist_id' => $request->input('specialist_id'),
                'appointment_type' => $request->input('package_id'),
                'appointment_datetime' => $dateTime,
                'status' => 'pendiente',
                'confirm_token' => $confirmToken,
                'color' => '#2563eb'
            ]);

            DB::commit();

            // 3. Enviar Notificaciones
            $this->sendConfirmationEmails($appointment, $confirmToken);

            // Guardar ID en sesión para mostrar en success
            return redirect()->route('booking.success')->with('appointment_id', $appointment->id);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Ocurrió un error al procesar la reserva: ' . $e->getMessage()]);
        }
    }

    /**
     * Enviar correos de confirmación al cliente y al dueño
     */
    private function sendConfirmationEmails($appointment, $token)
    {
        $appointment->load(['customer', 'package', 'specialist']);
        $customer = $appointment->customer;
        
        $businessName = \App\Models\Setting::get('business_name', 'AgendaPOS');
        $businessEmail = \App\Models\Setting::get('business_email');
        
        $dateObj = \Carbon\Carbon::parse($appointment->appointment_datetime);
        
        $mailData = [
            'customerName' => $customer->first_name,
            'businessName' => $businessName,
            'serviceName' => $appointment->package ? $appointment->package->package_name : 'Servicio',
            'specialistName' => $appointment->specialist ? $appointment->specialist->name : 'Cualquier Especialista',
            'dateFormatted' => $dateObj->format('l, d \d\e F, Y'),
            'timeFormatted' => $dateObj->format('h:i A'),
            'confirmUrl' => url("cita/confirmar/{$token}"),
            'cancelUrl' => url("cita/cancelar/{$token}"),
            'modifyUrl' => url("cita/modificar/{$token}"),
            'businessPhone' => \App\Models\Setting::get('business_phone', '---'),
            'businessLocation' => 'Holguines Trade Center'
        ];

        try {
            $fromEmail = env('MAIL_USERNAME');
            
            // 1. Correo al Cliente
            \Mail::send('emails.booking_confirmation', $mailData, function($message) use ($customer, $businessName, $fromEmail) {
                $message->from($fromEmail, $businessName)
                        ->to($customer->email, $customer->first_name)
                        ->subject("Confirmación de Reserva en {$businessName}");
            });

            // 2. Correo al Dueño (Notificación de nueva cita)
            if ($businessEmail) {
                \Mail::send('emails.booking_confirmation', $mailData, function($message) use ($businessEmail, $businessName, $customer, $fromEmail) {
                    $message->from($fromEmail, $businessName)
                            ->to($businessEmail)
                            ->subject("NUEVA CITA: {$customer->first_name} - {$businessName}");
                });
            }
        } catch (\Exception $e) {
            \Log::error("Error sending booking confirmation emails: " . $e->getMessage());
        }
    }

    /**
     * Paso 5: Pantalla de éxito
     */
    public function success()
    {
        $appointmentId = session('appointment_id');
        if (!$appointmentId) {
            return redirect()->route('booking.index');
        }

        $appointment = Appointment::with(['customer', 'package', 'specialist'])->find($appointmentId);
        return view('success', compact('appointment'));
    }
    /**
     * Pantalla para unirse a la lista de espera
     */
    public function waitlistInfo(Request $request)
    {
        $package = Package::find($request->input('package_id'));
        $date = $request->input('date');
        $specialist_id = $request->input('specialist_id');
        $specialist = $specialist_id ? Specialist::find($specialist_id) : null;

        return view('waitlistPublic', compact('package', 'date', 'specialist'));
    }

    /**
     * Procesar registro en lista de espera
     */
    public function waitlistConfirm(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'package_id' => 'required|exists:packages,id'
        ]);

        DB::beginTransaction();
        try {
            // 1. Crear o actualizar cliente
            $nameParts = explode(' ', $request->input('name'), 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            $customer = Customer::firstOrCreate(
                ['contact_number' => $request->input('phone')],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $request->input('email') ?: $request->input('phone') . '@temp.com',
                    'wants_updates' => true
                ]
            );

            // 2. Agregar a la lista de espera
            $maxPriority = Waitlist::max('priority') ?? 0;
            
            // Formatear fechas
            $dateFrom = \Carbon\Carbon::now();
            $dateTo = \Carbon\Carbon::now()->addDays(7); // Por defecto una semana
            
            if ($request->input('date')) {
                try {
                    $dateFrom = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date'));
                    $dateTo = (clone $dateFrom)->addDays(1);
                } catch (\Exception $e) {}
            }

            Waitlist::create([
                'customer_id' => $customer->id,
                'package_id' => $request->input('package_id'),
                'specialist_id' => $request->input('specialist_id'),
                'date_from' => $dateFrom->format('Y-m-d'),
                'date_to' => $dateTo->format('Y-m-d'),
                'time_preference' => 'any',
                'priority' => $maxPriority + 1,
                'status' => 'waiting',
                'notes' => 'Registro vía Agendamiento Web'
            ]);

            DB::commit();

            return view('waitlistSuccess');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Tienda de Bonos de Regalo (Público)
     */
    public function bonosShop()
    {
        return view('bonos.shop');
    }

    /**
     * Procesar compra de bono
     */
    public function bonosPurchase(Request $request)
    {
        $this->validate($request, [
            'buyer_name' => 'required',
            'recipient_name' => 'required',
            'amount' => 'required|numeric|min:10000',
            'recipient_email' => 'required|email'
        ]);

        try {
            // 1. Generar código único (LINA-XXXX-YYYY)
            $code = 'LL-' . strtoupper(str_random(4)) . '-' . strtoupper(str_random(4));

            // 2. Simular pago exitoso y crear registro
            $bono = \App\Models\Bono::create([
                'code' => $code,
                'buyer_name' => $request->buyer_name,
                'recipient_name' => $request->recipient_name,
                'recipient_email' => $request->recipient_email,
                'recipient_phone' => $request->recipient_phone,
                'message' => $request->message,
                'amount' => $request->amount,
                'balance' => $request->amount,
                'status' => 'paid', // Simulado
                'expiry_date' => \Carbon\Carbon::now()->addMonths(6),
                'payment_id' => 'PAY-' . strtoupper(str_random(8))
            ]);

            // 3. Enviar Correo (Opcional por ahora)
            // $this->sendBonoEmail($bono);

            return redirect()->route('bonos.success', $bono->id);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error: ' . $e->getMessage()]);
        }
    }

    /**
     * Pantalla de éxito de compra de bono
     */
    public function bonosSuccess($id)
    {
        $bono = \App\Models\Bono::find($id);
        if (!$bono) return redirect('bonos-regalo');

        return view('bonos.success', compact('bono'));
    }
    /**
    * Bloquear un cupo temporalmente
    */
    public function lockSlot(Request $request)
    {
        $datetime = $request->datetime; // Y-m-d H:i
        $specialist_id = $request->specialist_id;
        $package_id = $request->package_id;
        $session_token = session()->getId();

        // Limpiar bloqueos expirados
        try {
            \DB::table('booking_locks')->where('expires_at', '<', \Carbon\Carbon::now())->delete();
        } catch (\Exception $e) {}

        // Verificar si ya está bloqueado por otro
        try {
            $exists = \DB::table('booking_locks')
                ->where('datetime', $datetime)
                ->where('specialist_id', $specialist_id)
                ->where('session_token', '!=', $session_token)
                ->where('expires_at', '>', \Carbon\Carbon::now())
                ->exists();

            if ($exists) {
                return response()->json(['success' => false, 'message' => 'Este cupo acaba de ser reservado por otra persona.']);
            }

            // Crear o actualizar bloqueo
            \DB::table('booking_locks')->updateOrInsert(
                ['datetime' => $datetime, 'specialist_id' => $specialist_id, 'session_token' => $session_token],
                [
                    'package_id' => $package_id,
                    'expires_at' => \Carbon\Carbon::now()->addMinutes(10),
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al bloquear cupo.']);
        }
    }
}
