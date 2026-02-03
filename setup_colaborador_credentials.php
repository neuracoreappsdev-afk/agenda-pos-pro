<?php
require_once 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Specialist;

echo "=== CONFIGURANDO CREDENCIALES DE COLABORADORES ===\n\n";

// Get all specialists
$specialists = Specialist::all();

if ($specialists->isEmpty()) {
    echo "No hay especialistas. Creando uno de prueba...\n";
    Specialist::create([
        'name' => 'Colaborador Demo',
        'title' => 'Especialista',
        'email' => 'demo@agendapos.com',
        'pin' => '1234',
        'active' => 1,
        'avatar' => 'https://ui-avatars.com/api/?name=Demo&background=3b82f6&color=fff'
    ]);
    $specialists = Specialist::all();
}

$credenciales = [];

foreach($specialists as $index => $s) {
    // Generate email if missing
    $email = $s->email;
    if (empty($email)) {
        $nameParts = explode(' ', strtolower($s->name));
        $email = $nameParts[0] . ($index + 1) . '@agendapos.com';
    }
    
    // Generate PIN if missing
    $pin = $s->pin;
    if (empty($pin)) {
        $pin = str_pad(($index + 1) * 1111, 4, '0', STR_PAD_LEFT);
    }
    
    // Update specialist
    $s->email = $email;
    $s->pin = $pin;
    $s->active = 1;
    $s->pin_reset_required = 0;
    $s->save();
    
    $credenciales[] = [
        'nombre' => $s->name,
        'email' => $email,
        'pin' => $pin
    ];
    
    echo "Actualizado: {$s->name}\n";
}

echo "\n=== CREDENCIALES PARA LOGIN ===\n\n";
foreach($credenciales as $c) {
    echo "Nombre: {$c['nombre']}\n";
    echo "Email: {$c['email']}\n";
    echo "PIN: {$c['pin']}\n";
    echo "-----------------------------\n";
}

echo "\n[OK] Ahora puedes acceder a http://localhost:8000/colaborador/login\n";
