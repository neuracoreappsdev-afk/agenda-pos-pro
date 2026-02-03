<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "--- TABLES ---\n";
$tables = DB::select('SHOW TABLES');
print_r($tables);

echo "\n--- BUSINESSES ---\n";
try {
    $rows = DB::table('businesses')->get();
    foreach($rows as $r) {
        print_r($r);
    }
} catch (\Exception $e) {
    echo "NO BUSINESSES TABLE: " . $e->getMessage();
}
