<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_recipes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id')->unsigned(); // El servicio (Package)
            $table->integer('product_id')->unsigned(); // El producto consumible
            $table->decimal('quantity', 10, 2); // Cantidad consumida (ej: 0.5, 30)
            $table->string('unit')->default('unidad'); // ml, gr, unidad, etc
            $table->timestamps();

            // Foreign keys if possible, but keeping it loose for now to avoid migration hell with SQLite/MySQL diffs
            // $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('service_recipes');
    }
}
