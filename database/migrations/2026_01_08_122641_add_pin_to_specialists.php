<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPinToSpecialists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('specialists', function (Blueprint $table) {
            if (!Schema::hasColumn('specialists', 'pin')) {
                $table->string('pin', 10)->nullable()->after('email');
            }
            if (!Schema::hasColumn('specialists', 'profile_color')) {
                $table->string('profile_color', 20)->nullable()->after('pin');
            }
            if (!Schema::hasColumn('specialists', 'services_json')) {
                $table->text('services_json')->nullable()->after('active');
            }
            if (!Schema::hasColumn('specialists', 'commissions')) {
                $table->text('commissions')->nullable()->after('services_json');
            }
            if (!Schema::hasColumn('specialists', 'time_blocks')) {
                $table->text('time_blocks')->nullable()->after('commissions');
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
        Schema::table('specialists', function (Blueprint $table) {
            $table->dropColumn(['pin', 'profile_color', 'services_json', 'commissions', 'time_blocks']);
        });
    }
}
