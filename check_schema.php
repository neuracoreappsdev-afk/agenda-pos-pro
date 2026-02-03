<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$cols = Illuminate\Support\Facades\Schema::getColumnListing('appointments');
echo "Columns in appointments table:\n";
print_r($cols);

$colsCust = Illuminate\Support\Facades\Schema::getColumnListing('customers');
echo "\nColumns in customers table:\n";
print_r($colsCust);
