<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Packages:\n";
print_r(\App\Models\Package::all(['id', 'package_name', 'category', 'commission_percentage'])->toArray());

echo "\nProducts:\n";
print_r(\App\Models\Product::all(['id', 'name', 'category'])->toArray());
