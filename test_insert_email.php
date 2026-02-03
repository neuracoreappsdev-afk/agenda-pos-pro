<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Attempting to insert an admin with email...\n";
try {
    DB::table('admin')->insert([
        'username' => 'test_email',
        'email' => 'test@example.com',
        'password' => 'secret'
    ]);
    echo "Insert SUCCESSFUL.\n";
} catch (\Exception $e) {
    echo "Insert FAILED: " . $e->getMessage() . "\n";
}
