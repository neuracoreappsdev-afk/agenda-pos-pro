<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "--- Last 5 appointments by ID ---\n";
$apts = \App\Models\Appointment::orderBy('id', 'desc')->limit(5)->get();
foreach ($apts as $a) {
    echo "ID: {$a->id}, Customer: {$a->customer_id}, Type: {$a->appointment_type}, Created: {$a->created_at}, Data: " . json_encode($a->toArray()) . "\n";
}
