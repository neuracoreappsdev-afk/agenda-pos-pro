<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

$username = 'admin';
$password = '123456'; // Testing typical default

echo "Testing credentials for user: $username\n";
try {
    $admin = DB::table('admin')->where('username', $username)->first();
    if ($admin) {
        $admin = (object)$admin;
        if (Hash::check($password, $admin->password)) {
            echo "Password '$password' is CORRECT!\n";
        } else {
            echo "Password '$password' is INCORRECT.\n";
        }
    } else {
        echo "User not found.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
