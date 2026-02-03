<?php
require 'bootstrap/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Actualizar item_type basándose en si existe en products o packages
$items = DB::table('sale_items')->get();
$updated = 0;

foreach ($items as $item) {
    // Revisar si el item_id existe en productos
    $isProduct = DB::table('products')->where('id', $item->item_id)->exists();
    $isService = DB::table('packages')->where('id', $item->item_id)->exists();
    
    $type = '';
    if ($isProduct) {
        $type = 'producto';
    } elseif ($isService) {
        $type = 'servicio';
    }
    
    if ($type && $item->item_type != $type) {
        DB::table('sale_items')->where('id', $item->id)->update(['item_type' => $type]);
        echo "Updated item {$item->id} ({$item->item_name}) to type: {$type}\n";
        $updated++;
    }
}

echo "\nTotal updated: {$updated}\n";
