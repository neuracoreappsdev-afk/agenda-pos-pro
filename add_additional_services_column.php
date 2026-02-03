<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::table('appointments', function (Blueprint $table) {
    if (!Schema::hasColumn('appointments', 'additional_services')) {
        $table->text('additional_services')->nullable();
    }
});

echo "Column additional_services added to appointments table.\n";
