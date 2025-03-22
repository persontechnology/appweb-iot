<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->uuid('device_profile_id');
            $table->uuid('application_id');
            $table->timestamps();

            $table->foreign('device_profile_id')->references('id')->on('device_profile')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('application')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
