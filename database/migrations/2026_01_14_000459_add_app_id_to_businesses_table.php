<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppIdToBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('businesses', function(Blueprint $table)
        {
            $table->integer('app_id')->unsigned()->nullable()->after('id');
            
            // Relación lógica (puedes añadir foreign key si core_apps ya existe)
            // $table->foreign('app_id')->references('id')->on('core_apps')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('businesses', function(Blueprint $table)
        {
            $table->dropColumn('app_id');
        });
    }
}
