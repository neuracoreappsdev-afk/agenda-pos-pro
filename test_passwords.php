<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

$username = 'admin';
$passwords = ['admin', '12345', 'password', 'lina', 'imperial'];

echo "Testing several common passwords for user: $username\n";
try {
    $admin = DB::table('admin')->where('username', $username)->first();
    if ($admin) {
        $admin = (object)$admin;
        foreach ($passwords as $password) {
            if (Hash::check($password, $admin->password)) {
                echo "Password '$password' is CORRECT!\n";
                exit;
            }
        }
        echo "None of the tested passwords were correct.\n";
    } else {
        echo "User not found.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
