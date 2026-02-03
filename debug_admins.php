<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "All Admin Users:\n";
try {
    $admins = DB::table('admin')->get();
    foreach ($admins as $a) {
        print_r($a);
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
