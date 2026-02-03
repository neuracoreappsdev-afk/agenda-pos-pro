<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$columns = DB::getSchemaBuilder()->getColumnListing('sales');
print_r($columns);
