<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$date = '2026-01-07';
echo "--- Appointments for $date ---\n";
$apts = \App\Models\Appointment::whereDate('appointment_datetime', '=', $date)->get();
foreach ($apts as $a) {
    echo "ID: {$a->id}, Customer: {$a->customer_id}, Time: {$a->appointment_datetime}\n";
}
