<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportingFieldsFinal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add location to specialists
        Schema::table('specialists', function (Blueprint $table) {
            if (!Schema::hasColumn('specialists', 'location')) {
                $table->string('location')->nullable()->default('Sede Principal');
            }
        });

        // Add columns to sales
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'location_id')) {
                $table->integer('location_id')->unsigned()->nullable();
            }
            if (!Schema::hasColumn('sales', 'invoice_number')) {
                $table->string('invoice_number')->nullable();
            }
            if (!Schema::hasColumn('sales', 'status')) {
                $table->string('status')->nullable()->default('Completada');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('specialists', function (Blueprint $table) {
            $table->dropColumn('location');
        });
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['location_id', 'invoice_number', 'status']);
        });
    }
}
