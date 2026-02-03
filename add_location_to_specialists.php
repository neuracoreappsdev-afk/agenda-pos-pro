<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

try {
    if (!Schema::hasColumn('specialists', 'location_id')) {
        Schema::table('specialists', function($table) {
            $table->integer('location_id')->unsigned()->nullable()->after('id');
        });
        echo "Column 'location_id' added to specialists.\n";
    } else {
        echo "Column 'location_id' already exists in specialists.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
