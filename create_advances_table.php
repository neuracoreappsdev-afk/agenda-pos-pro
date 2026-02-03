<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Create specialist_advances table for vales/adelantos
Schema::dropIfExists('specialist_advances');

Schema::create('specialist_advances', function($table) {
    $table->increments('id');
    $table->integer('specialist_id')->unsigned();
    $table->decimal('amount', 12, 2);
    $table->string('type')->default('vale'); // vale, prestamo, descuento
    $table->string('reason')->nullable();
    $table->string('status')->default('pending'); // pending, deducted, cancelled
    $table->date('date');
    $table->date('deducted_date')->nullable();
    $table->text('notes')->nullable();
    $table->integer('created_by')->unsigned()->nullable();
    $table->timestamps();
});

echo "Table 'specialist_advances' created successfully!\n";

// Verify
$cols = DB::select('SHOW COLUMNS FROM specialist_advances');
foreach($cols as $c) {
    echo "- " . $c->Field . "\n";
}
