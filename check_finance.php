<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "--- TABLES ---\n";
print_r(Schema::getConnection()->getDoctrineSchemaManager()->listTableNames());

echo "\n--- BUSINESSES ---\n";
print_r(DB::table('businesses')->get());
