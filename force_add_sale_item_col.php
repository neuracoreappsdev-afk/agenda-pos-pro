<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

echo "Adding specialist_id column to sale_items...\n";

if (!Schema::hasColumn('sale_items', 'specialist_id')) {
    Schema::table('sale_items', function (Blueprint $table) {
        $table->unsignedInteger('specialist_id')->nullable()->after('total');
    });
    echo "Column added successfully.\n";
} else {
    echo "Column already exists.\n";
}
