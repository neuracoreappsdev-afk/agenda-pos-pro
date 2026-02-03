<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$columns = DB::getSchemaBuilder()->getColumnListing('appointments');
echo implode(", ", $columns) . "\n";
?>
