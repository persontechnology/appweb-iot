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
        Schema::table('user', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            
            $table->string('password')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('nombres')->nullable();
            $table->string('identificacion')->nullable();

            // $table->string('tenant_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn(['password','apellidos','nombres','identificacion','email_verified_at','remember_token']);
        });
    }
};
