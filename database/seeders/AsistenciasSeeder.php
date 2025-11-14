<?php

namespace Database\Seeders;

use App\Models\Asistencia;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AsistenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando asistencias de prueba...');

        // Obtener todos los grupos con sus alumnos
        $grupos = Grupo::with('alumnos', 'profesor')->get();

        if ($grupos->isEmpty()) {
            $this->command->warn('No hay grupos disponibles. Ejecuta DatabaseCompleteSeeder primero.');
            return;
        }

        $estados = ['presente', 'ausente', 'retardo', 'justificado'];
        $totalAsistencias = 0;

        foreach ($grupos as $grupo) {
            if ($grupo->alumnos->isEmpty()) {
                $this->command->warn("El grupo {$grupo->clave_grupo} no tiene alumnos inscritos.");
                continue;
            }

            $this->command->info("Procesando grupo: {$grupo->clave_grupo} - {$grupo->materia->nombre}");

            // Crear asistencias para los últimos 10 días de clase
            // Suponiendo que tienen clase 2 días a la semana
            $fechasClase = [];
            $fechaInicio = Carbon::now()->subDays(30);
            
            for ($i = 0; $i < 10; $i++) {
                $fechasClase[] = $fechaInicio->copy()->addDays($i * 3); // Cada 3 días
            }

            foreach ($fechasClase as $fecha) {
                foreach ($grupo->alumnos as $alumno) {
                    // 80% de probabilidad de estar presente
                    $random = rand(1, 100);
                    
                    if ($random <= 70) {
                        $estado = 'presente';
                    } elseif ($random <= 80) {
                        $estado = 'retardo';
                    } elseif ($random <= 90) {
                        $estado = 'justificado';
                    } else {
                        $estado = 'ausente';
                    }

                    Asistencia::create([
                        'grupo_id' => $grupo->id,
                        'alumno_id' => $alumno->id,
                        'fecha' => $fecha->format('Y-m-d'),
                        'estado' => $estado,
                        'observaciones' => $estado === 'retardo' ? 'Llegó 10 minutos tarde' : 
                                          ($estado === 'justificado' ? 'Justificante médico presentado' : null),
                        'registrado_por' => $grupo->profesor_id,
                    ]);

                    $totalAsistencias++;
                }
            }
        }

        $this->command->info("✅ Se crearon {$totalAsistencias} registros de asistencia.");
        $this->command->info("📊 Distribución aproximada:");
        $this->command->info("   - 70% Presentes");
        $this->command->info("   - 10% Retardos");
        $this->command->info("   - 10% Justificados");
        $this->command->info("   - 10% Ausentes");
    }
}

