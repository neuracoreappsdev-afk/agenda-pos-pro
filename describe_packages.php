<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$columns = DB::select('SHOW COLUMNS FROM packages');
$output = "";
foreach($columns as $col) {
    $output .= "Field: {$col->Field} | Type: {$col->Type} | Null: {$col->Null} | Key: {$col->Key} | Default: " . ($col->Default ?? 'NULL') . " | Extra: {$col->Extra}\n";
}
file_put_contents('packages_structure.txt', $output);
echo "Structure saved to packages_structure.txt\n";
