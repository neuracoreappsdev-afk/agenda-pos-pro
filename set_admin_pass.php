<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

$username = 'admin';
$newPassword = 'admin123';

echo "Resetting password for $username to $newPassword...\n";
try {
    $updated = DB::table('admin')->where('username', $username)->update(['password' => Hash::make($newPassword)]);
    if ($updated) {
        echo "Password updated successfully.\n";
    } else {
        echo "User '$username' not found or update failed.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
