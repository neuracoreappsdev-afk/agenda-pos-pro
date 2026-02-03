<?php
$db = new PDO('sqlite:database/database.sqlite');

// Asignar emails de prueba a especialistas sin email
$db->exec("UPDATE specialists SET email = 'liliana@test.com' WHERE id = 1 AND (email IS NULL OR email = '')");
$db->exec("UPDATE specialists SET email = 'paola@test.com' WHERE id = 2 AND (email IS NULL OR email = '')");
$db->exec("UPDATE specialists SET email = 'cristian@test.com' WHERE id = 3 AND (email IS NULL OR email = '')");

// Asignar PIN de prueba "1234" a todos los especialistas sin PIN
$db->exec("UPDATE specialists SET pin = '1234' WHERE pin IS NULL OR pin = ''");

echo "=== CREDENCIALES DE ACCESO COLABORADORES ===\n\n";

// Mostrar especialistas con sus credenciales
$r = $db->query('SELECT id, name, email, pin, active FROM specialists');
while($row = $r->fetch(PDO::FETCH_ASSOC)) {
    echo "Nombre: " . $row['name'] . "\n";
    echo "Email: " . $row['email'] . "\n";
    echo "PIN: " . $row['pin'] . "\n";
    echo "Activo: " . ($row['active'] ? 'SI' : 'NO') . "\n";
    echo "---\n";
}

echo "\nPara ingresar usa:\n";
echo "URL: http://localhost:8000/colaborador/login\n";
