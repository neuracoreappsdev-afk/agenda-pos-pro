<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    if (!Schema::hasTable('bonos')) {
        Schema::create('bonos', function ($table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('recipient_name');
            $table->string('recipient_email')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->text('message')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('balance', 15, 2);
            $table->enum('status', ['pending', 'paid', 'partially_used', 'used', 'expired'])->default('pending');
            $table->date('expiry_date')->nullable();
            $table->string('payment_id')->nullable();
            $table->timestamps();
        });
        echo "Table 'bonos' created successfully!\n";
    } else {
        echo "Table 'bonos' already exists.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
