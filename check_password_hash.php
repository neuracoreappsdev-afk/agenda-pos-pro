<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Admin password hash:\n";
try {
    $admin = DB::table('admin')->where('username', 'admin')->first();
    if ($admin) {
        $admin = (object)$admin;
        echo "Hash: " . $admin->password . "\n";
        echo "Is Bcrypt? " . (password_get_info($admin->password)['algoName'] !== 'unknown' ? 'YES' : 'NO') . "\n";
    } else {
        echo "Admin not found.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
