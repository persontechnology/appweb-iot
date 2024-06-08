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
        Schema::create('alertas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('nombre');
            $table->boolean('estado')->default(0);
            $table->boolean('puede_enviar_email')->default(0);

            $table->uuid('application_id');
            $table->foreign('application_id')->references('id')->on('application')->onDelete('cascade');
        
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas');
    }
};
