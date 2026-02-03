<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Customer;

// Run python script to get JSON
$json = shell_exec('python export_clients.py');
$data = json_decode($json, true);

if (!$data || isset($data['error'])) {
    die("Error decoding JSON: " . ($data['error'] ?? 'Unknown error') . "\n");
}

echo "Starting import of " . count($data) . " clients...\n";

$imported = 0;
$skipped = 0;

foreach ($data as $row) {
    $id_num = trim($row['Identificación'] ?? '');
    $phone = trim($row['Celular'] ?? $row['Teléfono'] ?? '');
    $email = trim($row['Correo Electrónico'] ?? '');
    $fullname = trim($row['Nombre'] ?? '');
    
    if (!$fullname) {
        $skipped++;
        continue;
    }

    // Check for duplicates
    $exists = false;
    if ($id_num) {
        $exists = Customer::where('identification', $id_num)->exists();
    }
    
    if (!$exists && $phone) {
        $exists = Customer::where('contact_number', $phone)->exists();
    }
    
    if (!$exists && $email) {
        $exists = Customer::where('email', $email)->exists();
    }

    if ($exists) {
        $skipped++;
        continue;
    }

    // Split name
    $nameParts = explode(' ', $fullname);
    $first = $nameParts[0];
    $last = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '---';

    try {
        Customer::create([
            'first_name' => $first,
            'last_name' => $last,
            'identification' => $id_num ?: null,
            'id_type' => $row['Tipo id.'] ?? 'Cédula de ciudadanía',
            'dv' => $row['DV'] ?? null,
            'contact_number' => $phone ?: null,
            'email' => $email ?: null,
            'address' => $row['Dirección'] ?? null,
            'city' => $row['Municipio'] ?? null,
            'gender' => $row['Genero'] ?? null,
            'type' => $row['Tipo Persona'] ?? 'Persona',
            'notes' => $row['Notas'] ?? null,
            'company_name' => $row['Empresa'] ?? null,
            'wants_updates' => 1
        ]);
        $imported++;
    } catch (\Exception $e) {
        echo "Error importing {$fullname}: " . $e->getMessage() . "\n";
    }
}

echo "\nImport finished!\n";
echo "Imported: {$imported}\n";
echo "Skipped (duplicates or empty): {$skipped}\n";
