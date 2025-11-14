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
            // Agregar campos para el sistema de asistencias de profesores
            $table->foreignId('grupo_id')->nullable()->after('sesion_clase_id')->constrained('grupos')->onDelete('cascade');
            $table->date('fecha')->nullable()->after('grupo_id');
            $table->enum('estado', ['presente', 'ausente', 'retardo', 'justificado'])->nullable()->after('asistio');
            $table->foreignId('registrado_por')->nullable()->after('observaciones')->constrained('users')->onDelete('set null');
            
            // Índices para mejorar consultas
            $table->index(['grupo_id', 'fecha']);
            $table->index(['alumno_id', 'grupo_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            // Eliminar índices primero
            $table->dropIndex(['grupo_id', 'fecha']);
            $table->dropIndex(['alumno_id', 'grupo_id', 'fecha']);
            
            // Eliminar foreign keys
            $table->dropForeign(['grupo_id']);
            $table->dropForeign(['registrado_por']);
            
            // Eliminar columnas
            $table->dropColumn(['grupo_id', 'fecha', 'estado', 'registrado_por']);
        });
    }
};
