<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingColsToSaasPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saas_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('saas_plans', 'features_json')) {
                $table->text('features_json')->nullable()->after('description');
            }
            if (!Schema::hasColumn('saas_plans', 'currency')) {
                $table->string('currency')->default('COP')->after('price');
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
        Schema::table('saas_plans', function (Blueprint $table) {
            if (Schema::hasColumn('saas_plans', 'features_json')) {
                $table->dropColumn('features_json');
            }
            if (Schema::hasColumn('saas_plans', 'currency')) {
                $table->dropColumn('currency');
            }
        });
    }
}
