<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

try {
    $cols = DB::select('SHOW COLUMNS FROM sale_items');
    foreach($cols as $c) {
        echo $c->Field . "\n";
    }
} catch (\Exception $e) {
    echo "Table sale_items does not exist: " . $e->getMessage() . "\n";
}
