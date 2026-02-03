<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$packages = \App\Models\Package::orderBy('category')->orderBy('package_name')->get();

echo "=== SERVICIOS IMPORTADOS ===\n\n";

$currentCategory = null;
foreach($packages as $p) {
    if ($currentCategory !== $p->category) {
        $currentCategory = $p->category;
        echo "\n--- {$currentCategory} ---\n";
    }
    echo "  {$p->package_name} - $" . number_format($p->package_price, 0, '', '.') . " ({$p->package_time} min)\n";
}

echo "\n\nTotal: " . $packages->count() . " servicios\n";
