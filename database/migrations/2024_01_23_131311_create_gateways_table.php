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
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre');
            $table->string('modelo');
            $table->string('fcc_id');
            $table->string('direccion_ip');
            $table->string('usuario');
            $table->string('password');
            $table->string('imei');
            $table->string('mac');
            $table->string('foto')->nullable();
            $table->enum('estado',['ACTIVO','INACTIVO'])->default('ACTIVO');
            $table->enum('conectado',['SI','NO'])->default('NO');
            $table->string('lat')->default(0);
            $table->string('lng')->default(0);
            $table->string('descripcion')->default(0);

            $table->foreignId('categoria_gateway_id')->constrained(
                table: 'categoria_gateways'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateways');
    }
};
