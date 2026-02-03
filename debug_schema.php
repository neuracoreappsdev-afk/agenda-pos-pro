<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

try {
    echo "Running Schema::create('payments')...\n";
    Schema::create('payments', function ($table) {
        $table->increments('id');
        $table->integer('sale_id')->unsigned();
        $table->integer('payment_method_id')->unsigned();
        $table->decimal('amount', 12, 2);
        $table->decimal('tip', 12, 2)->default(0);
        $table->timestamps();
    });
    echo "Success!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
