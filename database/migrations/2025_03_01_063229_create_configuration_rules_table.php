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
        Schema::create('configuration_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('configuration_id'); // Debe coincidir con el tipo de ID de 'configurations'
            $table->string('sensor'); // 'distancia', 'gps', 'button'
            $table->string('condition_type')->nullable(); // 'rango', 'evento', 'estado'
            $table->string('color')->nullable(); // 'rango', 'evento', 'estado'
            $table->string('description')->nullable(); // 'rango', 'evento', 'estado'
            $table->integer('min_value')->nullable();
            $table->integer('max_value')->nullable();
            $table->string('event')->nullable(); // Para 'evento' o 'estado'
            $table->boolean('alert')->default(false);
            $table->timestamps();

            $table->foreign('configuration_id')->references('id')->on('configurations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuration_rules');
    }
};
