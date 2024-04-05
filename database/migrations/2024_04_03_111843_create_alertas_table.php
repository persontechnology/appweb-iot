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
          

            $table->uuid('application_id');
            $table->foreign('application_id')->references('id')->on('application')->onDelete('cascade');

            $table->uuid('device_profile_id');
            $table->foreign('device_profile_id')->references('id')->on('device_profile')->onDelete('cascade');

            
            $table->unique(['application_id', 'device_profile_id']);



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
