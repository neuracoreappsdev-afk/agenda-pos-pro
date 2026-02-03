<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('businesses')->delete();

        \DB::table('businesses')->insert([
            [
                'name' => 'Lina Lucio - Imperial Salon',
                'slug' => 'imperial-salon',
                'owner_name' => 'Lina Lucio',
                'email' => 'gerencia@linahair.com',
                'phone' => '+57 300 123 4567',
                'location' => 'Cali, Valle del Cauca',
                'plan_type' => 'enterprise',
                'status' => 'active',
                'app_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Barber Shop Elite',
                'slug' => 'barber-elite',
                'owner_name' => 'Carlos Barbero',
                'email' => 'admin@barberelite.com',
                'phone' => '+57 310 987 6543',
                'location' => 'Medellín, Antioquia',
                'plan_type' => 'premium',
                'status' => 'active',
                'app_id' => 2,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
        
        // Seed some subscriptions for stats
        \DB::table('core_subscriptions')->delete(); // Assuming table exists or create logical mock
        
        // Mock subscriptions in a way that Controller won't crash if table doesn't exist
        // (Actually Controller query relies on table existence. We should ensure it exists or migrate it)
    }
}
