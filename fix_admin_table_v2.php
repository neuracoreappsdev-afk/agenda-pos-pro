<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    if (!Schema::hasColumn('admin', 'is_approved')) {
        Schema::table('admin', function($table) {
            $table->boolean('is_approved')->default(0)->after('password');
        });
        echo "Column is_approved added.\n";
    } else {
        echo "Column is_approved already exists.\n";
    }
    
    if (!Schema::hasColumn('admin', 'email')) {
        Schema::table('admin', function($table) {
            $table->string('email')->nullable()->after('username');
        });
        echo "Column email added.\n";
    } else {
        echo "Column email already exists.\n";
    }
    
    // Auto-approve the current admin (likely ID 1)
    DB::table('admin')->where('id', 1)->update(['is_approved' => 1]);
    echo "Admin #1 approved.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
