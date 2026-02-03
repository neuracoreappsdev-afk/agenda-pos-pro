<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

try {
    Schema::table('sale_items', function ($table) {
        if (!Schema::hasColumn('sale_items', 'tax_amount')) {
            $table->decimal('tax_amount', 12, 2)->default(0);
        }
        if (!Schema::hasColumn('sale_items', 'commission_value')) {
            $table->decimal('commission_value', 12, 2)->default(0);
        }
        if (!Schema::hasColumn('sale_items', 'discount_value')) {
            $table->decimal('discount_value', 12, 2)->default(0);
        }
    });

    Schema::table('cash_movements', function ($table) {
        if (!Schema::hasColumn('cash_movements', 'cash_register_session_id')) {
            $table->integer('cash_register_session_id')->unsigned()->nullable();
        }
        if (!Schema::hasColumn('cash_movements', 'sale_id')) {
            $table->integer('sale_id')->unsigned()->nullable();
        }
    });
    echo "Success!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
