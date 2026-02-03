<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$a = \App\Models\Appointment::find(9);
if ($a) {
    print_r($a->toArray());
    $c = $a->customer;
    if ($c) {
        echo "Customer Name: {$c->first_name} {$c->last_name}\n";
    }
} else {
    echo "Appointment 9 not found";
}
