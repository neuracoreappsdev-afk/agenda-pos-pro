<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Appointment;
use Carbon\Carbon;

class CreatorController extends Controller
{
    public function dashboard()
    {
        // Seguridad: Solo el Creador (Admin Master) puede entrar
        if (!session('admin_session')) {
            return redirect('admin');
        }

        try {
            $businesses = \App\Models\Business::orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            $businesses = collect();
        }
        
        // Métricas Financieras (Arrendamientos SaaS)
        $currentMonth = date('Y-m');
        $prevMonth = date('Y-m', strtotime('-1 month'));

        $incomeCurrent = \DB::table('core_subscriptions')->where('period', $currentMonth)->sum('amount') ?: 0;
        $incomePrev = \DB::table('core_subscriptions')->where('period', $prevMonth)->sum('amount') ?: 0;
        $totalHistorical = \DB::table('core_subscriptions')->sum('amount') ?: 0;

        // Cálculo de balance (diferencia)
        $diff = 0;
        if ($incomePrev > 0) {
            $diff = (($incomeCurrent - $incomePrev) / $incomePrev) * 100;
        }

        $stats = [
            'total_businesses' => $businesses->count(),
            'global_sales' => '$' . number_format($totalHistorical / 1000000, 1) . 'M', // Histórico acumulado
            'monthly_income' => number_format($incomeCurrent, 0, ',', '.'),
            'balance_diff' => number_format($diff, 1, ',', '.'),
            'income_prev' => number_format($incomePrev, 0, ',', '.'),
            'support_tickets' => \App\Models\SupportTicket::count()
        ];

        $apps = \DB::table('core_apps')->where('is_active', 1)->get();
        
        // Cargar Admins Pendientes de Aprobación
        $pendingAdmins = \App\Models\Admin::where('is_approved', 0)->orderBy('created_at', 'desc')->get();
        
        // Cargar Planes SaaS disponibles
        $saasPlans = \DB::table('saas_plans')->get();

        return view('creator/dashboard', compact('stats', 'businesses', 'apps', 'pendingAdmins', 'saasPlans'));
    }

    public function businessDetail($id)
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        // En un SaaS real, aquí buscaríamos el nombre de la empresa en la BD por su ID
        $business_name = ($id == 1) ? "Lina Lucio - Imperial Salon" : "Barber Shop Elite";
        $business_id = $id;

        return view('creator/business_explorer', compact('business_name', 'business_id'));
    }

    public function store(Request $request)
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:businesses',
            'owner_name' => 'required'
        ]);

        try {
            $data = $request->all();
            $data['slug'] = str_slug($data['name']);
            $data['app_id'] = $request->app_id ?? 1; // Default to first app if not provided
            
            \App\Models\Business::create($data);

            return redirect('creator/dashboard')->with('success', 'Infraestructura SaaS desplegada correctamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear empresa: ' . $e->getMessage()]);
        }
    }

    public function support()
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        $tickets = \App\Models\SupportTicket::with('business')->orderBy('updated_at', 'desc')->get();
        
        return view('creator/support', compact('tickets'));
    }

    public function impersonate($id)
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        $business = \App\Models\Business::find($id);
        
        // Cargar branding de la App asociada
        $app_branding = [
            'primary_color' => '#000000',
            'secondary_color' => '#333333',
            'font_family' => 'inherit'
        ];

        if ($business && $business->app_id) {
            $app = \DB::table('core_apps')->where('id', $business->app_id)->first();
            if ($app) {
                $app_branding = [
                    'primary_color' => $app->primary_color,
                    'secondary_color' => $app->secondary_color,
                    'font_family' => $app->font_family
                ];
            }
        }

        session(['business_id' => $id]);
        session(['business_name' => $business ? $business->name : 'Empresa Seleccionada']);
        session(['app_branding' => $app_branding]);
        
        return redirect('admin/dashboard')->with('success', 'Sesión iniciada como: ' . session('business_name'));
    }

    public function ticketDetail($id)
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        $ticket = \App\Models\SupportTicket::with(['business', 'messages'])->findOrFail($id);
        
        return view('creator/ticket_detail', compact('ticket'));
    }

    public function apps()
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        $apps = \DB::table('core_apps')->get();
        
        return view('creator/apps', compact('apps'));
    }

    public function updateApp(Request $request, $id)
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        \DB::table('core_apps')->where('id', $id)->update([
            'name' => $request->name,
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'font_family' => $request->font_family,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return back()->with('success', 'Configuración de App actualizada');
    }

    /**
     * Aprobar un administrador registrado
     */
    public function approveAdmin($id)
    {
        if (!session('admin_session')) return redirect('admin');

        $admin = \App\Models\Admin::find($id);
        if ($admin) {
            $admin->is_approved = 1;
            $admin->save();
            return back()->with('success', "Acceso aprobado para: " . $admin->username);
        }
        return back()->with('error', "No se pudo encontrar el usuario");
    }

    /**
     * Reset de Fábrica (Limpiar Datos)
     * ¡Atención: Este método vacía las tablas operativas!
     */
    public function hardReset()
    {
        if (!session('admin_session')) return redirect('admin');

        try {
            // Tablas operativas a limpiar
            $tables = [
                'appointments', 
                'customers', 
                'sales', 
                'sale_items', 
                'cash_movements', 
                'cash_register_sessions',
                'packages',
                'specialists',
                'notifications',
                'waitlist'
            ];

            foreach ($tables as $table) {
                if (\Schema::hasTable($table)) {
                    \DB::table($table)->delete();
                }
            }

            return back()->with('success', '¡Software reseteado! Todas las tablas operativas están en blanco.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al resetear: ' . $e->getMessage());
        }
    }
    /**
     * Activar un plan manualmente (Premios / Pagos externos)
     */
    public function activatePlan(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        $business_id = $request->input('business_id');
        $plan_id = $request->input('plan_id');
        $months = (int)$request->input('months', 1);

        $plan = \DB::table('saas_plans')->where('id', $plan_id)->first();
        if (!$plan) return back()->with('error', 'Plan no encontrado');

        $endsAt = date('Y-m-d H:i:s', strtotime("+$months months"));

        // Actualizar o Crear Suscripción
        $subscription = \DB::table('saas_subscriptions')->where('business_id', $business_id)->first();

        if ($subscription) {
            \DB::table('saas_subscriptions')->where('id', $subscription->id)->update([
                'plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => date('Y-m-d H:i:s'),
                'ends_at' => $endsAt,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            \DB::table('saas_subscriptions')->insert([
                'business_id' => $business_id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => date('Y-m-d H:i:s'),
                'ends_at' => $endsAt,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        // También actualizar el plan_type en la tabla businesses para coherencia visual
        \DB::table('businesses')->where('id', $business_id)->update([
            'plan_type' => $plan->slug
        ]);

        return back()->with('success', "Plan '{$plan->name}' activado por $months meses para el negocio.");
    }
}
