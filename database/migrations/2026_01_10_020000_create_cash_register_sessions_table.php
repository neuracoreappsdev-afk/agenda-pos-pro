<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashRegisterSessionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_register_sessions', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned(); // ID del usuario que abre la caja
            $table->decimal('opening_amount', 12, 2); // Monto base inicial
            $table->decimal('closing_amount', 12, 2)->nullable(); // Monto reportado por el usuario al cierre
            $table->decimal('calculated_amount', 12, 2)->nullable(); // Monto calculado por el sistema (Ventas - Gastos + Base)
            $table->decimal('difference', 12, 2)->nullable(); // Diferencia (Sobrante o Faltante)
            $table->text('closing_notes')->nullable(); // Notas de cierre
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cash_register_sessions');
    }

}
