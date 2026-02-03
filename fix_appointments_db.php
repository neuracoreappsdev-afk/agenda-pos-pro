<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Fixing appointments table schema...\n";

try {
    Schema::table('appointments', function (Blueprint $table) {
        if (!Schema::hasColumn('appointments', 'notes')) {
            $table->text('notes')->nullable();
        }
        if (!Schema::hasColumn('appointments', 'status')) {
            $table->string('status')->default('pendiente');
        }
        if (!Schema::hasColumn('appointments', 'color')) {
            $table->string('color')->default('#2563eb');
        }
        if (!Schema::hasColumn('appointments', 'confirm_token')) {
            $table->string('confirm_token')->nullable();
        }
    });
    echo "Success! Columns added to appointments table.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
