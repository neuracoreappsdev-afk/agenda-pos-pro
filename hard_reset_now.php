<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

$tables = [
    'appointments', 
    'customers', 
    'sales', 
    'sale_items', 
    'cash_movements', 
    'cash_register_sessions',
    'packages',
    'specialists',
    'notifications',
    'waitlist',
    'news',
    'messages',
    'products',
    'technical_sheets',
    'bonos',
    'booking_locks',
    'ai_conversation_logs',
    'loyalty_points',
    'support_tickets',
    'service_recipes',
    'consent_forms'
];

echo "Iniciando Limpieza de Fábrica...\n";

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        try {
            DB::table($table)->delete();
            echo "[-] Tabla '$table' limpiada.\n";
        } catch (Exception $e) {
            echo "[!] Error en tabla '$table': " . $e->getMessage() . "\n";
        }
    }
}

// Limpiar admins excepto el principal (ID 1) si existe
try {
    $deletedAdmins = DB::table('admin')->where('id', '>', 1)->delete();
    echo "[-] $deletedAdmins administradores secundarios eliminados.\n";
    
    // Asegurar que el admin #1 sea 'admin' y esté aprobado
    DB::table('admin')->where('id', 1)->update(['is_approved' => 1]);
    echo "[+] Admin principal verificado y aprobado.\n";
} catch (Exception $e) {
    echo "[!] Error limpiando admins: " . $e->getMessage() . "\n";
}

echo "\n¡ÉXITO! El software está ahora en CERO (0) y listo para producción.\n";
