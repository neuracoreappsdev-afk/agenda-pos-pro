<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ReferenceSource;
use App\Models\Customer;

echo "Seeding Reference Sources...\n";

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
    // Check if exists manually if firstOrCreate is acting up, but it should be fine.
    // Creating manually to be safe with older Laravel versions if needed
    $exists = ReferenceSource::where('name', $name)->first();
    if (!$exists) {
        ReferenceSource::create([
            'name' => $name,
            'description' => $desc,
            'active' => 1
        ]);
        echo "Created: $name\n";
    }
}

// 2. Assign random sources to existing customers
echo "Assigning random sources to customers...\n";
$customers = Customer::whereNull('reference_source_id')->get();
$sourceIds = ReferenceSource::lists('id')->all(); // lists() for L5.1/5.2, pluck() for newer

if (!empty($sourceIds)) {
    foreach ($customers as $c) {
        // 70% chance to have a source
        if (rand(1, 100) <= 70) {
            $c->reference_source_id = $sourceIds[array_rand($sourceIds)];
            $c->save();
        }
    }
    echo "Assigned sources to " . count($customers) . " customers.\n";
} else {
    echo "No sources found to assign.\n";
}

echo "Done.\n";
