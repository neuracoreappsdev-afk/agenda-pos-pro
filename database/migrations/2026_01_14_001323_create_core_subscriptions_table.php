<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoreSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->decimal('amount', 12, 2);
            $table->string('period'); // Format YYYY-MM
            $table->date('payment_date');
            $table->string('status')->default('paid'); // paid, pending, failed
            $table->string('payment_method')->nullable();
            $table->string('invoice_number')->nullable();
            $table->timestamps();

            // Relación lógica (evitamos foreign keys estrictas si la tabla business es volátil)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('core_subscriptions');
    }
}
