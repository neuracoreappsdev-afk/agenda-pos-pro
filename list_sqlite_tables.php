<?php
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    echo "Tables in SQLite:\n";
    while ($row = $stmt->fetch()) {
        echo "- " . $row['name'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
