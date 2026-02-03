<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Holiday;

$holidays = Holiday::all();
echo "Total holidays: " . $holidays->count() . "\n";
foreach ($holidays as $h) {
    echo "ID: {$h->id}, Date: {$h->date}, Name: {$h->name}, Active: {$h->active}\n";
}
