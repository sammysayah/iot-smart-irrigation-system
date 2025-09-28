<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('manual_requests', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->boolean('processed')->default(false);
            $table->integer('duration')->default(10);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('manual_requests');
    }
};