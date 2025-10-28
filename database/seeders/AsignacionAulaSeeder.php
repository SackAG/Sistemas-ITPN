<?php

namespace Database\Seeders;

use App\Models\AsignacionAula;
use App\Models\Aula;
use App\Models\Grupo;
use Illuminate\Database\Seeder;

class AsignacionAulaSeeder extends Seeder
{
    /**
     * Ejecutar el seeder de asignaciones de aula.
     * 
     * Asigna cada grupo a un aula en diferentes horarios (2 días por semana).
     */
    public function run(): void
    {
        // Obtener grupos activos
        $grupos = Grupo::where('activo', true)->get();

        if ($grupos->isEmpty()) {
            $this->command->warn('⚠️  No hay grupos activos. Ejecuta primero el GrupoSeeder.');
            return;
        }

        // Obtener aulas activas
        $aulas = Aula::where('activo', true)->get();

        if ($aulas->count() < 3) {
            $this->command->warn('⚠️  Se necesitan al menos 3 aulas activas.');
            return;
        }

        // Definir horarios diferentes para cada grupo (2 días por semana)
        $horarios = [
            [
                ['dia' => 'lunes', 'hora_inicio' => '07:00', 'hora_fin' => '09:00'],
                ['dia' => 'miercoles', 'hora_inicio' => '07:00', 'hora_fin' => '09:00'],
            ],
            [
                ['dia' => 'martes', 'hora_inicio' => '09:00', 'hora_fin' => '11:00'],
                ['dia' => 'jueves', 'hora_inicio' => '09:00', 'hora_fin' => '11:00'],
            ],
            [
                ['dia' => 'miercoles', 'hora_inicio' => '11:00', 'hora_fin' => '13:00'],
                ['dia' => 'viernes', 'hora_inicio' => '11:00', 'hora_fin' => '13:00'],
            ],
        ];

        $totalCreadas = 0;
        
        // Fechas de vigencia para el semestre
        $fechaInicio = '2025-08-01';
        $fechaFin = '2025-12-20';

        foreach ($grupos as $index => $grupo) {
            // Asignar un aula (rotar entre las disponibles)
            $aula = $aulas[$index % $aulas->count()];
            
            // Obtener horarios para este grupo (rotar entre los horarios definidos)
            $horariosGrupo = $horarios[$index % count($horarios)];

            // Crear 2 asignaciones (2 días por semana)
            foreach ($horariosGrupo as $horario) {
                AsignacionAula::create([
                    'grupo_id' => $grupo->id,
                    'aula_id' => $aula->id,
                    'dia_semana' => $horario['dia'],
                    'hora_inicio' => $horario['hora_inicio'],
                    'hora_fin' => $horario['hora_fin'],
                    'fecha_inicio_vigencia' => $fechaInicio,
                    'fecha_fin_vigencia' => $fechaFin,
                    'activo' => true,
                ]);
                $totalCreadas++;
            }
        }

        $this->command->info("✅ {$totalCreadas} asignaciones de aula creadas para {$grupos->count()} grupo(s).");
    }
}
