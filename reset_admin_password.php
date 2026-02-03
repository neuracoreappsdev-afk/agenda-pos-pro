<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Updating admin password to '123456'...\n";
try {
    $updated = DB::table('admin')->where('username', 'admin')->update(['password' => Hash::make('123456')]);
    if ($updated) {
        echo "Password updated successfully.\n";
    } else {
        echo "Password was already set or user 'admin' not found.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
