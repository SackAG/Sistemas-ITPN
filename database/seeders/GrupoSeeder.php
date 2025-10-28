<?php

namespace Database\Seeders;

use App\Models\Grupo;
use App\Models\Materia;
use App\Models\User;
use Illuminate\Database\Seeder;

class GrupoSeeder extends Seeder
{
    /**
     * Ejecutar el seeder de grupos.
     * 
     * Crea 3 grupos de diferentes materias con sus respectivos profesores.
     */
    public function run(): void
    {
        // Obtener materias existentes
        $materias = Materia::all();
        
        if ($materias->count() < 3) {
            $this->command->warn('⚠️  Se necesitan al menos 3 materias. Ejecuta primero los seeders de Carreras y Materias.');
            return;
        }

        // Obtener profesores disponibles
        $profesores = User::where('rol', 'profesor')->get();
        
        if ($profesores->count() < 3) {
            $this->command->warn('⚠️  Se necesitan al menos 3 profesores. Ejecuta primero el UserSeeder.');
            return;
        }

        // Grupo 1: Primera materia - Grupo A
        Grupo::create([
            'materia_id' => $materias[0]->id,
            'profesor_id' => $profesores[0]->id,
            'clave_grupo' => 'A',
            'periodo' => 'Ago-Dic',
            'año' => 2025,
            'activo' => true,
        ]);

        // Grupo 2: Segunda materia - Grupo B
        Grupo::create([
            'materia_id' => $materias[1]->id,
            'profesor_id' => $profesores[1]->id,
            'clave_grupo' => 'B',
            'periodo' => 'Ago-Dic',
            'año' => 2025,
            'activo' => true,
        ]);

        // Grupo 3: Tercera materia - Grupo A
        Grupo::create([
            'materia_id' => $materias[2]->id,
            'profesor_id' => $profesores->count() > 2 ? $profesores[2]->id : $profesores[0]->id,
            'clave_grupo' => 'A',
            'periodo' => 'Ago-Dic',
            'año' => 2025,
            'activo' => true,
        ]);

        $this->command->info('✅ 3 grupos creados exitosamente.');
    }
}
