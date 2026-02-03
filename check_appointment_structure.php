<?php
require_once 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== APPOINTMENTS SCHEMA ===\n";
$columns = DB::select("PRAGMA table_info(appointments)");
foreach ($columns as $column) {
    echo "{$column->name} ({$column->type})\n";
}

echo "\n=== SAMPLE APPOINTMENT DATA ===\n";
$appointment = App\Models\Appointment::with(['customer', 'package'])->first();
if ($appointment) {
    echo "ID: {$appointment->id}\n";
    echo "Status: {$appointment->status}\n";
    echo "Datetime: {$appointment->appointment_datetime}\n";
    echo "Specialist ID: {$appointment->specialist_id}\n";
    echo "Package ID: {$appointment->package_id}\n";
} else {
    echo "No appointments found.\n";
}

// Check for pivot table or JSON field for extra services
echo "\n=== CHECKING FOR EXTRA SERVICES STRUCTURE ===\n";
$tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
foreach ($tables as $table) {
    if (strpos($table->name, 'appointment') !== false || strpos($table->name, 'service') !== false) {
        echo "Table: {$table->name}\n";
    }
}
