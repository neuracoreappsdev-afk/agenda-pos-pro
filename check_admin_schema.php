<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Schema for 'admin' table:\n";
try {
    $columns = \DB::getSchemaBuilder()->getColumnListing('admin');
    foreach ($columns as $column) {
        echo "- $column\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
