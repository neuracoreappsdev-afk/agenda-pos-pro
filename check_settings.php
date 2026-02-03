<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "n8n_webhook_url: " . (\App\Models\Setting::get('n8n_webhook_url') ?: 'NOT SET') . "\n";
echo "business_name: " . (\App\Models\Setting::get('business_name') ?: 'NOT SET') . "\n";
