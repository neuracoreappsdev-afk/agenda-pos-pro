<?php
require_once 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$specialists = App\Models\Specialist::select('id', 'name', 'email', 'pin', 'active')->get();

echo "\n=== CREDENCIALES DE COLABORADORES ===\n\n";

foreach($specialists as $s) {
    echo "----------------------------------------\n";
    echo "Nombre: " . $s->name . "\n";
    echo "Email: " . $s->email . "\n";  
    echo "PIN: " . $s->pin . "\n";
    echo "Activo: " . ($s->active ? 'Si' : 'No') . "\n";
}
echo "----------------------------------------\n";
