<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Creating 'locations' table...\n";
try {
    if (!Schema::hasTable('locations')) {
        Schema::create('locations', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address')->nullable();
            $table->timestamps();
        });
        echo "Table 'locations' created.\n";
        
        // Add default location
        DB::table('locations')->insert([
            'name' => 'Sede Principal',
            'address' => 'Imperial Salon',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        echo "Default location added.\n";
    } else {
        echo "Table 'locations' already exists.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
