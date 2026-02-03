<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Hypothesis test: querying a definitely non-existent column...\n";
try {
    $res = DB::select('select * from "admin" where "definitely_not_here" = "admin" limit 1');
    echo "Query with non-existent column passed!\n";
} catch (\Exception $e) {
    echo "Query with non-existent column failed: " . $e->getMessage() . "\n";
}
try {
    $res = DB::select('select definitely_not_here from "admin" limit 1');
    echo "Select non-existent column passed!\n";
} catch (\Exception $e) {
    echo "Select non-existent column failed: " . $e->getMessage() . "\n";
}
