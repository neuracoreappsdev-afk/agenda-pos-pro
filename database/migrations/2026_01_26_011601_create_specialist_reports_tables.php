<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialistReportsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Pivot table for specialists and services
        if (!Schema::hasTable('package_specialist')) {
            Schema::create('package_specialist', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('package_id')->unsigned();
                $table->integer('specialist_id')->unsigned();
                $table->timestamps();
                
                $table->index(['package_id', 'specialist_id']);
            });
        }

        // 2. Advances and Deductions table
        if (!Schema::hasTable('specialist_advances')) {
            Schema::create('specialist_advances', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('specialist_id')->unsigned();
                $table->decimal('amount', 12, 2);
                $table->string('type')->default('descuento'); // adelanto, descuento, etc
                $table->string('reason')->nullable();
                $table->string('status')->default('pending'); // pending, paid
                $table->datetime('date');
                $table->datetime('deducted_date')->nullable();
                $table->text('notes')->nullable();
                $table->integer('created_by')->unsigned()->nullable();
                $table->timestamps();
                
                $table->index(['specialist_id', 'date']);
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
        Schema::dropIfExists('package_specialist');
        Schema::dropIfExists('specialist_advances');
    }
}
