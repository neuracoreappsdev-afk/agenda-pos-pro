<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

$sedes = \App\Models\Setting::get('sedes', []);
echo "Sedes from settings:\n";
print_r($sedes);
