<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsentFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consent_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->string('title'); // e.g. "Consentimiento Depilación Láser"
            $table->text('content'); // El texto legal aceptado
            $table->text('signature_data'); // Base64 de la firma
            $table->string('ip_address')->nullable();
            $table->string('status')->default('signed'); 
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
        Schema::drop('consent_forms');
    }
}
