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
        // En MySQL, para modificar un ENUM necesitamos usar DB::statement
        DB::statement("ALTER TABLE equipos MODIFY COLUMN tipo ENUM('computadora', 'proyector', 'switch', 'router', 'impresora', 'otro') NOT NULL");
        DB::statement("ALTER TABLE equipos MODIFY COLUMN estado ENUM('disponible', 'en_uso', 'mantenimiento', 'dañado', 'dado_de_baja') NOT NULL DEFAULT 'disponible'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a los valores originales
        DB::statement("ALTER TABLE equipos MODIFY COLUMN tipo ENUM('computadora', 'proyector', 'impresora', 'laptop', 'tablet', 'otro') NOT NULL");
        DB::statement("ALTER TABLE equipos MODIFY COLUMN estado ENUM('operativo', 'mantenimiento', 'baja') NOT NULL DEFAULT 'operativo'");
    }
};
