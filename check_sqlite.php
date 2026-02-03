<?php
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $stmt = $pdo->query('SELECT count(*) FROM specialists');
    $count = $stmt->fetchColumn();
    echo "Specialists in SQLite: " . $count . "\n";
    
    if ($count > 0) {
        $stmt = $pdo->query('SELECT name FROM specialists');
        while ($row = $stmt->fetch()) {
            echo "- " . $row['name'] . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
