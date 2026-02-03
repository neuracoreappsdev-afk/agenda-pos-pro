<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketingTables extends Migration
{
    public function up()
    {
        // Campaigns Table
        if (!Schema::hasTable('marketing_campaigns')) {
            Schema::create('marketing_campaigns', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('type'); // email, sms, whatsapp
                $table->string('status')->default('draft'); // draft, scheduled, sent
                $table->string('subject')->nullable();
                $table->text('message')->nullable();
                $table->datetime('scheduled_at')->nullable();
                $table->integer('recipients_count')->default(0);
                $table->timestamps();
            });
        }

        // Messages Log Table
        if (!Schema::hasTable('marketing_messages')) {
            Schema::create('marketing_messages', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('campaign_id')->unsigned();
                $table->integer('customer_id')->unsigned()->nullable();
                $table->string('channel'); // email, sms, whatsapp
                $table->string('status'); // pending, sent, failed
                $table->string('recipient_contact')->nullable();
                $table->datetime('sent_at')->nullable();
                $table->timestamps();

                $table->foreign('campaign_id')->references('id')->on('marketing_campaigns')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('marketing_messages');
        Schema::dropIfExists('marketing_campaigns');
    }
}
