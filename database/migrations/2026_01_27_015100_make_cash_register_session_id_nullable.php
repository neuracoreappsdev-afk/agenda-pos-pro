<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCashRegisterSessionIdNullable extends Migration {

    public function up()
    {
        // Para SQLite: recrear la tabla con la estructura correcta
        // Primero respaldar datos existentes
        $existingData = DB::table('cash_movements')->get();
        
        // Eliminar la tabla
        Schema::dropIfExists('cash_movements');
        
        // Crear la tabla con la estructura correcta
        Schema::create('cash_movements', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('cash_register_session_id')->unsigned()->nullable(); // NULLABLE ahora
            $table->integer('sale_id')->unsigned()->nullable();
            $table->string('type');
            $table->decimal('amount', 12, 2);
            $table->string('concept')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('movement_date')->nullable();
            $table->timestamps();
        });
        
        // Restaurar datos si existían
        foreach ($existingData as $row) {
            DB::table('cash_movements')->insert((array)$row);
        }
    }

    public function down()
    {
        // Nothing to reverse
    }

}
