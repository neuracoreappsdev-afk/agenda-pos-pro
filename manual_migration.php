<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Database\Schema\Blueprint;

echo "Starting Manual Migration...\n";

try {
    // 1. Booking Locks
    if (!Schema::hasTable('booking_locks')) {
        Schema::create('booking_locks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('datetime'); 
            $table->integer('specialist_id')->unsigned()->nullable();
            $table->integer('package_id')->unsigned()->nullable();
            $table->string('session_token');
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->index(['datetime', 'specialist_id']);
        });
        DB::table('migrations')->insert(['migration' => '2026_01_09_224613_create_booking_locks_table', 'batch' => 999]);
        echo "Created booking_locks.\n";
    }

    // 2. AI Conversation Logs
    if (!Schema::hasTable('ai_conversation_logs')) {
        Schema::create('ai_conversation_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('phone')->nullable();
            $table->text('conversation_summary')->nullable();
            $table->string('last_action')->nullable(); 
            $table->string('status')->default('active');
            $table->text('raw_data')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });
        DB::table('migrations')->insert(['migration' => '2026_01_10_013427_create_ai_conversation_logs_table', 'batch' => 999]);
        echo "Created ai_conversation_logs.\n";
    }

    // 3. Cash Register Sessions
    if (!Schema::hasTable('cash_register_sessions')) {
        Schema::create('cash_register_sessions', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->decimal('opening_amount', 12, 2);
            $table->decimal('closing_amount', 12, 2)->nullable();
            $table->decimal('calculated_amount', 12, 2)->nullable();
            $table->decimal('difference', 12, 2)->nullable();
            $table->text('closing_notes')->nullable();
            $table->string('status')->default('open');
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
        DB::table('migrations')->insert(['migration' => '2026_01_10_020000_create_cash_register_sessions_table', 'batch' => 999]);
        echo "Created cash_register_sessions.\n";
    }

    // 4. Sales
    if (!Schema::hasTable('sales')) {
        Schema::create('sales', function(Blueprint $table) {
            $table->increments('id');
            $table->decimal('total', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->string('payment_method');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('customer_name')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('cash_register_session_id')->unsigned();
            $table->text('notes')->nullable();
            $table->text('items_json'); 
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('cash_register_session_id')->references('id')->on('cash_register_sessions')->onDelete('cascade');
        });
        DB::table('migrations')->insert(['migration' => '2026_01_10_030000_create_sales_table', 'batch' => 999]);
        echo "Created sales.\n";
    }

    echo "Manual Migration Completed Successfully.\n";

} catch (\Exception $e) {
    echo "Manual Migration Failed:\n";
    echo $e->getMessage();
}
