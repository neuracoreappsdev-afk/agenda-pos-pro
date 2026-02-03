<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Raw SQL test...\n";
try {
    $res = DB::select('select * from "admin" where "username" = "admin" or "email" = "admin" limit 1');
    echo "Raw SQL successful.\n";
} catch (\Exception $e) {
    echo "Raw SQL failed: " . $e->getMessage() . "\n";
}
