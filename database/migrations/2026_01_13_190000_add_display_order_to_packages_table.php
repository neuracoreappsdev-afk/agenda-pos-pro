<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisplayOrderToPackagesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('packages', 'display_order')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->integer('display_order')->default(0);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('packages', 'display_order')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->dropColumn('display_order');
            });
        }
    }
}
