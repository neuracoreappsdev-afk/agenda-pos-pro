<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Columns in 'specialists' table:\n";
try {
    $res = DB::select("PRAGMA table_info(specialists)");
    foreach ($res as $col) {
        echo "- " . $col->name . " (" . $col->type . ")\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
