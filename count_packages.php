<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

foreach(\App\Models\Package::all() as $p) {
    echo "ID: {$p->id} | Name: {$p->package_name} | Price: {$p->package_price}\n";
}
