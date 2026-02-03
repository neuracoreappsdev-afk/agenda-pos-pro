<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=booking_app', 'root', '');
$tables = ['sales', 'sale_items', 'specialist_advances', 'cash_movements'];

foreach ($tables as $table) {
    echo "TABLE: $table\n";
    $stmt = $pdo->query("DESCRIBE $table");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['Field'] . "\n";
    }
    echo "\n";
}
