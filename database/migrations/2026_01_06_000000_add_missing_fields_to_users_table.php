<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->boolean('active')->default(true)->after('password');
            $table->text('roles')->nullable()->after('active'); // Storing roles as JSON/CSV
            $table->string('profile_photo')->nullable()->after('roles');
            $table->integer('sede_id')->nullable()->after('profile_photo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'active', 'roles', 'profile_photo', 'sede_id']);
        });
    }
}
