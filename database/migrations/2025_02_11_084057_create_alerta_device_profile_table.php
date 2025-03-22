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
        Schema::create('alerta_device_profile', function (Blueprint $table) {
            $table->integer('alerta_id');
            $table->uuid('device_profile_id');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('alerta_id')->references('id')->on('alertas')->onDelete('cascade');
            $table->foreign('device_profile_id')->references('id')->on('device_profile')->onDelete('cascade');

            // Clave primaria compuesta
            $table->primary(['alerta_id', 'device_profile_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerta_device_profile');
    }
};
