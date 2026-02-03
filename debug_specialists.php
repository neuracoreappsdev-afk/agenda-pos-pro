<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
try {
    echo 'Specialists count: ' . \App\Models\Specialist::count() . "\n";
    $specialists = \App\Models\Specialist::all();
    foreach ($specialists as $s) {
        echo "- " . $s->name . " (ID: " . $s->id . ")\n";
    }
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
