<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = DB::table('users')->first();
$specialist = DB::table('specialists')->first();
$customer = DB::table('customers')->first();
$session = DB::table('cash_register_sessions')->where('status', 'open')->first();

echo "User ID: " . ($user ? $user->id : 'None') . "\n";
echo "Specialist ID: " . ($specialist ? $specialist->id : 'None') . "\n";
echo "Customer ID: " . ($customer ? $customer->id : 'None') . "\n";
echo "Session ID: " . ($session ? $session->id : 'None') . "\n";
