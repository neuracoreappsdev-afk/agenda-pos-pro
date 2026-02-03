<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Upgrading products and packages for commissions...\n";

Schema::table('products', function($table) {
    if (!Schema::hasColumn('products', 'commission_percentage')) {
        $table->decimal('commission_percentage', 10, 2)->default(0);
    }
    if (!Schema::hasColumn('products', 'commission_type')) {
        $table->string('commission_type', 20)->default('porcentaje');
    }
});

Schema::table('packages', function($table) {
    if (!Schema::hasColumn('packages', 'commission_type')) {
        $table->string('commission_type', 20)->default('porcentaje');
    }
});

echo "Done.\n";
