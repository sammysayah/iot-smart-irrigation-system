\<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->float('moisture_level');
            $table->boolean('pump_status')->default(false);
            $table->float('water_used')->default(0);
            $table->string('reading_type')->default('automatic'); // automatic or manual
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensor_readings');
    }
};