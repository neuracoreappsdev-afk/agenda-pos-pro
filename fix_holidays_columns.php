<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Updating 'holidays' table...\n";
if (Schema::hasTable('holidays')) {
    Schema::table('holidays', function($table) {
        if (!Schema::hasColumn('holidays', 'country_code')) {
            $table->string('country_code', 10)->nullable();
        }
    });
    echo "Added 'country_code' to 'holidays'.\n";
}
echo "Done.\n";
