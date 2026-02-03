<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaasPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks to allow truncation
        if (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        DB::table('saas_plans')->truncate();

        if (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $plans = [
            [
                'name' => 'Plan Base (Gratuito)',
                'slug' => 'base-free',
                'description' => 'Software de gestión completo para tu peluquería: Agenda, Caja, Inventario y Clientes.',
                'price' => 0.00,
                'billing_cycle_days' => 30, // Renovación automática gratuita
                'max_users' => 100, // Prácticamente ilimitado para una pyme
                'max_branches' => 1,
                'whatsapp_integration' => false,
                'has_ai' => false,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Plan IA Premium',
                'slug' => 'ai-premium',
                'description' => 'Todo el Plan Base + La potencia de la Inteligencia Artificial: Recepcionista 24/7, Recordatorios automáticos y Agentes de Fidelización.',
                'price' => 200000.00, // 200.000 COP
                'billing_cycle_days' => 30,
                'max_users' => 100,
                'max_branches' => 1,
                'whatsapp_integration' => true, // Necesario para los agentes
                'has_ai' => true,
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('saas_plans')->insert($plans);
    }
}
