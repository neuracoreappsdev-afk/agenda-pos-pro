<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Holiday;

$holidays2026 = [
    '2026-01-01' => 'Año Nuevo',
    '2026-01-05' => 'Reyes Magos',
    '2026-03-23' => 'San José',
    '2026-04-02' => 'Jueves Santo',
    '2026-04-03' => 'Viernes Santo',
    '2026-05-01' => 'Día del Trabajo',
    '2026-05-18' => 'Ascensión del Señor',
    '2026-06-08' => 'Corpus Christi',
    '2026-06-15' => 'Sagrado Corazón',
    '2026-06-29' => 'San Pedro y San Pablo',
    '2026-07-20' => 'Grito de Independencia',
    '2026-08-07' => 'Batalla de Boyacá',
    '2026-08-17' => 'Asunción de la Virgen',
    '2026-10-12' => 'Día de la Raza',
    '2026-11-02' => 'Todos los Santos',
    '2026-11-16' => 'Independencia de Cartagena',
    '2026-12-08' => 'Inmaculada Concepción',
    '2026-12-25' => 'Navidad'
];

foreach ($holidays2026 as $date => $name) {
    Holiday::updateOrCreate(
        ['date' => $date],
        [
            'name' => $name,
            'country_code' => 'CO',
            'active' => true
        ]
    );
}

echo "Successfully populated " . count($holidays2026) . " holidays for 2026.\n";
