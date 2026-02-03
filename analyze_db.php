<?php
// Script para ver todas las tablas y su contenido
$pdo = new PDO('mysql:host=127.0.0.1;dbname=booking_app', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== TABLAS EN LA BASE DE DATOS ===\n\n";

$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
    echo "- $table ($count registros)\n";
}

echo "\n=== ESTRUCTURA DE TABLAS PRINCIPALES ===\n";

$mainTables = ['packages', 'specialists', 'appointments', 'customers', 'bookings', 'products', 'sales', 'invoices'];
foreach ($mainTables as $table) {
    if (in_array($table, $tables)) {
        echo "\n--- $table ---\n";
        $cols = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            echo "  {$col['Field']} ({$col['Type']})\n";
        }
    }
}
