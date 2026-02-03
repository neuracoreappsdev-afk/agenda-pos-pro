<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=booking_app', 'root', '');
$stmt = $pdo->query("DESCRIBE cash_register_sessions");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . " - " . ($row['Null'] == 'NO' ? 'NOT NULL' : 'NULL') . "\n";
}
