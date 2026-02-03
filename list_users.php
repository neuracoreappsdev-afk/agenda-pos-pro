<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Users in 'users' table:\n";
try {
    $users = DB::table('users')->get();
    foreach ($users as $u) {
        $u = (object)$u;
        echo "ID: " . ($u->id ?? 'N/A') . " | Username: " . ($u->username ?? 'N/A') . " | Email: " . ($u->email ?? 'N/A') . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
