<?php

use Illuminate\Database\Seeder;
use App\Models\ReferenceSource;
use App\Models\Customer;

class ReferenceSourcesTableSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Default Sources
        $sources = [
            'Google' => 'Búsqueda en Google, Maps, etc.',
            'Instagram' => 'Perfil de Instagram, Ads, Stories',
            'Facebook' => 'Página de Facebook, Marketplace',
            'TikTok' => 'Videos virales, perfil de TikTok',
            'Recomendación' => 'Recomendado por un amigo o familiar',
            'Pasaba por aquí' => 'Cliente de paso / Tráfico peatonal',
            'Email Marketing' => 'Boletines, promociones por correo',
            'Publicidad Impresa' => 'Volantes, revistas, periódicos',
            'Otro' => 'Otras fuentes no listadas'
        ];

        foreach ($sources as $name => $desc) {
            ReferenceSource::firstOrCreate(
                ['name' => $name],
                ['description' => $desc, 'active' => 1]
            );
        }

        // 2. Assign random sources to existing customers (Simulation for Demo)
        // Only if they don't have one
        $customers = Customer::whereNull('reference_source_id')->get();
        $sourceIds = ReferenceSource::lists('id')->all();

        if (!empty($sourceIds)) {
            foreach ($customers as $c) {
                // 70% chance to have a source
                if (rand(1, 100) <= 70) {
                    $c->reference_source_id = $sourceIds[array_rand($sourceIds)];
                    $c->save();
                }
            }
        }
    }
}
