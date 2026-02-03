<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

if (Schema::hasTable('bonos')) {
    echo "Table 'bonos' EXISTS\n";
} else {
    echo "Table 'bonos' DOES NOT EXIST\n";
}

$migrations = DB::table('migrations')->where('migration', '2026_01_09_194001_create_bonos_table')->get();
if ($migrations) {
    echo "Migration entry FOUND in 'migrations' table\n";
    print_r($migrations);
} else {
    echo "Migration entry NOT FOUND in 'migrations' table\n";
}
