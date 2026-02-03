<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAiConversationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_conversation_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('phone')->nullable();
            $table->text('conversation_summary')->nullable();
            $table->string('last_action')->nullable(); // confirmed, cancelled, booked, etc
            $table->string('status')->default('active'); // active, finished
            $table->text('raw_data')->nullable(); // Para guardar el contexto completo si es necesario
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ai_conversation_logs');
    }
}
