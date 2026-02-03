<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $res = DB::table('holidays')->insert([
        'date' => '2026-01-01',
        'name' => 'Año Nuevo TEST',
        'country_code' => 'CO',
        'active' => 1
    ]);
    echo "Insert result: " . ($res ? "SUCCESS" : "FAILURE") . "\n";
} catch (\Exception $e) {
    echo "INSERT ERROR: " . $e->getMessage() . "\n";
}
