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
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('profesor_id')->constrained('users')->onDelete('cascade');
            $table->string('clave_grupo');
            $table->string('periodo');
            $table->integer('año')->unsigned();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índices para mejorar consultas
            $table->index('materia_id');
            $table->index('profesor_id');
            $table->index(['activo', 'año']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
