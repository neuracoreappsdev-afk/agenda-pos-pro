<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Package;

$json = file_get_contents('services_to_import.json');
$services = json_decode($json, true);

echo "Detected " . count($services) . " services.\n";

echo "Truncating packages table...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
Package::truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "Starting import...\n";

$imported = 0;
foreach ($services as $svc) {
    try {
        $data = [
            'package_name' => $svc['name'],
            'sku' => $svc['sku'],
            'package_price' => $svc['price'],
            'package_time' => $svc['duration'],
            'category' => $svc['category'],
            'package_description' => '',
            'active' => 1,
            'show_in_reservations' => 1
        ];
        
        Package::create($data);
        $imported++;
    } catch (\Exception $e) {
        echo "Error importing {$svc['name']}: " . $e->getMessage() . "\n";
    }
}

echo "Done. Final count: " . Package::count() . "\n";
echo "Successfully imported $imported services.\n";
