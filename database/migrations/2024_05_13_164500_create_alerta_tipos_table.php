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
        Schema::create('alerta_tipos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('titulo')->nullable();
            $table->string('parametro')->nullable();
            $table->string('condicion')->nullable();
            $table->string('valor')->nullable();
            $table->string('mensaje')->nullable();
            $table->foreignId('alerta_id')->constrained(
                table: 'alertas'
            )->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerta_tipos');
    }
};
