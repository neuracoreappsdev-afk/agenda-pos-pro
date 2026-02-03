<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

try {
    Schema::dropIfExists('sales');
    echo "Dropped sales.\n";
    Schema::dropIfExists('cash_register_sessions');
    echo "Dropped cash_register_sessions.\n";
    Schema::dropIfExists('booking_locks');
    echo "Dropped booking_locks.\n";
    Schema::dropIfExists('ai_conversation_logs');
    echo "Dropped ai_conversation_logs.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
