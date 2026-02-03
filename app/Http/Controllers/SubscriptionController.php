<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function index()
    {
        // 1. Identificar el negocio actual
        $business_id = session('business_id', 1); 

        // --- AUTO-HEALING & MIGRATION LOGIC ---
        // Verificar si existen planes legacy (Precios > 500 o nombres viejos)
        $legacyPlans = \DB::table('saas_plans')
            ->where('price', '>', 500)
            ->orWhere('name', 'AgendaPOS Free')
            ->orWhere('name', 'Equipo Crecimiento IA')
            ->count();
            
        if ($legacyPlans > 0) {
            \DB::table('saas_plans')->delete(); // Borrar planes viejos/erróneos
        }

        // Si no existen planes, crearlos
        if (\DB::table('saas_plans')->count() == 0) {
            $this->seedDefaultPlans();
        }

        // 2. Buscar suscripción
        $subscription = \DB::table('saas_subscriptions')
                            ->join('saas_plans', 'saas_subscriptions.plan_id', '=', 'saas_plans.id')
                            ->where('saas_subscriptions.business_id', $business_id)
                            ->select('saas_subscriptions.*', 'saas_plans.name as plan_name', 'saas_plans.price', 'saas_plans.id as plan_id')
                            ->first();

        // 3. Si sigue sin tener suscripción, crearle la GRATIS por defecto
        if (!$subscription) {
            $freePlan = \DB::table('saas_plans')->where('price', 0)->first();
            if ($freePlan) {
                \DB::table('saas_subscriptions')->insert([
                    'business_id' => $business_id,
                    'plan_id' => $freePlan->id,
                    'status' => 'active',
                    'starts_at' => date('Y-m-d H:i:s'),
                    'ends_at' => date('Y-m-d H:i:s', strtotime('+10 years')), // Forever free
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                return redirect('admin/subscription'); // Recargar para mostrar dashboard
            }
             // Si falla todo, mostrar error (no debería pasar)
             return view('admin.subscription.empty'); 
        }

        $payments = \DB::table('saas_payments')
                        ->where('business_id', $business_id)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();

        $availablePlans = \DB::table('saas_plans')->where('is_active', 1)->orderBy('price', 'asc')->get();
        
        // Pre-procesar características para evitar lógica en la vista
        foreach ($availablePlans as $plan) {
            $features = json_decode($plan->features_json, true);
            if (!is_array($features)) $features = [];

            // Fallbacks
            if (empty($features)) {
                if ($plan->price == 0) $features = ['Agenda Ilimitada', 'POS Básico', '50 Clientes', 'Sin IA'];
                elseif ($plan->price < 15) $features = ['Todo lo de Gratis', 'Contador IA 🤖', 'Reportes Financieros', 'Control de Gastos'];
                else $features = ['Todo lo de Control', 'Call Center IA (WhatsApp) 📞', 'Marketing IA', 'Inventario Avanzado', 'Soporte Prioritario'];
            }
            $plan->features_parsed = $features;
        }

        return view('admin.subscription.index', compact('subscription', 'payments', 'availablePlans'));
    }

    /**
     * Crea los planes por defecto si la base de datos está vacía
     */
    private function seedDefaultPlans()
    {
        $now = date('Y-m-d H:i:s');
        
        // Plan 1: Freemium
        \DB::table('saas_plans')->insert([
            'name' => 'AgendaPOS Freemium',
            'slug' => 'freemium',
            'description' => 'Todo lo que necesitas para operar. Agenda, POS y Clientes ilimitados.',
            'price' => 0.00,
            'currency' => 'USD',
            'billing_cycle_days' => 30,
            'max_users' => 99, 
            'max_branches' => 1,
            'whatsapp_integration' => 0,
            'features_json' => json_encode(['Agenda Ilimitada', 'POS Ilimitado', 'Inventario Básico', 'Usuarios Ilimitados']),
            'has_ai' => 0,
            'is_active' => 1,
            'created_at' => $now, 'updated_at' => $now
        ]);

        // Plan 2: Control ($17)
        \DB::table('saas_plans')->insert([
            'name' => 'Control Financiero',
            'slug' => 'smart-financial',
            'description' => 'Entiende dónde ganas y pierdes dinero. Alertas y reportes.',
            'price' => 17.00,
            'currency' => 'USD',
            'billing_cycle_days' => 30,
            'max_users' => 99,
            'max_branches' => 1,
            'whatsapp_integration' => 0,
            'features_json' => json_encode(['Agenda & POS', 'Reportes Financieros 📊', 'Contador IA 🤖', 'Control de Fugas', 'Alertas de Gastos']),
            'has_ai' => 1, 
            'is_active' => 1,
            'created_at' => $now, 'updated_at' => $now
        ]);

        // Plan 3: Pro ($30)
        \DB::table('saas_plans')->insert([
            'name' => 'Control Absoluto de mi Negocio',
            'slug' => 'pro-ai-team',
            'description' => 'Tu equipo completo de expertos: Contador, Nómina, Call Center y Marketing.',
            'price' => 30.00, 
            'currency' => 'USD',
            'billing_cycle_days' => 30,
            'max_users' => 99,
            'max_branches' => 1,
            'whatsapp_integration' => 1, // Includes Bot
            'features_json' => json_encode(['Todo lo de Control Interno', 'Call Center IA 📞', 'WhatsApp Bot', 'Marketing IA 🚀', 'Nómina Inteligente']),
            'has_ai' => 1,
            'is_active' => 1,
            'created_at' => $now, 'updated_at' => $now
        ]);
    }

    public function pay($plan_id)
    {
        // 1. Obtener los detalles del plan seleccionado
        $plan = \DB::table('saas_plans')->where('id', $plan_id)->first();
        
        if (!$plan) {
            // Si el ID no se encuentra en saas_plans, intentamos buscarlo en suscripciones (retrocompatibilidad)
            $subData = \DB::table('saas_subscriptions')
                ->join('saas_plans', 'saas_subscriptions.plan_id', '=', 'saas_plans.id')
                ->where('saas_subscriptions.id', $plan_id)
                ->select('saas_plans.*', 'saas_subscriptions.id as sub_id')
                ->first();
            
            if (!$subData) abort(404, 'Plan o Suscripción no encontrada');
            $plan = $subData;
        }

        // Crear un objeto shim para la vista que espera un objeto $sub
        $sub = (object)[
            'id' => isset($plan->sub_id) ? $plan->sub_id : 0,
            'plan_name' => $plan->name,
            'price' => $plan->price
        ];

        // 2. Datos del negocio para el pago
        $business_id = session('business_id', 1);
        $business_email = \App\Models\Setting::get('business_email', 'admin@agendapos.com');

        // 3. Referencia única de pago
        $reference = 'BILL-' . $business_id . '-' . $plan->id . '-' . time();
        $amount_in_cents = (int)($plan->price * 4000 * 100); // TRM simulada a COP si es necesario
        
        // Firma de integridad simulada (Wompi la requiere para widget)
        $integrity_signature = hash("sha256", $reference . $amount_in_cents . "COP" . "secreto_test");

        $paymentData = [
            'public_key' => 'pub_test_XyZ...',
            'currency' => 'COP',
            'amount_in_cents' => $amount_in_cents,
            'reference' => $reference,
            'signature:integrity' => $integrity_signature,
            'redirect_url' => url('admin/subscription/callback'),
            'customer_email' => $business_email
        ];

        return view('admin.subscription.pay', compact('sub', 'paymentData'));
    }

    public function callback(Request $request)
    {
        $transaction_id = $request->input('id', 'SIM-' . time());
        
        // Simulación: Activamos el Plan Pro ($20) para pruebas
        $amountReceived = 20.00; 
        $business_id = session('business_id', 1);

        return $this->processPaymentConfirmation($business_id, $amountReceived, $transaction_id);
    }

    /**
     * Lógica central para detectar el plan según el monto y activarlo
     */
    private function processPaymentConfirmation($business_id, $amount, $transaction_id = null)
    {
        // 1. Buscar el plan que coincida con el precio pagado
        // (En un sistema real podrías tener variaciones, pero aquí usamos coincidencia exacta)
        $plan = \DB::table('saas_plans')
                    ->where('price', $amount)
                    ->where('is_active', true)
                    ->first();

        if (!$plan) {
            return redirect('admin/subscription')->with('error', 'No se encontró un plan que coincida con el monto pagado ($'.$amount.'). Por favor contacte a soporte.');
        }

        // 2. Registrar el pago en el historial
        \DB::table('saas_payments')->insert([
            'business_id' => $business_id,
            'reference_code' => 'PAY-' . time(),
            'transaction_id' => $transaction_id,
            'amount' => $amount,
            'status' => 'APPROVED',
            'paid_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 3. Actualizar o Crear la suscripción activa
        $subscription = \DB::table('saas_subscriptions')
                            ->where('business_id', $business_id)
                            ->first();

        $startsAt = date('Y-m-d H:i:s');
        $endsAt = date('Y-m-d H:i:s', strtotime('+30 days'));

        if ($subscription) {
            \DB::table('saas_subscriptions')
                ->where('id', $subscription->id)
                ->update([
                    'plan_id' => $plan->id,
                    'status' => 'active',
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        } else {
            \DB::table('saas_subscriptions')->insert([
                'business_id' => $business_id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $message = "¡Pago Exitoso! Tu plan ha sido actualizado a: " . $plan->name;
        if ($plan->has_ai) {
            $message .= ". Los Agentes de IA han sido ACTIVADOS.";
        } else {
            $message .= ". Nota: Este plan no incluye Agentes de IA.";
        }

        return redirect('admin/subscription')->with('success', $message);
    }

    /**
     * Método estático útil para verificar si un negocio tiene acceso a una característica
     */
    public static function hasFeature($feature)
    {
        $business_id = session('business_id', 1);
        $subscription = \DB::table('saas_subscriptions')
                            ->join('saas_plans', 'saas_subscriptions.plan_id', '=', 'saas_plans.id')
                            ->where('saas_subscriptions.business_id', $business_id)
                            ->where('saas_subscriptions.status', 'active')
                            ->where('saas_subscriptions.ends_at', '>', date('Y-m-d H:i:s'))
                            ->select('saas_plans.*')
                            ->first();

        if (!$subscription) return false;

        return isset($subscription->$feature) && $subscription->$feature;
    }
}
