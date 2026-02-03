<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Listing all entries in the 'admin' table:\n";
try {
    if (!Schema::hasTable('admin')) {
        echo "Table 'admin' does NOT exist.\n";
    } else {
        $admins = DB::table('admin')->get();
        if (empty($admins)) {
            echo "No admins found.\n";
        } else {
            foreach ($admins as $a) {
                // Handle both object and array return types
                $a = (object)$a;
                echo "ID: " . ($a->id ?? 'N/A') . 
                     " | Username: " . ($a->username ?? 'N/A') . 
                     " | Email: " . ($a->email ?? 'N/A') . "\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
