<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Holiday;

try {
    $h = Holiday::updateOrCreate(
        ['date' => '2026-01-01'],
        [
            'name' => 'Año Nuevo ELOQUENT',
            'country_code' => 'CO',
            'active' => true
        ]
    );
    echo "Eloquent result: " . ($h ? "SUCCESS (ID: {$h->id})" : "FAILURE") . "\n";
    echo "Current count: " . Holiday::count() . "\n";
} catch (\Exception $e) {
    echo "ELOQUENT ERROR: " . $e->getMessage() . "\n";
}
