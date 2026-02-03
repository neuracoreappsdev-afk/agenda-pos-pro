<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Columns in 'products':\n";
print_r(Schema::getColumnListing('products'));

echo "\nColumns in 'packages':\n";
print_r(Schema::getColumnListing('packages'));
