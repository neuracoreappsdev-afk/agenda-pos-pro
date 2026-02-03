<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAiFlagToSaasPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saas_plans', function (Blueprint $table) {
            $table->boolean('has_ai')->default(false)->after('whatsapp_integration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saas_plans', function (Blueprint $table) {
            $table->dropColumn('has_ai');
        });
    }
}
