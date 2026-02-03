<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function(Blueprint $table)
        {
            $table->increments('id');
            $table->decimal('total', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->string('payment_method'); // Efectivo, Transferencia, Tarjeta
            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('customer_name')->nullable(); // Para consumidor final o históricos
            $table->integer('user_id')->unsigned(); // Vendedor
            $table->integer('cash_register_session_id')->unsigned(); // Sesión de caja
            $table->text('notes')->nullable();
            
            // JSON column for items details (snapshot of prices/names at time of sale)
            // Storing as TEXT for compatibility if JSON type not strictly supported by DB constraints or version
            $table->text('items_json'); 

            $table->timestamps();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('cash_register_session_id')->references('id')->on('cash_register_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sales');
    }

}
