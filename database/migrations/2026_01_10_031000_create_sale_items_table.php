<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleItemsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sale_items')) {
            Schema::create('sale_items', function(Blueprint $table)
            {
                $table->increments('id');
                $table->integer('sale_id')->unsigned();
                $table->string('item_type'); // servicio, producto
                $table->integer('item_id')->unsigned();
                $table->string('item_name');
                $table->integer('quantity');
                $table->decimal('unit_price', 12, 2);
                $table->decimal('discount', 12, 2)->default(0);
                $table->decimal('total', 12, 2);
                $table->integer('specialist_id')->unsigned()->nullable();
                $table->decimal('commission_rate', 12, 2)->nullable();
                $table->decimal('commission_value', 12, 2)->nullable();
                $table->timestamps();
                
                $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
                $table->foreign('specialist_id')->references('id')->on('specialists');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_items');
    }

}
