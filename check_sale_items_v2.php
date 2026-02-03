<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

$schema = DB::select("DESCRIBE sale_items");
$cols = [];
foreach ($schema as $col) {
    // Just get the Field name
    $cols[] = $col->Field;
}
echo "COLS: " . implode('|', $cols) . "\n";
