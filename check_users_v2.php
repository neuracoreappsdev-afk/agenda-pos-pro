<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Verificando App\\User...\n";
try {
    $users = \App\User::all();
    if ($users->isEmpty()) {
        echo "Tabla 'users' está VACÍA.\n";
        
        // Crear un usuario por defecto si está vacía
        echo "Creando usuario por defecto (ID 1)...\n";
        $u = new \App\User();
        $u->id = 1;
        $u->username = 'admin';
        $u->password = Hash::make('123456');
        $u->email = 'admin@example.com';
        $u->save();
        echo "Usuario creado: " . $u->id . "\n";
    } else {
        foreach ($users as $user) {
            echo "ID: " . $user->id . " - " . $user->username . "\n";
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
