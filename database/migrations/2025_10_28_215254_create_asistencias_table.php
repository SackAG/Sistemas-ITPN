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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesion_clase_id')->constrained('sesiones_clase')->onDelete('cascade');
            $table->foreignId('alumno_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('equipo_id')->nullable()->constrained('equipos')->onDelete('set null');
            $table->time('hora_entrada');
            $table->time('hora_salida')->nullable();
            $table->boolean('asistio')->default(true);
            $table->boolean('uso_equipo_personal')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices para consultas de asistencia
            $table->index('sesion_clase_id');
            $table->index(['alumno_id', 'sesion_clase_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
