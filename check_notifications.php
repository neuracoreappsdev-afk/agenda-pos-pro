<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "--- Recent Notifications ---\n";
$notifs = \App\Models\Notification::orderBy('id', 'desc')->limit(5)->get();
foreach ($notifs as $n) {
    echo "ID: {$n->id}, Title: {$n->title}, Message: {$n->message}, Created: {$n->created_at}\n";
}
