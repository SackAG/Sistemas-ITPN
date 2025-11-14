<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservaciones_equipos', function (Blueprint $table) {
            // Agregar profesor_id
            $table->foreignId('profesor_id')->nullable()->after('alumno_id')->constrained('users')->onDelete('cascade');
            
            // Hacer alumno_id nullable (las reservaciones pueden ser por profesor sin especificar alumno)
            $table->foreignId('alumno_id')->nullable()->change();
        });
        
        // Actualizar enum de estado
        DB::statement("ALTER TABLE reservaciones_equipos MODIFY COLUMN estado ENUM('pendiente', 'aprobada', 'rechazada', 'cancelada', 'en_uso', 'completada') DEFAULT 'pendiente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservaciones_equipos', function (Blueprint $table) {
            $table->dropForeign(['profesor_id']);
            $table->dropColumn('profesor_id');
        });
        
        // Revertir enum de estado
        DB::statement("ALTER TABLE reservaciones_equipos MODIFY COLUMN estado ENUM('pendiente', 'en_uso', 'completada', 'cancelada') DEFAULT 'pendiente'");
    }
};
