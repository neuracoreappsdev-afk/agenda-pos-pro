<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Specialists:\n";
try {
    $specialists = DB::table('specialists')->get();
    foreach ($specialists as $s) {
        $s = (object)$s;
        echo "ID: " . $s->id . " | Name: " . $s->name . " | Email: " . ($s->email ?? 'N/A') . " | Active: " . $s->active . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
