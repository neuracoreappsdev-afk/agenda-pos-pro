<?php
require 'bootstrap/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$items = DB::table('sale_items')->get();
echo "Total items: " . count($items) . "\n";
foreach($items as $item) {
    echo "Type: [" . $item->item_type . "] Name: " . $item->item_name . "\n";
}
