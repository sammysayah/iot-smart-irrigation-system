<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('irrigation_settings', function (Blueprint $table) {
            $table->id();
            $table->float('moisture_threshold')->default(30.0);
            $table->integer('pump_duration')->default(10); // seconds
            $table->boolean('auto_mode')->default(true);
            $table->boolean('system_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('irrigation_settings');
    }
};