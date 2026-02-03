<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Listing all users in the 'users' table:\n";
try {
    $users = \DB::table('users')->get();
    if ($users->isEmpty()) {
        echo "No users found.\n";
    } else {
        foreach ($users as $user) {
            echo "ID: " . ($user->id ?? 'N/A') . 
                 " | Username: " . ($user->username ?? 'N/A') . 
                 " | Email: " . ($user->email ?? 'N/A') . 
                 " | Name: " . ($user->name ?? 'N/A') . "\n";
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
