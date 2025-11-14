<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReservacionEquipo;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Aula;
use App\Models\Grupo;
use Carbon\Carbon;

class ReservacionEquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos necesarios
        $profesores = User::where('rol', 'profesor')->get();
        $equipos = Equipo::where('activo', true)->get();
        $aulas = Aula::where('activo', true)->get();
        $grupos = Grupo::where('activo', true)->get();

        if ($profesores->isEmpty() || $equipos->isEmpty() || $aulas->isEmpty()) {
            $this->command->warn('No hay suficientes datos para crear reservaciones. Ejecuta primero los seeders de usuarios, equipos y aulas.');
            return;
        }

        $estados = ['pendiente', 'aprobada', 'rechazada', 'cancelada'];
        $motivos = [
            'Clase práctica de programación',
            'Examen de certificación',
            'Taller de desarrollo web',
            'Proyecto final del semestre',
            'Capacitación de software',
            'Exposición de trabajos',
            'Práctica de bases de datos',
            'Curso de redes',
            'Desarrollo de aplicaciones móviles',
            'Mantenimiento preventivo',
        ];

        // Crear 30 reservaciones de prueba
        $reservacionesCreadas = 0;
        $intentos = 0;
        $maxIntentos = 100;

        while ($reservacionesCreadas < 30 && $intentos < $maxIntentos) {
            $intentos++;
            
            $profesor = $profesores->random();
            $equipo = $equipos->random();
            $aula = $aulas->random();
            $grupo = $grupos->where('profesor_id', $profesor->id)->first() ?? $grupos->random();
            
            // Fechas variadas (pasadas, presentes y futuras)
            $diasOffset = rand(-15, 30);
            $fecha = Carbon::now()->addDays($diasOffset)->format('Y-m-d');
            
            // Horarios variados con minutos aleatorios para evitar conflictos
            $hora = rand(7, 17);
            $minuto = [0, 15, 30, 45][rand(0, 3)];
            $horaInicio = sprintf('%02d:%02d', $hora, $minuto);
            $duracion = rand(1, 3); // 1 a 3 horas
            $horaFin = Carbon::createFromFormat('H:i', $horaInicio)->addHours($duracion)->format('H:i');

            // Verificar si ya existe una reservación con estos datos
            $existe = ReservacionEquipo::where('equipo_id', $equipo->id)
                ->where('fecha_reservacion', $fecha)
                ->where('hora_inicio', $horaInicio)
                ->exists();

            if ($existe) {
                continue; // Saltar e intentar con otros datos
            }

            // Estado basado en la fecha
            if ($diasOffset < 0) {
                $estado = rand(1, 10) <= 8 ? 'aprobada' : ['aprobada', 'cancelada'][rand(0, 1)];
            } elseif ($diasOffset < 7) {
                $estado = rand(1, 10) <= 7 ? 'aprobada' : 'pendiente';
            } else {
                $estado = $estados[rand(0, 3)];
            }

            try {
                ReservacionEquipo::create([
                    'profesor_id' => $profesor->id,
                    'alumno_id' => null,
                    'equipo_id' => $equipo->id,
                    'aula_id' => $aula->id,
                    'grupo_id' => $grupo->id,
                    'fecha_reservacion' => $fecha,
                    'hora_inicio' => $horaInicio,
                    'hora_fin' => $horaFin,
                    'motivo' => $motivos[array_rand($motivos)],
                    'estado' => $estado,
                ]);
                $reservacionesCreadas++;
            } catch (\Exception $e) {
                continue;
            }
        }

        $this->command->info("✓ {$reservacionesCreadas} reservaciones de equipos creadas exitosamente");
    }
}

