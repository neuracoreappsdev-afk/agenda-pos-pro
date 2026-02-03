<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Packages count: " . \App\Models\Package::count() . "\n";
echo "Products count: " . \App\Models\Product::count() . "\n";
