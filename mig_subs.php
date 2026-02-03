<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

if (!Schema::hasTable('core_subscriptions')) {
    Schema::create('core_subscriptions', function ($table) {
        $table->increments('id');
        $table->integer('business_id')->unsigned();
        $table->decimal('amount', 12, 2);
        $table->string('period');
        $table->date('payment_date');
        $table->string('status')->default('paid');
        $table->string('payment_method')->nullable();
        $table->string('invoice_number')->nullable();
        $table->timestamps();
    });
    echo "Table core_subscriptions created.\n";
} else {
    echo "Table already exists.\n";
}
