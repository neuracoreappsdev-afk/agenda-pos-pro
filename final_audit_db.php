<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=booking_app', 'root', '');
$stmt = $pdo->query("DESCRIBE cash_register_sessions");
echo "Column | Null | Default\n";
echo "-------|------|---------\n";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . " | " . $row['Null'] . " | " . ($row['Default'] === null ? 'NULL' : $row['Default']) . "\n";
}
echo "\nChecking Users table...\n";
$stmt = $pdo->query("SELECT id FROM users LIMIT 1");
$user = $stmt->fetch(PDO::FETCH_ASSOC);
echo "User ID found: " . ($user['id'] ?? 'NONE') . "\n";
