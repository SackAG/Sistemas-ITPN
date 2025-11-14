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
        Schema::table('equipos', function (Blueprint $table) {
            // Agregar campos adicionales
            $table->string('nombre')->nullable()->after('id');
            $table->string('ubicacion_especifica')->nullable()->after('ubicacion_en_aula');
            $table->boolean('activo')->default(true)->after('observaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'ubicacion_especifica', 'activo']);
        });
    }
};
