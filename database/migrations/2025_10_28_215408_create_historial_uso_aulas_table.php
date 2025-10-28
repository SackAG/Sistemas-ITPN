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
        Schema::create('historial_uso_aulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade');
            $table->enum('tipo_uso', ['clase', 'uso_libre', 'evento_especial', 'mantenimiento', 'reservado']);
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin')->nullable();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('grupo_id')->nullable()->constrained('grupos')->onDelete('set null');
            $table->foreignId('sesion_clase_id')->nullable()->constrained('sesiones_clase')->onDelete('set null');
            $table->foreignId('uso_libre_id')->nullable()->constrained('uso_libre_equipos')->onDelete('set null');
            $table->foreignId('reservacion_id')->nullable()->constrained('reservaciones_equipos')->onDelete('set null');
            $table->text('descripcion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_uso_aulas');
    }
};
