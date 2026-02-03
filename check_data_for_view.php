<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- LOCATIONS ---\n";
$locations = DB::table('locations')->get();
foreach ($locations as $loc) {
    echo "ID: {$loc->id}, Name: {$loc->name}\n";
}

echo "\n--- CATEGORIES (from existing packages) ---\n";
$categories = DB::table('packages')->select('category')->distinct()->get();
foreach ($categories as $cat) {
    echo "Category: {$cat->category}\n";
}
