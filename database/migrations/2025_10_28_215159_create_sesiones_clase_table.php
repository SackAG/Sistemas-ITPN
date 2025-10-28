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
        Schema::create('sesiones_clase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignacion_aula_id')->constrained('asignaciones_aula')->onDelete('cascade');
            $table->foreignId('tema_id')->constrained('temas')->onDelete('cascade');
            $table->date('fecha_sesion');
            $table->time('hora_inicio_real');
            $table->time('hora_fin_real')->nullable();
            $table->enum('tipo_actividad', ['practica', 'ejercicio', 'proyecto', 'investigacion', 'exposicion', 'examen']);
            $table->text('descripcion')->nullable();
            $table->foreignId('profesor_id')->constrained('users')->onDelete('cascade');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices para consultas frecuentes
            $table->index('fecha_sesion');
            $table->index('asignacion_aula_id');
            $table->index('profesor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesiones_clase');
    }
};
