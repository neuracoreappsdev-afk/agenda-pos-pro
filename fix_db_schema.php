<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "Starting custom migration fixes...\n";

// 1. Create loyalty_points if not exists
if (!Schema::hasTable('loyalty_points')) {
    echo "Creating loyalty_points table...\n";
    Schema::create('loyalty_points', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('customer_id')->unsigned();
        $table->integer('points');
        $table->string('type')->default('earned');
        $table->integer('sale_id')->unsigned()->nullable();
        $table->string('reason')->nullable();
        $table->timestamps();
        $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
    });
    echo "loyalty_points created.\n";
} else {
    echo "loyalty_points already exists.\n";
}

// 2. Create technical_sheets if not exists
if (!Schema::hasTable('technical_sheets')) {
    echo "Creating technical_sheets table...\n";
    Schema::create('technical_sheets', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('customer_id')->unsigned();
        $table->integer('specialist_id')->unsigned()->nullable();
        $table->integer('service_id')->unsigned()->nullable();
        $table->text('notes')->nullable();
        $table->text('formula')->nullable();
        $table->text('products_used')->nullable();
        $table->string('attachments')->nullable();
        $table->timestamps();
        $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
    });
    echo "technical_sheets created.\n";
} else {
    echo "technical_sheets already exists.\n";
}

// 3. Add reference_source_id to customers
if (Schema::hasTable('customers')) {
    if (!Schema::hasColumn('customers', 'reference_source_id')) {
        echo "Adding reference_source_id to customers...\n";
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('reference_source_id')->unsigned()->nullable()->after('notes');
        });
        echo "Column added.\n";
    } else {
        echo "reference_source_id already exists on customers.\n";
    }
}

echo "Done.\n";
