<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Testing login query...\n";
try {
    $username = 'admin';
    $admin = \App\Models\Admin::where('username', $username)
                              ->orWhere('email', $username)
                              ->first();
    echo "Query successful. Found: " . ($admin ? $admin->username : "None") . "\n";
} catch (\Exception $e) {
    echo "Query FAILED: " . $e->getMessage() . "\n";
}
