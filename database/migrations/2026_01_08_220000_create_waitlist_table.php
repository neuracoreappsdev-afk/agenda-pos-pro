<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaitlistTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('waitlist')) {
            Schema::create('waitlist', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('customer_id')->unsigned();
                $table->integer('package_id')->unsigned()->nullable(); // Servicio deseado
                $table->integer('specialist_id')->unsigned()->nullable(); // Preferencia (opcional)
                $table->date('date_from'); // Desde cuándo necesita disponibilidad
                $table->date('date_to'); // Hasta cuándo necesita disponibilidad
                $table->string('time_preference')->default('any'); // morning, afternoon, any
                $table->integer('priority')->default(0); // Orden en la cola (FIFO)
                $table->string('status')->default('waiting'); // waiting, notified, accepted, expired, passed
                $table->text('notes')->nullable();
                $table->timestamp('notified_at')->nullable();
                $table->timestamp('responded_at')->nullable();
                $table->timestamps();

            // Foreign keys
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
            $table->foreign('specialist_id')->references('id')->on('specialists')->onDelete('set null');

                // Index for quick lookups
                $table->index(['status', 'date_from', 'date_to']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('waitlist');
    }
}
