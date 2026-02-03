<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "Checking customers table...\n";
if (Schema::hasColumn('customers', 'reference_source_id')) {
    echo "Column reference_source_id EXISTS.\n";
} else {
    echo "Column reference_source_id MISSING.\n";
}
