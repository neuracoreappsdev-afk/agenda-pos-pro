<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Adding columns to appointments table...\n";

try {
    Schema::table('appointments', function (Blueprint $table) {
        if (!Schema::hasColumn('appointments', 'client_arrived_at')) {
            $table->timestamp('client_arrived_at')->nullable();
            echo "- Add client_arrived_at: OK\n";
        }
        if (!Schema::hasColumn('appointments', 'arrival_acknowledged')) {
            $table->boolean('arrival_acknowledged')->default(false);
            echo "- Add arrival_acknowledged: OK\n";
        }
    });
    echo "Process finished successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
