<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Creating core_apps table...\n";
    if (!Schema::hasTable('core_apps')) {
        Schema::create('core_apps', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('primary_color')->default('#6366f1');
            $table->string('secondary_color')->default('#a855f7');
            $table->string('font_family')->default('Inter, sans-serif');
            $table->string('logo')->nullable();
            $table->string('icon')->default('app-window');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        echo "Success!\n";
    } else {
        echo "Table core_apps already exists.\n";
    }

    echo "Adding app_id to businesses table...\n";
    Schema::table('businesses', function ($table) {
        if (!Schema::hasColumn('businesses', 'app_id')) {
            $table->integer('app_id')->unsigned()->nullable()->after('id');
            echo "Success!\n";
        } else {
            echo "Column app_id already exists.\n";
        }
    });

    // Seed "Agenda POS" if not exists
    echo "Seeding Agenda POS app...\n";
    $appId = DB::table('core_apps')->where('slug', 'agenda-pos')->value('id');
    if (!$appId) {
        $appId = DB::table('core_apps')->insertGetId([
            'name' => 'Agenda POS PRO',
            'slug' => 'agenda-pos',
            'description' => 'Sistema de Gestión y POS para Peluquerías',
            'primary_color' => '#6366f1',
            'secondary_color' => '#4f46e5',
            'font_family' => 'Outfit, sans-serif',
            'icon' => 'calendar',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        echo "Agenda POS seeded with ID: $appId\n";
    }

    // Update existing businesses to use this app
    echo "Updating existing businesses to app ID: $appId\n";
    DB::table('businesses')->whereNull('app_id')->update(['app_id' => $appId]);
    echo "Done.\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
