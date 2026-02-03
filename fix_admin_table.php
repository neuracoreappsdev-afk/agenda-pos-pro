<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Adding 'email' column to 'admin' table...\n";
try {
    if (!Schema::hasColumn('admin', 'email')) {
        Schema::table('admin', function($table) {
            $table->string('email')->nullable()->after('username');
        });
        echo "Column 'email' added successfully.\n";
        
        // Update default admin with a probable email
        DB::table('admin')->where('username', 'admin')->update(['email' => 'admin@gmail.com']);
        echo "Default admin email updated.\n";
    } else {
        echo "Column 'email' already exists.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
