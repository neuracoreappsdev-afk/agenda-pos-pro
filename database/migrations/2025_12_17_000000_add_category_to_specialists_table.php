<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryToSpecialistsTable extends Migration
{
    public function up()
    {
        Schema::table('specialists', function (Blueprint $table) {
            $table->string('category')->nullable()->after('title'); // manicurista, estilista, etc.
            $table->boolean('active')->default(true)->after('category');
        });
    }

    public function down()
    {
        Schema::table('specialists', function (Blueprint $table) {
            $table->dropColumn(['category', 'active']);
        });
    }
}
