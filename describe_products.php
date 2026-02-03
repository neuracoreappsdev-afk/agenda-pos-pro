<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$columns = DB::select('SHOW COLUMNS FROM products');
$output = '';
foreach($columns as $col) {
    $output .= "{$col->Field}: {$col->Type}\n";
}
file_put_contents('products_structure.txt', $output);
echo "Done. See products_structure.txt\n";
