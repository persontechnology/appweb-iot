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
        Schema::create('alerta_tipo_dispositivos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('alerta_id')->constrained('alertas')->onDelete('cascade');
            $table->foreignId('tipo_dispositivo_id')->constrained('tipo_dispositivos')->onDelete('cascade');

        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerta_tipo_dispositivos');
    }
};
