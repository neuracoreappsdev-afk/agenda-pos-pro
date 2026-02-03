<?php

use Illuminate\Database\Seeder;
use App\Models\Specialist;

class SpecialistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Especialista 1 - Liliana Gutiérrez
        Specialist::create([
            'name' => 'Liliana',
            'last_name' => 'Gutiérrez',
            'title' => 'Especialista Senior',
            'email' => 'liliana@agendapos.com',
            'phone' => '3001234567',
            'pin' => '1234',
            'active' => 1,
            'pin_reset_required' => 0,
            'avatar' => 'https://ui-avatars.com/api/?name=Liliana+G&background=f3e8ff&color=7e22ce',
            'category' => 'senior',
            'commissions' => json_encode(['percentage' => 40]),
        ]);

        // Especialista 2 - Paola Ramirez
        Specialist::create([
            'name' => 'Paola',
            'last_name' => 'Ramirez',
            'title' => 'Estilista de Cejas',
            'email' => 'paola@agendapos.com',
            'phone' => '3009876543',
            'pin' => '2345',
            'active' => 1,
            'pin_reset_required' => 0,
            'avatar' => 'https://ui-avatars.com/api/?name=Paola+R&background=dcfce7&color=15803d',
            'category' => 'estilista',
            'commissions' => json_encode(['percentage' => 35]),
        ]);

        // Especialista 3 - Carolina Luna
        Specialist::create([
            'name' => 'Carolina',
            'last_name' => 'Luna',
            'title' => 'Asesora de Imagen',
            'email' => 'carolina@agendapos.com',
            'phone' => '3005551234',
            'pin' => '3456',
            'active' => 1,
            'pin_reset_required' => 0,
            'avatar' => 'https://ui-avatars.com/api/?name=Carolina+L&background=ffedd5&color=c2410c',
            'category' => 'asesora',
            'commissions' => json_encode(['percentage' => 30]),
        ]);

        // Especialista 4 - Demo (para pruebas rápidas)
        Specialist::create([
            'name' => 'Demo',
            'last_name' => 'Colaborador',
            'title' => 'Usuario de Prueba',
            'email' => 'demo@agendapos.com',
            'phone' => '3000000000',
            'pin' => '0000',
            'active' => 1,
            'pin_reset_required' => 0,
            'avatar' => 'https://ui-avatars.com/api/?name=Demo&background=3b82f6&color=ffffff',
            'category' => 'general',
            'commissions' => json_encode(['percentage' => 25]),
        ]);
    }
}
