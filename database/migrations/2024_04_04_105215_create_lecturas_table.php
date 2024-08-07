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
        Schema::create('lecturas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('estado')->default(0);
            $table->json('data')->nullable();
            $table->json('lat')->nullable();
            $table->json('long')->nullable();
            $table->string('dev_eui');
            $table->unsignedBigInteger('alerta_id');
            // $table->foreign('dev_eui')->references('dev_eui')->on('device')->onDelete('cascade');
            $table->foreign('alerta_id')->references('id')->on('alertas')->onDelete('cascade');
            $table->uuid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenant')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturas');
    }
};
