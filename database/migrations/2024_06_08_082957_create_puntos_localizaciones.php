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
        Schema::create('puntos_localizaciones', function (Blueprint $table) {
            $table->id();
            $table->boolean('estado')->default(0);
            $table->enum('tipo',['LOCALIZACION','ERROR'])->default('LOCALIZACION');
            $table->text('dato')->nullable();
            $table->text('error')->nullable();
            $table->json('data')->nullable();
            $table->decimal('latitud',10,8)->nullable();
            $table->decimal('longitud',10,8)->nullable();
            $table->integer('exactitud')->nullable();  
            //$table->binary('dev_eui'); 
            $table->string('dev_eui');         
            //$table->foreign('dev_eui')->references('dev_eui')->on('device')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntos_localizaciones');
    }
};
