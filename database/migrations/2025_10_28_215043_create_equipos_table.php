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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_inventario')->unique();
            $table->enum('tipo', ['computadora', 'proyector', 'impresora', 'laptop', 'tablet', 'otro']);
            $table->string('marca');
            $table->string('modelo');
            $table->string('numero_serie')->unique()->nullable();
            $table->enum('estado', ['operativo', 'mantenimiento', 'baja'])->default('operativo');
            $table->foreignId('aula_id')->nullable()->constrained('aulas')->onDelete('set null');
            $table->string('ubicacion_en_aula')->nullable();
            $table->enum('propiedad', ['institucional', 'personal'])->default('institucional');
            $table->foreignId('propietario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('fecha_adquisicion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices para mejorar consultas
            $table->index(['aula_id', 'estado']);
            $table->index(['tipo', 'estado']);
            $table->index('propiedad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
