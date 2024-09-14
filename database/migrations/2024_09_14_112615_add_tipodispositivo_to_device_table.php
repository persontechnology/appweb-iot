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
        Schema::table('device', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_dispositivo_id')->nullable();
            $table->foreign('tipo_dispositivo_id')->references('id')->on('tipo_dispositivos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device', function (Blueprint $table) {
            $table->dropColumn('tipo_dispositivo_id');
        });
    }
};
