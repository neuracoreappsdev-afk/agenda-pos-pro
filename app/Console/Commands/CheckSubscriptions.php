<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saas:check-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica suscripciones SaaS próximas a vencer y envía recordatorios';

    public function handle()
    {
        $this->info("Iniciando auditoría diaria de suscripciones SaaS...");
        
        // Buscar suscripciones que vencen en los próximos 3 días
        // Asumiendo tablas: saas_subscriptions (ends_at, id, business_id)
        
        $now = \Carbon\Carbon::now();
        $threeDays = \Carbon\Carbon::now()->addDays(3);
        
        try {
            $subscriptions = \DB::table('saas_subscriptions')
                ->where('status', 'active')
                ->where('ends_at', '<=', $threeDays)
                ->where('ends_at', '>', $now)
                ->get();
                
            $count = 0;
            
            foreach ($subscriptions as $sub) {
                $business = \DB::table('businesses')->where('id', $sub->business_id)->first();
                if (!$business) continue;
                
                $daysLeft = $now->diffInDays(\Carbon\Carbon::parse($sub->ends_at), false);
                $daysLeft = ceil($daysLeft);
                
                // Lógica de Frecuencia: 3, 2, 1 días
                if ($daysLeft <= 3 && $daysLeft >= 0) {
                    $this->sendReminder($business, $sub, $daysLeft);
                    $count++;
                }
            }
            
            $this->info("Proceso finalizado. Recordatorios enviados: $count");
            
            // También desactivar las vencidas
            $expired = \DB::table('saas_subscriptions')
                ->where('status', 'active')
                ->where('ends_at', '<', $now)
                ->update(['status' => 'past_due']);
                
            if ($expired > 0) {
                $this->warn("Se han suspendido $expired suscripciones vencidas.");
            }
            
        } catch (\Exception $e) {
            $this->error("Error crítico: " . $e->getMessage());
        }
    }
    
    private function sendReminder($business, $subscription, $days)
    {
        $paymentLink = url('admin/subscription/pay/' . $subscription->id); // Ruta hipotética de pago
        
        // 1. Mensaje Email (Simulado Log)
        $subject = "⚠️ Importante: Tu suscripción vence en $days días";
        if ($days == 0 || $days < 1) $subject = "🚨 URGENTE: Tu suscripción vence HOY";
        
        $this->line(" >> EMAIL a [{$business->email}]: $subject. Link: $paymentLink");
        
        // 2. Mensaje WhatsApp (Integración simulada)
        $waMessage = "Hola {$business->owner_name}, recordamos que tu plan AgendaPOS vence en *$days días*. Evita el bloqueo del servicio realizando tu pago aquí: $paymentLink";
        
        $this->line(" >> WHATSAPP a [{$business->phone}]: $waMessage");
        
        // Aquí iría la llamada real a la API de WhatsApp (Twilio/Wati/Meta)
        // \App\Services\WhatsApp::send($business->phone, $waMessage);
    }
}
