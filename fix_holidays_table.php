<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Checking 'holidays' table...\n";
if (!Schema::hasTable('holidays')) {
    echo "Table 'holidays' is MISSING. Creating it...\n";
    try {
        Schema::create('holidays', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->date('date');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        echo "Table 'holidays' created successfully.\n";
    } catch (\Exception $e) {
        echo "Error creating table: " . $e->getMessage() . "\n";
    }
} else {
    echo "Table 'holidays' exists.\n";
}
