<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialist;
use App\Models\Appointment;
use App\Models\Sale;
use App\Models\News;
use App\Models\Message;
use Carbon\Carbon;
use Session;

class SpecialistAuthController extends Controller
{
    public function showLogin()
    {
        if (Session::has('specialist_session')) {
            return redirect('colaborador/dashboard');
        }
        return view('specialist/login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'pin' => 'required'
        ]);

        $specialist = Specialist::where('email', $request->email)
            ->where('pin', $request->pin)
            ->where('active', 1)
            ->first();

        if ($specialist) {
            Session::put('specialist_session', true);
            Session::put('specialist_id', $specialist->id);
            Session::put('specialist_name', $specialist->name);
            
            if ($specialist->pin_reset_required) {
                return redirect('colaborador/cambiar-pin')->with('info', 'Por seguridad, debes cambiar tu clave temporal antes de continuar.');
            }
            
            return redirect('colaborador/dashboard');
        }

        return back()->with('error', 'Credenciales incorrectas o cuenta inactiva.');
    }

    public function logout()
    {
        Session::forget(['specialist_session', 'specialist_id', 'specialist_name']);
        return redirect('colaborador/login');
    }

    public function dashboard(Request $request)
    {
        if (!Session::has('specialist_session')) {
            return redirect('colaborador/login');
        }

        $specialistId = Session::get('specialist_id');
        $specialist = Specialist::find($specialistId);

        if ($specialist->pin_reset_required) {
            return redirect('colaborador/cambiar-pin');
        }

        // Soporte para filtrado por fechas
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));

        // Obtener Citas de la Agenda para el rango seleccionado (SOLO las propias por privacidad)
        $rawAppointments = Appointment::with(['customer', 'package'])
            ->where('specialist_id', $specialistId)
            ->whereBetween('appointment_datetime', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', '!=', 'cancelada')
            ->orderBy('appointment_datetime', 'asc')
            ->get();

        // Privacidad Estricta: Solo nombre y servicio
        $appointments = $rawAppointments->map(function($apt) {
            return (object)[
                'id' => $apt->id,
                'customer_name' => ($apt->customer ? $apt->customer->first_name . ' ' . $apt->customer->last_name : 'Cliente'),
                'service_name' => ($apt->package ? $apt->package->package_name : 'Servicio'),
                'time' => $apt->appointment_datetime ? $apt->appointment_datetime->format('H:i') : '--:--',
                'status' => $apt->status,
                'client_arrived_at' => $apt->client_arrived_at,
                'arrival_acknowledged' => $apt->arrival_acknowledged,
                'appointment_datetime' => $apt->appointment_datetime,
                'duration' => $apt->duration ?: ($apt->package ? $apt->package->package_time : 30),
                'specialist_id' => $apt->specialist_id,
                'additional_services' => $apt->additional_services
            ];
        });

        // Reporte de Comisiones / Pagos (Vouchers)
        $saleItems = \App\Models\SaleItem::with(['sale'])
            ->where('specialist_id', $specialistId)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalVentas = $saleItems->sum('total');
        $totalComisiones = $saleItems->sum('commission_value');

        // Novedades
        $news = News::where('active', true)->orderBy('created_at', 'desc')->limit(5)->get();

        // Mensajes de Chat
        $chatMessages = Message::where('specialist_id', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        // Lista de todos los especialistas para ver agendas ajenas (solo disponibilidad/nombres)
        $allSpecialists = Specialist::where('active', 1)->select('id', 'name', 'last_name')->get();

        // Servicios disponibles para carga rápida
        $allPackages = \App\Models\Package::where('active', 1)->get(['id', 'package_name', 'package_time', 'package_price']);

        return view('specialist/dashboard', compact(
            'specialist', 
            'appointments', 
            'totalVentas', 
            'totalComisiones', 
            'saleItems', 
            'news', 
            'chatMessages',
            'startDate',
            'endDate',
            'allSpecialists',
            'allPackages'
        ));
    }

    public function searchServices(Request $request)
    {
        $query = $request->get('q');
        $services = \App\Models\Package::where('package_name', 'like', "%$query%")
            ->where('active', 1)
            ->limit(10)
            ->get(['id', 'package_name', 'package_time', 'package_price']);
        return response()->json($services);
    }

    public function updateAppointmentServices(Request $request)
    {
        $appointment = Appointment::find($request->appointment_id);
        if (!$appointment) return response()->json(['success' => false, 'message' => 'Cita no encontrada']);

        $services = $request->services; // Expecting array of {id, name, duration, price}
        
        // Calcular nueva duración total
        $totalDuration = 0;
        if (is_array($services)) {
            foreach ($services as $s) {
                $totalDuration += (isset($s['duration']) ? (int)$s['duration'] : 0);
            }
        }

        $appointment->additional_services = json_encode($services);
        $appointment->duration = $totalDuration;
        $appointment->save();

        return response()->json(['success' => true]);
    }

    /**
     * Confirmar que el colaborador vio la notificación de llegada del cliente
     */
    public function acknowledgeArrival(Request $request)
    {
        $id = $request->input('appointment_id');
        $apt = Appointment::where('id', $id)
            ->where('specialist_id', Session::get('specialist_id'))
            ->first();

        if ($apt) {
            $apt->arrival_acknowledged = true;
            $apt->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Sistema de Polling para notificaciones premium (Llegada y Recordatorios)
     */
    public function checkNotifications()
    {
        if (!Session::has('specialist_id')) return response()->json(['type' => 'none']);
        
        $specialistId = Session::get('specialist_id');
        $now = Carbon::now();

        // 1. Notificación de Llegada (Un cliente se anunció en recepción)
        $arrival = Appointment::with('customer')
            ->where('specialist_id', $specialistId)
            ->whereNotNull('client_arrived_at')
            ->where('arrival_acknowledged', false)
            ->where('status', '!=', 'completada')
            ->orderBy('client_arrived_at', 'desc')
            ->first();

        if ($arrival) {
            return response()->json([
                'type' => 'arrival',
                'id' => $arrival->id,
                'title' => '¡Tu cliente ha llegado!',
                'message' => ($arrival->customer ? $arrival->customer->first_name . ' ' . $arrival->customer->last_name : 'Un cliente') . ' acaba de anunciarse en recepción para su turno de las ' . $arrival->appointment_datetime->format('H:i') . '.',
                'sound' => true
            ]);
        }

        // 2. Recordatorios de Turno (15 y 5 minutos antes)
        $reminder = Appointment::with(['customer', 'package'])
            ->where('specialist_id', $specialistId)
            ->where('status', 'confirmada')
            ->whereBetween('appointment_datetime', [$now->copy()->addMinutes(1), $now->copy()->addMinutes(16)])
            ->orderBy('appointment_datetime', 'asc')
            ->first();

        if ($reminder) {
            $diffInMinutes = $now->diffInMinutes($reminder->appointment_datetime);
            if ($diffInMinutes == 15 || $diffInMinutes == 5) {
                return response()->json([
                    'type' => 'reminder',
                    'id' => $reminder->id,
                    'title' => 'Turno Próximo',
                    'message' => 'Faltan ' . $diffInMinutes . ' minutos para tu turno con ' . ($reminder->customer ? $reminder->customer->first_name : 'cliente') . '.',
                    'sound' => true
                ]);
            }
        }

        return response()->json(['type' => 'none']);
    }

    public function startAppointment(Request $request)
    {
        $aptId = $request->input('appointment_id');
        $appointment = Appointment::with('package')->find($aptId);
        
        if (!$appointment) return response()->json(['error' => 'Cita no encontrada'], 404);
        
        $appointment->status = 'iniciado';
        
        // Inicializar servicios adicionales si está vacío con el servicio base
        if (!$appointment->additional_services && $appointment->package) {
            $appointment->additional_services = [
                [
                    'id' => $appointment->package->id,
                    'name' => $appointment->package->package_name,
                    'duration' => $appointment->package->package_time,
                    'price' => $appointment->package->package_price
                ]
            ];
            $appointment->duration = $appointment->package->package_time;
        }
        
        $appointment->save();
        
        return response()->json(['success' => true]);
    }

    public function getServices()
    {
        return response()->json(\App\Models\Package::where('active', 1)->orderBy('package_name')->get());
    }

    public function addServiceToAppointment(Request $request)
    {
        $aptId = $request->input('appointment_id');
        $packageId = $request->input('package_id');
        
        $appointment = Appointment::find($aptId);
        $package = \App\Models\Package::find($packageId);
        
        if (!$appointment || !$package) {
            return response()->json(['error' => 'Cita o Servicio no encontrado'], 404);
        }

        // 1. Calcular nueva hora de fin
        // Si ya tiene servicios adicionales, sumamos todos
        $baseDuration = $appointment->duration ?: ($appointment->package ? $appointment->package->duration : 30);
        $additionalDuration = 0;
        
        $additional = $appointment->additional_services ?: [];
        foreach($additional as $item) {
            $additionalDuration += (isset($item['duration']) ? $item['duration'] : 0);
        }
        
        $newServiceDuration = $package->duration ?: 30;
        $totalMinutes = $baseDuration + $additionalDuration + $newServiceDuration;
        
        $startTime = Carbon::parse($appointment->appointment_datetime);
        $estimatedEndTime = $startTime->copy()->addMinutes($totalMinutes);
        
        // 2. Verificar disponibilidad (Citas posteriores el mismo día)
        $nextAppointment = Appointment::where('specialist_id', $appointment->specialist_id)
            ->where('appointment_datetime', '>', $appointment->appointment_datetime)
            ->where('appointment_datetime', '<', $startTime->copy()->endOfDay())
            ->where('status', '!=', 'cancelada')
            ->where('id', '!=', $appointment->id)
            ->orderBy('appointment_datetime', 'asc')
            ->first();
            
        if ($nextAppointment) {
            $nextStartTime = Carbon::parse($nextAppointment->appointment_datetime);
            if ($estimatedEndTime->gt($nextStartTime)) {
                return response()->json([
                    'error' => 'No hay tiempo suficiente',
                    'message' => 'Este servicio adicional duraría hasta las ' . $estimatedEndTime->format('H:i') . ', cruzándose con tu siguiente cita con ' . ($nextAppointment->customer ? $nextAppointment->customer->first_name : 'otro cliente') . ' a las ' . $nextStartTime->format('H:i') . '.'
                ], 422);
            }
        }

        // 3. Guardar servicio adicional
        $additional[] = [
            'id' => $package->id,
            'name' => $package->package_name,
            'price' => $package->price,
            'duration' => $newServiceDuration,
            'added_at' => Carbon::now()->toDateTimeString()
        ];
        
        $appointment->additional_services = $additional;
        $appointment->save();
        
        return response()->json(['success' => true, 'services' => $additional]);
    }

    /**
     * Send message from specialist to admin
     */
    public function sendMessage(Request $request)
    {
        if (!Session::has('specialist_id')) {
            return response()->json(['error' => 'No session'], 401);
        }

        $specialistId = Session::get('specialist_id');
        $type = $request->input('type', 'text');
        $content = $request->input('message');
        $filePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/chat'), $filename);
            $filePath = 'uploads/chat/' . $filename;
        }

        $message = Message::create([
            'specialist_id' => 0, // 0 = Group Chat
            'sender_type' => 'specialist',
            'sender_name' => Session::get('specialist_name'),
            'message' => $content,
            'message_type' => $type,
            'file_path' => $filePath,
            'is_read' => false
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function getMessages()
    {
        $messages = Message::where('specialist_id', 0)
            ->orderBy('created_at', 'asc')
            ->get();
            
        return response()->json($messages);
    }

    public function showChangePin()
    {
        if (!Session::has('specialist_session')) {
            return redirect('colaborador/login');
        }
        return view('specialist/change_pin');
    }

    public function changePin(Request $request)
    {
        if (!Session::has('specialist_session')) {
            return redirect('colaborador/login');
        }

        $this->validate($request, [
            'pin' => 'required|digits:4|confirmed',
        ]);

        $specialist = Specialist::find(Session::get('specialist_id'));
        $specialist->pin = $request->pin;
        $specialist->pin_reset_required = 0;
        $specialist->save();

        return redirect('colaborador/dashboard')->with('success', 'Tu clave ha sido actualizada correctamente.');
    }
}
