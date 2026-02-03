<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoreAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_apps', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            
            // Branding
            $table->string('primary_color')->default('#6366f1');
            $table->string('secondary_color')->default('#a855f7');
            $table->string('font_family')->default('Inter, sans-serif');
            $table->string('logo')->nullable();
            $table->string('icon')->default('app-window'); // Lucide icon name
            
            $table->boolean('is_active')->default(true);
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
        Schema::drop('core_apps');
    }
}
