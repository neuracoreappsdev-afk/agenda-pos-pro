<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$cols = DB::select('DESCRIBE sales');
foreach($cols as $c) {
    echo "Field: {$c->Field} | Null: {$c->Null} | Key: {$c->Key}\n";
}
