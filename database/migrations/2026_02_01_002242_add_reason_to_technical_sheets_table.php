<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonToTechnicalSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('technical_sheets', function (Blueprint $table) {
            $table->text('reason')->after('service_id')->nullable(); // Motivo de Consulta
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('technical_sheets', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
    }
}
