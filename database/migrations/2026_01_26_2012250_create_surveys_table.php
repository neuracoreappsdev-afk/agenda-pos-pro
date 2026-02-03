<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('questions_json')->nullable(); // JSON array of questions
            $table->boolean('active')->default(true);
            $table->string('trigger_event')->default('appointment_finished'); // event that triggers sending the survey
            $table->integer('delay_minutes')->default(60); // time after event to send
            $table->timestamps();
        });

        Schema::create('survey_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('survey_id')->unsigned();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('appointment_id')->unsigned()->nullable();
            $table->text('answers_json')->nullable();
            $table->integer('rating')->nullable(); // overall rating 1-5
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('survey_responses');
        Schema::drop('surveys');
    }
}
