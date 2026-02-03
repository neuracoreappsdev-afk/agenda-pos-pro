<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=booking_app', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = ['sales'];
    foreach ($tables as $table) {
        echo "\nTable: $table\n";
        $stmt = $pdo->query("DESCRIBE $table");
        $cols = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cols[] = $row['Field'];
        }
        echo implode(", ", $cols) . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
