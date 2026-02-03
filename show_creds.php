<?php
$db = new SQLite3('database/database.sqlite');
$result = $db->query('SELECT name, email, pin, active FROM specialists WHERE active = 1');

echo "CREDENCIALES:\n";
while($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo $row['name'] . " - " . $row['email'] . " - PIN:" . $row['pin'] . "\n";
}
$db->close();
