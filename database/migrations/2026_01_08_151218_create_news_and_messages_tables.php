<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateNewsAndMessagesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Table for Updates/News
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('content');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Table for Chat Messages between Admin and Specialist
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('specialist_id')->unsigned();
            $table->enum('sender_type', ['admin', 'specialist']);
            $table->text('message')->nullable();
            $table->enum('message_type', ['text', 'image', 'audio'])->default('text');
            $table->string('file_path')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('specialist_id')->references('id')->on('specialists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('news');
    }
}
