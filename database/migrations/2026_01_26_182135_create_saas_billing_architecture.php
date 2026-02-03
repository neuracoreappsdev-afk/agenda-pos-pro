<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasBillingArchitecture extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Planes SaaS (Lo que vendes)
        Schema::create('saas_plans', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name'); // Basic, Premium, Enterprise
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Precio mensual
             $table->integer('billing_cycle_days')->default(30);
            
            // Limitantes del plan
            $table->integer('max_users')->default(1);
            $table->integer('max_branches')->default(1);
            $table->boolean('whatsapp_integration')->default(false);
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Suscripciones (Relación Negocio - Plan)
        Schema::create('saas_subscriptions', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->integer('plan_id')->unsigned();
            
            $table->string('status')->default('active'); // active, past_due, cancelled, trial
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->dateTime('trial_ends_at')->nullable();
            
            // Logica de recordatorios
            $table->dateTime('last_reminder_sent_at')->nullable();
            $table->integer('reminder_count')->default(0); // 1 (3 dias), 2 (2 dias), 3 (1 dia)

            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('saas_plans');
        });

        // 3. Historial de Pagos (Auditoría financiera)
        Schema::create('saas_payments', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->integer('subscription_id')->unsigned()->nullable();
            
            $table->string('reference_code'); // Referencia única Wompi/Banco
            $table->string('transaction_id')->nullable(); // ID retornado por la pasarela
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('COP');
            $table->string('payment_method')->nullable(); // Card, PSE, Nequi
            $table->string('status'); // PENDING, APPROVED, DECLINED, VOIDED
            $table->dateTime('paid_at')->nullable();
            
            $table->timestamps();
            
            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('subscription_id')->references('id')->on('saas_subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('saas_payments');
        Schema::drop('saas_subscriptions');
        Schema::drop('saas_plans');
    }
}
