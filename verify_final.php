<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sale_id = 5;

echo "--- SALE ---\n";
print_r(DB::table('sales')->where('id', $sale_id)->first());

echo "\n--- SALE ITEMS ---\n";
print_r(DB::table('sale_items')->where('sale_id', $sale_id)->get());

echo "\n--- PAYMENTS ---\n";
print_r(DB::table('payments')->where('sale_id', $sale_id)->get());

echo "\n--- CASH MOVEMENTS ---\n";
print_r(DB::table('cash_movements')->where('sale_id', $sale_id)->get());
