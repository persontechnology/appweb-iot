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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('valor');
            $table->string('descripcion');
            $table->string('color');
            $table->boolean('notification')->default(false);
            $table->jsonb('notification_types')->nullable();
            $table->jsonb('device_mode')->nullable();

            $table->uuid('application_id');
            $table->uuid('device_profile_id');
            $table->foreign('device_profile_id')->references('id')->on('device_profile')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
