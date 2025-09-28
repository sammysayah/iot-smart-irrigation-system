<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
          Schema::create('water_logs', function (Blueprint $table) {
            $table->id();
            $table->float('water_used');
            $table->integer('duration_seconds'); // Correct column name
            $table->string('trigger_type'); // automatic, manual, schedule
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('water_logs');
    }
};