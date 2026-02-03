<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingColumnsToSalesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function(Blueprint $table)
        {
            // Agregar sale_date si no existe
            if (!Schema::hasColumn('sales', 'sale_date')) {
                $table->timestamp('sale_date')->nullable()->after('id');
            }
            
            // Agregar specialist_id si no existe
            if (!Schema::hasColumn('sales', 'specialist_id')) {
                $table->integer('specialist_id')->unsigned()->nullable()->after('user_id');
                $table->foreign('specialist_id')->references('id')->on('specialists');
            }
        });
        
        // Actualizar sale_date con created_at para registros existentes
        DB::statement('UPDATE sales SET sale_date = created_at WHERE sale_date IS NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function(Blueprint $table)
        {
            $table->dropForeign(['specialist_id']);
            $table->dropColumn(['sale_date', 'specialist_id']);
        });
    }

}
