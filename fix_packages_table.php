<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

echo "Updating packages table structure...\n";

Schema::table('packages', function (Blueprint $table) {
    if (!Schema::hasColumn('packages', 'sku')) {
        $table->string('sku')->nullable()->after('package_name');
    }
    if (!Schema::hasColumn('packages', 'category')) {
        $table->string('category')->nullable()->after('package_description');
    }
    if (!Schema::hasColumn('packages', 'commission_percentage')) {
        $table->decimal('commission_percentage', 5, 2)->default(0)->after('category');
    }
    if (!Schema::hasColumn('packages', 'active')) {
        $table->boolean('active')->default(1)->after('commission_percentage');
    }
    if (!Schema::hasColumn('packages', 'show_in_reservations')) {
        $table->boolean('show_in_reservations')->default(1)->after('active');
    }
    if (!Schema::hasColumn('packages', 'custom_commission')) {
        $table->boolean('custom_commission')->default(0)->after('show_in_reservations');
    }
    if (!Schema::hasColumn('packages', 'block_qty_pos')) {
        $table->boolean('block_qty_pos')->default(0)->after('custom_commission');
    }
    if (!Schema::hasColumn('packages', 'require_deposit')) {
        $table->boolean('require_deposit')->default(0)->after('block_qty_pos');
    }
    if (!Schema::hasColumn('packages', 'extended_schedule')) {
        $table->boolean('extended_schedule')->default(0)->after('require_deposit');
    }
    if (!Schema::hasColumn('packages', 'enable_loyalty')) {
        $table->boolean('enable_loyalty')->default(0)->after('extended_schedule');
    }
    if (!Schema::hasColumn('packages', 'prices_json')) {
        $table->text('prices_json')->nullable()->after('enable_loyalty');
    }
    if (!Schema::hasColumn('packages', 'products_json')) {
        $table->text('products_json')->nullable()->after('prices_json');
    }
    if (!Schema::hasColumn('packages', 'discounts_json')) {
        $table->text('discounts_json')->nullable()->after('products_json');
    }
});

echo "Structure updated successfully.\n";
