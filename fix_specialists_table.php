<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Adding 'email' column to 'specialists' table...\n";
try {
    if (!Schema::hasColumn('specialists', 'email')) {
        Schema::table('specialists', function($table) {
            $table->string('email')->nullable()->after('name');
        });
        echo "Column 'email' added successfully.\n";
    } else {
        echo "Column 'email' already exists.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
