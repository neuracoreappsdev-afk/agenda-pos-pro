<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=booking_app', 'root', '');
$tables = ['sale_items', 'specialist_advances', 'cash_movements', 'sales', 'packages', 'products'];

foreach ($tables as $table) {
    echo "--- TABLE: $table ---\n";
    $stmt = $pdo->query("DESCRIBE $table");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    echo "\n";
}
