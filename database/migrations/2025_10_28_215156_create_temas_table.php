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
        Schema::create('temas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->integer('numero_tema')->unsigned();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->timestamps();
            
            // Índice compuesto para ordenar temas por materia
            $table->index(['materia_id', 'numero_tema']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temas');
    }
};
