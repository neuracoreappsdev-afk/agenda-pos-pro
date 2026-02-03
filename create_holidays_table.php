<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Create holidays table
Schema::dropIfExists('holidays');

Schema::create('holidays', function($table) {
    $table->increments('id');
    $table->date('date');
    $table->string('name');
    $table->string('country_code', 5)->default('CO');
    $table->boolean('active')->default(true); // If true, it's a non-working day
    $table->timestamps();
});

echo "Table 'holidays' created successfully!\n";
