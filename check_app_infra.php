<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$tables = ['businesses', 'apps', 'products'];
$info = [];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        $info[$table] = Schema::getColumnListing($table);
    } else {
        $info[$table] = 'NOT_EXISTS';
    }
}

echo json_encode($info, JSON_PRETTY_PRINT);
