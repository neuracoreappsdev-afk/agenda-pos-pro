<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingColumnsToCashMovementsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_movements', function(Blueprint $table)
        {
            if (!Schema::hasColumn('cash_movements', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('concept');
            }
            if (!Schema::hasColumn('cash_movements', 'reference')) {
                $table->string('reference')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('cash_movements', 'notes')) {
                $table->text('notes')->nullable()->after('reference');
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
        Schema::table('cash_movements', function(Blueprint $table)
        {
            $table->dropColumn(['payment_method', 'reference', 'notes']);
        });
    }

}
