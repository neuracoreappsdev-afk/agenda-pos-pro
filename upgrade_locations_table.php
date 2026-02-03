<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Updating 'locations' table...\n";
if (!Schema::hasTable('locations')) {
    Schema::create('locations', function($table) {
        $table->increments('id');
        $table->string('name');
        $table->string('address');
        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->string('city')->nullable();
        $table->string('state')->nullable();
        $table->string('zip')->nullable();
        $table->boolean('active')->default(true);
        $table->boolean('is_principal')->default(false);
        $table->timestamps();
    });
} else {
    Schema::table('locations', function($table) {
        if (!Schema::hasColumn('locations', 'phone')) $table->string('phone')->nullable();
        if (!Schema::hasColumn('locations', 'email')) $table->string('email')->nullable();
        if (!Schema::hasColumn('locations', 'city')) $table->string('city')->nullable();
        if (!Schema::hasColumn('locations', 'state')) $table->string('state')->nullable();
        if (!Schema::hasColumn('locations', 'zip')) $table->string('zip')->nullable();
        if (!Schema::hasColumn('locations', 'is_principal')) $table->boolean('is_principal')->default(false);
        if (!Schema::hasColumn('locations', 'active')) $table->boolean('active')->default(true);
    });
}

// Migrate data from Settings to locations table if locations table is empty
$count = DB::table('locations')->count();
if ($count == 0) {
    $sedes = \App\Models\Setting::get('sedes', []);
    if (is_string($sedes)) $sedes = json_decode($sedes, true) ?? [];
    
    foreach ($sedes as $sede) {
        DB::table('locations')->insert([
            'name' => $sede['nombre'] ?? 'Sede',
            'address' => $sede['direccion'] ?? '',
            'phone' => $sede['telefono'] ?? '',
            'email' => $sede['email'] ?? '',
            'city' => $sede['ciudad'] ?? '',
            'state' => $sede['departamento'] ?? '',
            'zip' => $sede['codigo_postal'] ?? '',
            'active' => isset($sede['activo']) ? $sede['activo'] : true,
            'is_principal' => isset($sede['principal']) ? $sede['principal'] : false,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
    echo "Migrated " . count($sedes) . " sedes from settings/json to locations table.\n";
}

echo "Done.\n";
