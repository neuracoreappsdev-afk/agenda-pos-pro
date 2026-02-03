<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryToPackagesTable extends Migration
{
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('category')->nullable()->after('package_description'); // manicurista, estilista, etc.
            $table->decimal('commission_percentage', 5, 2)->nullable()->after('category'); // % para el especialista
        });
    }

    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['category', 'commission_percentage']);
        });
    }
}
