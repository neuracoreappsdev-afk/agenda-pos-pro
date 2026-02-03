<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

DB::table('audit_logs')->insert([
    [
        'user_id' => 1, 
        'user_name' => 'admin', 
        'action' => 'LOGIN', 
        'details' => 'Inicio de sesión exitoso', 
        'ip_address' => '127.0.0.1', 
        'created_at' => date('Y-m-d H:i:s')
    ],
    [
        'user_id' => 1, 
        'user_name' => 'admin', 
        'action' => 'CREATE_PLAN', 
        'details' => 'Creación de nuevo plan comercial: Membresía VIP', 
        'ip_address' => '127.0.0.1', 
        'created_at' => date('Y-m-d H:i:s')
    ]
]);
echo "Seed data inserted\n";
