<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

function describeTable($table) {
    echo "\nTable: $table\n";
    $columns = DB::select("PRAGMA table_info($table)"); // Assuming SQLite based on previous interactions
    foreach ($columns as $col) {
        echo "{$col->name} ({$col->type})\n";
    }
}

describeTable('products');
describeTable('packages');
