<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldsToCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('identification')->nullable()->after('last_name');
            $table->string('phone_landline')->nullable()->after('contact_number');
            $table->string('address')->nullable()->after('email');
            $table->string('city')->nullable()->after('address');
            $table->date('birthday')->nullable()->after('city');
            $table->string('gender')->nullable()->after('birthday');
            $table->string('type')->default('Persona')->after('gender');
            $table->text('notes')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['identification', 'phone_landline', 'address', 'city', 'birthday', 'gender', 'type', 'notes']);
        });
    }
}
