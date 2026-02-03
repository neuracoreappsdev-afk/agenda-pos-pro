<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$columns = DB::select("DESCRIBE holidays");
foreach ($columns as $column) {
    echo "COL: " . $column->Field . " TYPE: " . $column->Type . "\n";
}
