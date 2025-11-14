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
        Schema::table('asistencias', function (Blueprint $table) {
            // Hacer nullable el campo sesion_clase_id para permitir asistencias sin sesión de clase
            $table->foreignId('sesion_clase_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            // Revertir el cambio (volver a requerido)
            // Nota: esto podría fallar si hay registros NULL
            $table->foreignId('sesion_clase_id')->nullable(false)->change();
        });
    }
};
