<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');

try {
    $kernel->bootstrap();
    
    // Ejecutar la migración manualmente
    $migration = new CreateAiConversationLogsTable();
    
    if (!Schema::hasTable('ai_conversation_logs')) {
        echo "Running migration...\n";
        $migration->up();
        echo "Migration completed successfully!\n";
        
        // Registrar en la tabla de migraciones
        DB::table('migrations')->insert([
            'migration' => '2026_01_10_013427_create_ai_conversation_logs_table',
            'batch' => DB::table('migrations')->max('batch') + 1
        ]);
        echo "Registered in migrations table.\n";
    } else {
        echo "Table already exists.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
