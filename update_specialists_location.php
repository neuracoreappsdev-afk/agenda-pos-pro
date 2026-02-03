<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Updating specialists default location...\n";
try {
    DB::table('specialists')->update(['location_id' => 1]);
    echo "Specialists updated with location_id = 1.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
