<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Seeding subscription data...\n";

$businesses = DB::table('businesses')->get();

// Si no hay negocios, crear 1 para el ejemplo si es necesario, 
// pero asumamos que hay al menos el de prueba ID:1 o similar que vimos antes.

$months = [
    ['period' => '2025-10', 'date' => '2025-10-05'],
    ['period' => '2025-11', 'date' => '2025-11-05'],
    ['period' => '2025-12', 'date' => '2025-12-05'],
    ['period' => '2026-01', 'date' => '2026-01-05'],
];

foreach ($months as $m) {
    // Simular ingresos crecientes
    $amount = 500000 + (rand(0, 10) * 10000); 
    if ($m['period'] == '2026-01') $amount *= 1.2; // Aumento en enero

    DB::table('core_subscriptions')->insert([
        'business_id' => 1,
        'amount' => $amount,
        'period' => $m['period'],
        'payment_date' => $m['date'],
        'status' => 'paid',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);
}

echo "Done seeding.\n";
