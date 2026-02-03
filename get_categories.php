<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Unique Package Categories:\n";
print_r(\App\Models\Package::select('category')->distinct()->pluck('category')->toArray());

echo "\nUnique Product Categories:\n";
print_r(\App\Models\Product::select('category')->distinct()->pluck('category')->toArray());
