<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashMovementsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('cash_movements')) {
            Schema::create('cash_movements', function(Blueprint $table)
            {
                $table->increments('id');
                $table->integer('cash_register_session_id')->unsigned()->nullable();
                $table->integer('sale_id')->unsigned()->nullable();
                $table->string('type'); // income, expense, withdrawal, deposit
                $table->decimal('amount', 12, 2);
                $table->string('concept')->nullable();
                $table->string('payment_method')->nullable();
                $table->string('reference')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('movement_date');
                $table->timestamps();
            });
        } else {
            // Si la tabla ya existe, agregar las columnas faltantes
            if (!Schema::hasColumn('cash_movements', 'payment_method')) {
                Schema::table('cash_movements', function(Blueprint $table) {
                    $table->string('payment_method')->nullable();
                });
            }
            if (!Schema::hasColumn('cash_movements', 'reference')) {
                Schema::table('cash_movements', function(Blueprint $table) {
                    $table->string('reference')->nullable();
                });
            }
            if (!Schema::hasColumn('cash_movements', 'notes')) {
                Schema::table('cash_movements', function(Blueprint $table) {
                    $table->text('notes')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_movements');
    }

}
