<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Table: packages\n";
$columns = DB::select("PRAGMA table_info(packages)");
foreach ($columns as $col) {
    echo "{$col->name} ({$col->type})\n";
}
echo "\nTable: products\n";
$columns = DB::select("PRAGMA table_info(products)");
foreach ($columns as $col) {
    echo "{$col->name} ({$col->type})\n";
}
