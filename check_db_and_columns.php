<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "DB Connection info:\n";
echo "Driver: " . DB::connection()->getDriverName() . "\n";
echo "Database: " . DB::connection()->getDatabaseName() . "\n";

echo "\nColumns in 'admin' table via Doctrine:\n";
try {
    $columns = Schema::getColumnListing('admin');
    foreach ($columns as $column) {
        echo "- $column\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
