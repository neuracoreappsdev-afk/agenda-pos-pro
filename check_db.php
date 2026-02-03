<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$migrations = DB::table('migrations')->orderBy('id', 'desc')->take(10)->get();
echo "\nLast 10 Migrations in DB:\n";
foreach ($migrations as $m) {
    echo $m->migration . "\n";
}

if (Schema::hasTable('sales')) {
    echo "\nTable sales EXISTS\n";
    print_r(Schema::getColumnListing('sales'));
} else {
    echo "\nTable sales NOT FOUND\n";
}
