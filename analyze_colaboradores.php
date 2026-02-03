<?php
require_once 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ANALISIS MODULO COLABORADORES ===\n\n";

// 1. Check specialists count
$specialists = App\Models\Specialist::all();
echo "1. ESPECIALISTAS EN DB: " . count($specialists) . "\n\n";

// 2. Check specialists with email and pin
$withCredentials = App\Models\Specialist::whereNotNull('email')
    ->whereNotNull('pin')
    ->where('active', 1)
    ->get();
echo "2. COLABORADORES CON CREDENCIALES COMPLETAS (email + pin + activo): " . count($withCredentials) . "\n\n";

if (count($withCredentials) > 0) {
    foreach($withCredentials as $s) {
        echo "   - " . $s->name . " | " . $s->email . " | PIN: " . $s->pin . "\n";
    }
} else {
    echo "   [!] NO HAY COLABORADORES CON CREDENCIALES PARA LOGIN\n";
}

echo "\n3. TODOS LOS ESPECIALISTAS:\n";
foreach($specialists as $s) {
    echo "   ID: " . $s->id . " | " . $s->name . " | email: " . ($s->email ?: 'NULL') . " | PIN: " . ($s->pin ?: 'NULL') . " | Activo: " . ($s->active ? 'Si' : 'No') . "\n";
}

// 3. Check tables
echo "\n4. TABLAS RELACIONADAS:\n";
$tables = ['specialists', 'messages', 'news'];
foreach($tables as $table) {
    try {
        $count = DB::table($table)->count();
        echo "   - $table: $count registros\n";
    } catch(Exception $e) {
        echo "   - $table: ERROR - " . $e->getMessage() . "\n";
    }
}
