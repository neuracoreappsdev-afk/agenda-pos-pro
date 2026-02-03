<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMissingSaleTablesForBi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Tabla de Pagos Detallados
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('sale_id')->unsigned();
                $table->integer('payment_method_id')->unsigned();
                $table->decimal('amount', 12, 2);
                $table->decimal('tip', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        // 2. Tabla de Logs de Inventario (Kardex)
        if (!Schema::hasTable('inventory_logs')) {
            Schema::create('inventory_logs', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->unsigned();
                $table->decimal('quantity', 12, 2);
                $table->string('type'); // in, out
                $table->string('reason');
                $table->timestamps();
            });
        }

        // 3. Ajustes en sale_items para BI
        Schema::table('sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_items', 'tax_amount')) {
                $table->decimal('tax_amount', 12, 2)->default(0)->after('unit_price');
            }
            if (!Schema::hasColumn('sale_items', 'commission_value')) {
                $table->decimal('commission_value', 12, 2)->default(0)->after('total');
            }
            if (!Schema::hasColumn('sale_items', 'discount_value')) {
                $table->decimal('discount_value', 12, 2)->default(0)->after('commission_value');
            }
        });

        // 4. Ajustes en cash_movements
        Schema::table('cash_movements', function (Blueprint $table) {
            if (!Schema::hasColumn('cash_movements', 'cash_register_session_id')) {
                $table->integer('cash_register_session_id')->unsigned()->nullable()->after('id');
            }
            if (!Schema::hasColumn('cash_movements', 'sale_id')) {
                $table->integer('sale_id')->unsigned()->nullable()->after('cash_register_session_id');
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
        Schema::dropIfExists('payments');
        Schema::dropIfExists('inventory_logs');
    }
}
