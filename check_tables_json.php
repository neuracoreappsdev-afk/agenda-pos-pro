<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

$tables = ['sale_items', 'inventory_logs', 'payments', 'cash_movements', 'sales'];
$result = [];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        $result[$table] = Schema::getColumnListing($table);
    } else {
        $result[$table] = "NOT EXISTS";
    }
}

echo json_encode($result, JSON_PRETTY_PRINT);
