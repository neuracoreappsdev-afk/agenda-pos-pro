<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Setting;

class AppointmentPublicController extends Controller
{
    /**
     * Mostrar página de confirmación de cita
     */
    public function show($token)
    {
        $appointments = Appointment::with(['customer', 'package', 'specialist'])
            ->where('confirm_token', $token)
            ->get();
        
        if ($appointments->isEmpty()) {
            return view('public.appointment.not_found');
        }
        
        $customer = $appointments->first()->customer;
        $businessName = Setting::get('business_name', 'Nuestro Salón');
        $businessLogo = Setting::get('business_logo', '');
        $businessPhone = Setting::get('business_phone', '');
        $businessWhatsapp = Setting::get('social_whatsapp', $businessPhone);
        $businessAddress = Setting::get('business_address', '');
        
        // Detectar conflictos de horario con otras citas del cliente
        $conflicts = $this->detectConflicts($appointments, $customer);
        
        return view('public.appointment.show', compact(
            'appointments', 'customer', 'token', 
            'businessName', 'businessLogo', 'businessPhone', 'businessAddress',
            'businessWhatsapp', 'conflicts'
        ));
    }


    /**
     * Confirmar cita
     */
    public function confirm($token)
    {
        $appointments = Appointment::where('confirm_token', $token)->get();
        
        if ($appointments->isEmpty()) {
            return view('public.appointment.not_found');
        }
        
        foreach ($appointments as $apt) {
            $apt->status = 'confirmada';
            $apt->save();
        }
        
        $businessName = Setting::get('business_name', 'Nuestro Salón');
        
        return view('public.appointment.confirmed', compact('appointments', 'businessName', 'token'));
    }

    /**
     * Cancelar cita
     */
    public function cancel($token)
    {
        $appointments = Appointment::where('confirm_token', $token)->get();
        
        if ($appointments->isEmpty()) {
            return view('public.appointment.not_found');
        }
        
        foreach ($appointments as $apt) {
            $apt->status = 'cancelada';
            $apt->save();
        }
        
        $businessName = Setting::get('business_name', 'Nuestro Salón');
        
        return view('public.appointment.cancelled', compact('appointments', 'businessName'));
    }

    /**
     * Mostrar formulario de modificación
     */
    public function modify($token)
    {
        $appointments = Appointment::with(['customer', 'package', 'specialist'])
            ->where('confirm_token', $token)
            ->get();
        
        if ($appointments->isEmpty()) {
            return view('public.appointment.not_found');
        }
        
        $customer = $appointments->first()->customer;
        $businessName = Setting::get('business_name', 'Nuestro Salón');
        
        // Get available time slots for next 7 days
        $availableSlots = $this->getAvailableSlots();
        
        return view('public.appointment.modify', compact(
            'appointments', 'customer', 'token', 'businessName', 'availableSlots'
        ));
    }

    /**
     * Guardar modificación de cita
     */
    public function saveModification($token, \Illuminate\Http\Request $request)
    {
        $appointments = Appointment::where('confirm_token', $token)->get();
        
        if ($appointments->isEmpty()) {
            return view('public.appointment.not_found');
        }
        
        $newDatetime = $request->date . ' ' . $request->time . ':00';
        
        // Update all appointments in the group
        $first = $appointments->first();
        $timeDiff = \Carbon\Carbon::parse($first->appointment_datetime)
            ->diffInMinutes(\Carbon\Carbon::parse($newDatetime), false);
        
        foreach ($appointments as $apt) {
            $currentTime = \Carbon\Carbon::parse($apt->appointment_datetime);
            $apt->appointment_datetime = $currentTime->addMinutes($timeDiff);
            $apt->status = 'pendiente'; // Reset to pending for new confirmation
            $apt->save();
        }
        
        $businessName = Setting::get('business_name', 'Nuestro Salón');
        
        return view('public.appointment.modified', compact('appointments', 'businessName', 'token'));
    }

    /**
     * Obtener horarios disponibles
     */
    private function getAvailableSlots()
    {
        $slots = [];
        $today = \Carbon\Carbon::today();
        
        for ($d = 0; $d < 7; $d++) {
            $date = $today->copy()->addDays($d);
            $dateStr = $date->format('Y-m-d');
            $slots[$dateStr] = [
                'label' => $date->format('l, d M'),
                'times' => []
            ];
            
            // Generate time slots from 8am to 8pm
            for ($h = 8; $h <= 20; $h++) {
                $slots[$dateStr]['times'][] = sprintf('%02d:00', $h);
                $slots[$dateStr]['times'][] = sprintf('%02d:30', $h);
            }
        }
        
        return $slots;
    }

    /**
     * Detectar conflictos de horario con otras citas del cliente
     */
    private function detectConflicts($currentAppointments, $customer)
    {
        if (!$customer) {
            return [];
        }

        $conflicts = [];
        $currentToken = $currentAppointments->first()->confirm_token;
        
        // Obtener todas las citas del cliente que NO sean las actuales y estén confirmadas/pendientes
        $otherAppointments = Appointment::with(['package', 'specialist'])
            ->where('customer_id', $customer->id)
            ->where('confirm_token', '!=', $currentToken)
            ->whereIn('status', ['confirmada', 'pendiente'])
            ->where('appointment_datetime', '>=', \Carbon\Carbon::now())
            ->get();
        
        if ($otherAppointments->isEmpty()) {
            return [];
        }

        // Verificar cada cita actual contra las otras
        foreach ($currentAppointments as $currentApt) {
            $currentStart = \Carbon\Carbon::parse($currentApt->appointment_datetime);
            $currentDuration = $currentApt->duration ?: ($currentApt->package ? $currentApt->package->package_time : 60);
            $currentEnd = $currentStart->copy()->addMinutes($currentDuration);
            
            foreach ($otherAppointments as $otherApt) {
                $otherStart = \Carbon\Carbon::parse($otherApt->appointment_datetime);
                $otherDuration = $otherApt->duration ?: ($otherApt->package ? $otherApt->package->package_time : 60);
                $otherEnd = $otherStart->copy()->addMinutes($otherDuration);
                
                // Verificar si hay solapamiento: (A.start < B.end) AND (A.end > B.start)
                if ($currentStart < $otherEnd && $currentEnd > $otherStart) {
                    $conflicts[] = [
                        'current_service' => $currentApt->package ? $currentApt->package->package_name : 'Servicio',
                        'current_time' => $currentStart->format('H:i'),
                        'other_service' => $otherApt->package ? $otherApt->package->package_name : 'Servicio',
                        'other_time' => $otherStart->format('H:i') . ' - ' . $otherEnd->format('H:i'),
                        'other_date' => $otherStart->format('d/m/Y'),
                        'specialist' => $otherApt->specialist ? $otherApt->specialist->name : 'Especialista'
                    ];
                }
            }
        }
        
        return $conflicts;
    }
}
