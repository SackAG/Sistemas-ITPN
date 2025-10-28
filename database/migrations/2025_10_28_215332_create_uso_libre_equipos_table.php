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
        Schema::create('uso_libre_equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade');
            $table->date('fecha_uso');
            $table->time('hora_inicio');
            $table->time('hora_fin')->nullable();
            $table->enum('motivo', ['tarea', 'proyecto_personal', 'investigacion', 'estudio', 'otro']);
            $table->text('descripcion')->nullable();
            $table->foreignId('autorizado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Índices para consultas de uso libre
            $table->index(['fecha_uso', 'alumno_id']);
            $table->index(['equipo_id', 'fecha_uso']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uso_libre_equipos');
    }
};
