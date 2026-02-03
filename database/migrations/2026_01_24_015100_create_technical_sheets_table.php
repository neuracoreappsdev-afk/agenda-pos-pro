<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnicalSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technical_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('specialist_id')->unsigned()->nullable();
            $table->integer('service_id')->unsigned()->nullable(); // appointment_id or package_id
            $table->text('notes')->nullable(); // Detailed technical notes
            $table->text('formula')->nullable(); // For hair color formulas etc
            $table->text('products_used')->nullable();
            $table->string('attachments')->nullable(); // JSON path to images
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('technical_sheets');
    }
}
