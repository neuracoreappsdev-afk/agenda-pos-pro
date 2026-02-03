<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->integer('customer_id')->unsigned()->nullable(); // Comprador
            $table->string('buyer_name')->nullable();
            $table->string('recipient_name');
            $table->string('recipient_email')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->text('message')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('balance', 15, 2);
            $table->enum('status', ['pending', 'paid', 'partially_used', 'used', 'expired'])->default('pending');
            $table->date('expiry_date')->nullable();
            $table->string('payment_id')->nullable(); // ID de transacción
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bonos');
    }
}
