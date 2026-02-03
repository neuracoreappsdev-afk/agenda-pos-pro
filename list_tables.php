<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$tables = DB::select('SHOW TABLES');
foreach($tables as $table) {
    echo array_values((array)$table)[0] . "\n";
}
