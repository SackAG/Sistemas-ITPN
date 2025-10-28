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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['admin', 'profesor', 'alumno'])->default('alumno')->after('password');
            $table->string('no_ctrl')->unique()->nullable()->after('rol');
            $table->foreignId('carrera_id')->nullable()->constrained('carreras')->onDelete('set null')->after('no_ctrl');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['carrera_id']);
            $table->dropColumn(['rol', 'no_ctrl', 'carrera_id']);
        });
    }
};
