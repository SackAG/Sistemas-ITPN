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
        Schema::create('reservaciones_equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade');
            $table->date('fecha_reservacion');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->text('motivo');
            $table->enum('estado', ['pendiente', 'en_uso', 'completada', 'cancelada'])->default('pendiente');
            $table->foreignId('grupo_id')->nullable()->constrained('grupos')->onDelete('set null');
            $table->timestamps();
            
            // Índices para consultas de reservaciones
            $table->index(['equipo_id', 'fecha_reservacion', 'estado']);
            $table->index(['alumno_id', 'fecha_reservacion']);
            
            // Constraint único para evitar dobles reservaciones del mismo equipo
            $table->unique(['equipo_id', 'fecha_reservacion', 'hora_inicio'], 'unique_reservacion_equipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservaciones_equipos');
    }
};
