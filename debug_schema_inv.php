<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

try {
    echo "Running Schema::create('inventory_logs')...\n";
    Schema::create('inventory_logs', function ($table) {
        $table->increments('id');
        $table->integer('product_id')->unsigned();
        $table->decimal('quantity', 12, 2);
        $table->string('type'); // in, out
        $table->string('reason');
        $table->timestamps();
    });
    echo "Success!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
