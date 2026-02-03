<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

echo "Adding display_order column...\n";

if (!Schema::hasColumn('packages', 'display_order')) {
    Schema::table('packages', function (Blueprint $table) {
        $table->integer('display_order')->default(0);
    });
    echo "Column added successfully.\n";
} else {
    echo "Column already exists.\n";
}
