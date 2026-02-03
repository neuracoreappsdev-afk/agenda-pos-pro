<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

$tables = ['sale_items', 'inventory_logs', 'payments', 'cash_movements', 'sales'];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "TABLE: $table\n";
        $columns = Schema::getColumnListing($table);
        foreach ($columns as $column) {
            echo " - $column\n";
        }
    } else {
        echo "TABLE: $table (NOT EXISTS)\n";
    }
    echo "-------------------\n";
}
