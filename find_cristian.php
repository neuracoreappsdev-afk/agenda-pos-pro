<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$c = \App\Models\Customer::where('first_name', 'LIKE', '%CRISTIAN%')->first();
if ($c) {
    echo "Found Customer: ID: {$c->id}, Name: {$c->first_name} {$c->last_name}\n";
    $apts = \App\Models\Appointment::where('customer_id', $c->id)->get();
    echo "Appointments Count: " . $apts->count() . "\n";
    foreach ($apts as $a) {
        echo "Apt ID: {$a->id}, Date: {$a->appointment_datetime}, Created: {$a->created_at}\n";
    }
} else {
    echo "Cristian not found\n";
}
