<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingLocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_locks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('datetime'); // Formato Y-m-d H:i
            $table->integer('specialist_id')->unsigned()->nullable();
            $table->integer('package_id')->unsigned()->nullable();
            $table->string('session_token');
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index(['datetime', 'specialist_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('booking_locks');
    }
}
