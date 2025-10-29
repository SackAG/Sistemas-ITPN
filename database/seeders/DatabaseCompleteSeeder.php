<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Carrera;
use App\Models\Aula;
use App\Models\Equipo;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Tema;
use App\Models\AsignacionAula;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseCompleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear Carreras
        $carreras = [
            ['nombre' => 'Ingeniería en Sistemas Computacionales', 'clave' => 'ISC', 'activo' => true],
            ['nombre' => 'Ingeniería Industrial', 'clave' => 'II', 'activo' => true],
            ['nombre' => 'Ingeniería Mecatrónica', 'clave' => 'IM', 'activo' => true],
            ['nombre' => 'Ingeniería Electrónica', 'clave' => 'IE', 'activo' => true],
            ['nombre' => 'Ingeniería en Gestión Empresarial', 'clave' => 'IGE', 'activo' => true],
        ];

        foreach ($carreras as $carrera) {
            Carrera::create($carrera);
        }

        $isc = Carrera::where('clave', 'ISC')->first();
        $ii = Carrera::where('clave', 'II')->first();

        // 2. Crear Usuarios
        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@tecnm.mx',
            'password' => Hash::make('admin123'),
            'rol' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Profesores
        $profesor1 = User::create([
            'name' => 'Dr. Juan Pérez',
            'email' => 'juan.perez@tecnm.mx',
            'password' => Hash::make('profesor123'),
            'rol' => 'profesor',
            'carrera_id' => $isc->id,
            'email_verified_at' => now(),
        ]);

        $profesor2 = User::create([
            'name' => 'Ing. María González',
            'email' => 'maria.gonzalez@tecnm.mx',
            'password' => Hash::make('profesor123'),
            'rol' => 'profesor',
            'carrera_id' => $isc->id,
            'email_verified_at' => now(),
        ]);

        $profesor3 = User::create([
            'name' => 'M.C. Carlos Ramírez',
            'email' => 'carlos.ramirez@tecnm.mx',
            'password' => Hash::make('profesor123'),
            'rol' => 'profesor',
            'carrera_id' => $isc->id,
            'email_verified_at' => now(),
        ]);

        // Alumnos
        $alumnos = [];
        for ($i = 1; $i <= 20; $i++) {
            $alumnos[] = User::create([
                'name' => 'Alumno ' . $i,
                'email' => 'alumno' . $i . '@tecnm.mx',
                'password' => Hash::make('alumno123'),
                'rol' => 'alumno',
                'no_ctrl' => '20' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'carrera_id' => $i <= 15 ? $isc->id : $ii->id,
                'email_verified_at' => now(),
            ]);
        }

        // 3. Crear Aulas
        $aulas = [];
        $edificios = ['A', 'B', 'C'];
        foreach ($edificios as $edificio) {
            for ($i = 1; $i <= 5; $i++) {
                $aulas[] = Aula::create([
                    'nombre' => 'Aula ' . $edificio . '-' . $i,
                    'edificio' => $edificio,
                    'capacidad_alumnos' => rand(25, 35),
                    'capacidad_equipos' => rand(20, 30),
                    'activo' => true,
                ]);
            }
        }

        // 4. Crear Equipos
        foreach ($aulas as $aula) {
            // Computadoras
            for ($i = 1; $i <= 20; $i++) {
                Equipo::create([
                    'codigo_inventario' => 'PC-' . $aula->id . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'tipo' => 'computadora',
                    'marca' => ['Dell', 'HP', 'Lenovo'][rand(0, 2)],
                    'modelo' => 'OptiPlex ' . rand(3000, 7000),
                    'numero_serie' => 'SN-' . strtoupper(uniqid()),
                    'estado' => 'operativo',
                    'aula_id' => $aula->id,
                    'ubicacion_en_aula' => 'Mesa ' . $i,
                    'propiedad' => 'institucional',
                    'fecha_adquisicion' => now()->subYears(rand(1, 4)),
                ]);
            }

            // Proyector
            Equipo::create([
                'codigo_inventario' => 'PROY-' . $aula->id,
                'tipo' => 'proyector',
                'marca' => 'Epson',
                'modelo' => 'PowerLite',
                'numero_serie' => 'SN-' . strtoupper(uniqid()),
                'estado' => 'operativo',
                'aula_id' => $aula->id,
                'ubicacion_en_aula' => 'Techo',
                'propiedad' => 'institucional',
                'fecha_adquisicion' => now()->subYears(rand(1, 3)),
            ]);
        }

        // 5. Crear Materias
        $materiasProgramacion = Materia::create([
            'nombre' => 'Programación I',
            'clave' => 'ISC-101',
            'carrera_id' => $isc->id,
            'semestre' => 1,
            'activo' => true,
        ]);

        $materiasBD = Materia::create([
            'nombre' => 'Bases de Datos',
            'clave' => 'ISC-301',
            'carrera_id' => $isc->id,
            'semestre' => 3,
            'activo' => true,
        ]);

        $materiasRedes = Materia::create([
            'nombre' => 'Redes de Computadoras',
            'clave' => 'ISC-501',
            'carrera_id' => $isc->id,
            'semestre' => 5,
            'activo' => true,
        ]);

        // 6. Crear Temas para cada materia
        // Temas de Programación I
        $temasProgramacion = [
            ['numero_tema' => 1, 'nombre' => 'Introducción a la programación', 'descripcion' => 'Conceptos básicos de programación y algoritmos'],
            ['numero_tema' => 2, 'nombre' => 'Variables y tipos de datos', 'descripcion' => 'Tipos de datos primitivos y declaración de variables'],
            ['numero_tema' => 3, 'nombre' => 'Estructuras de control', 'descripcion' => 'Condicionales (if, switch) y ciclos (for, while)'],
        ];

        foreach ($temasProgramacion as $tema) {
            Tema::create(array_merge($tema, ['materia_id' => $materiasProgramacion->id]));
        }

        // Temas de Bases de Datos
        $temasBD = [
            ['numero_tema' => 1, 'nombre' => 'Modelo Entidad-Relación', 'descripcion' => 'Diseño conceptual de bases de datos'],
            ['numero_tema' => 2, 'nombre' => 'SQL Básico', 'descripcion' => 'Consultas SELECT, INSERT, UPDATE, DELETE'],
            ['numero_tema' => 3, 'nombre' => 'Normalización', 'descripcion' => 'Formas normales y optimización de bases de datos'],
        ];

        foreach ($temasBD as $tema) {
            Tema::create(array_merge($tema, ['materia_id' => $materiasBD->id]));
        }

        // Temas de Redes
        $temasRedes = [
            ['numero_tema' => 1, 'nombre' => 'Modelo OSI', 'descripcion' => 'Capas del modelo OSI y sus funciones'],
            ['numero_tema' => 2, 'nombre' => 'Protocolo TCP/IP', 'descripcion' => 'Funcionamiento de TCP/IP y direccionamiento IP'],
            ['numero_tema' => 3, 'nombre' => 'Configuración de redes', 'descripcion' => 'Subnetting y configuración de equipos de red'],
        ];

        foreach ($temasRedes as $tema) {
            Tema::create(array_merge($tema, ['materia_id' => $materiasRedes->id]));
        }

        // 7. Crear Grupos (3 grupos con diferentes materias)
        $grupo1 = Grupo::create([
            'materia_id' => $materiasProgramacion->id,
            'profesor_id' => $profesor1->id,
            'clave_grupo' => 'A',
            'periodo' => 'Ago-Dic',
            'año' => 2025,
            'activo' => true,
        ]);

        $grupo2 = Grupo::create([
            'materia_id' => $materiasBD->id,
            'profesor_id' => $profesor2->id,
            'clave_grupo' => 'B',
            'periodo' => 'Ago-Dic',
            'año' => 2025,
            'activo' => true,
        ]);

        $grupo3 = Grupo::create([
            'materia_id' => $materiasRedes->id,
            'profesor_id' => $profesor3->id,
            'clave_grupo' => 'A',
            'periodo' => 'Ago-Dic',
            'año' => 2025,
            'activo' => true,
        ]);

        // 8. Crear Asignaciones de Aula (cada grupo en diferente aula y horario)
        // Programación I - Lunes y Miércoles 7:00-9:00 - Lab 1
        AsignacionAula::create([
            'aula_id' => $aulas[0]->id, // Lab 1
            'grupo_id' => $grupo1->id,
            'dia_semana' => 'lunes',
            'hora_inicio' => '07:00:00',
            'hora_fin' => '09:00:00',
            'fecha_inicio_vigencia' => now()->startOfMonth(),
            'fecha_fin_vigencia' => now()->addMonths(4)->endOfMonth(),
            'activo' => true,
        ]);

        AsignacionAula::create([
            'aula_id' => $aulas[0]->id, // Lab 1
            'grupo_id' => $grupo1->id,
            'dia_semana' => 'miercoles',
            'hora_inicio' => '07:00:00',
            'hora_fin' => '09:00:00',
            'fecha_inicio_vigencia' => now()->startOfMonth(),
            'fecha_fin_vigencia' => now()->addMonths(4)->endOfMonth(),
            'activo' => true,
        ]);

        // Bases de Datos - Martes y Jueves 9:00-11:00 - Lab 2
        AsignacionAula::create([
            'aula_id' => $aulas[1]->id, // Lab 2
            'grupo_id' => $grupo2->id,
            'dia_semana' => 'martes',
            'hora_inicio' => '09:00:00',
            'hora_fin' => '11:00:00',
            'fecha_inicio_vigencia' => now()->startOfMonth(),
            'fecha_fin_vigencia' => now()->addMonths(4)->endOfMonth(),
            'activo' => true,
        ]);

        AsignacionAula::create([
            'aula_id' => $aulas[1]->id, // Lab 2
            'grupo_id' => $grupo2->id,
            'dia_semana' => 'jueves',
            'hora_inicio' => '09:00:00',
            'hora_fin' => '11:00:00',
            'fecha_inicio_vigencia' => now()->startOfMonth(),
            'fecha_fin_vigencia' => now()->addMonths(4)->endOfMonth(),
            'activo' => true,
        ]);

        // Redes - Miércoles y Viernes 11:00-13:00 - Lab 3
        AsignacionAula::create([
            'aula_id' => $aulas[2]->id, // Lab 3
            'grupo_id' => $grupo3->id,
            'dia_semana' => 'miercoles',
            'hora_inicio' => '11:00:00',
            'hora_fin' => '13:00:00',
            'fecha_inicio_vigencia' => now()->startOfMonth(),
            'fecha_fin_vigencia' => now()->addMonths(4)->endOfMonth(),
            'activo' => true,
        ]);

        AsignacionAula::create([
            'aula_id' => $aulas[2]->id, // Lab 3
            'grupo_id' => $grupo3->id,
            'dia_semana' => 'viernes',
            'hora_inicio' => '11:00:00',
            'hora_fin' => '13:00:00',
            'fecha_inicio_vigencia' => now()->startOfMonth(),
            'fecha_fin_vigencia' => now()->addMonths(4)->endOfMonth(),
            'activo' => true,
        ]);

        // 9. Inscribir alumnos a grupos
        foreach ($alumnos as $index => $alumno) {
            if ($alumno->carrera_id === $isc->id) {
                // Inscribir a Programación I (primeros 10 alumnos)
                if ($index < 10) {
                    $grupo1->alumnos()->attach($alumno->id, [
                        'fecha_inscripcion' => now()->subDays(30),
                        'activo' => true,
                    ]);
                }

                // Inscribir a Bases de Datos (alumnos 5-14)
                if ($index >= 5 && $index < 15) {
                    $grupo2->alumnos()->attach($alumno->id, [
                        'fecha_inscripcion' => now()->subDays(30),
                        'activo' => true,
                    ]);
                }

                // Inscribir a Redes (últimos 8 alumnos de ISC)
                if ($index >= 7 && $index < 15) {
                    $grupo3->alumnos()->attach($alumno->id, [
                        'fecha_inscripcion' => now()->subDays(30),
                        'activo' => true,
                    ]);
                }
            }
        }

        $this->command->info('✅ Base de datos poblada exitosamente!');
        $this->command->info('');
        $this->command->info('📧 CREDENCIALES DE ACCESO:');
        $this->command->info('👑 Admin: admin@tecnm.mx / admin123');
        $this->command->info('👨‍🏫 Profesor 1: juan.perez@tecnm.mx / profesor123');
        $this->command->info('👨‍🏫 Profesor 2: maria.gonzalez@tecnm.mx / profesor123');
        $this->command->info('👨‍🏫 Profesor 3: carlos.ramirez@tecnm.mx / profesor123');
        $this->command->info('👨‍🎓 Alumno: alumno1@tecnm.mx / alumno123');
        $this->command->info('');
        $this->command->info('📊 DATOS CREADOS:');
        $this->command->info('- 5 Carreras');
        $this->command->info('- 23 Usuarios (1 admin, 3 profesores, 20 alumnos)');
        $this->command->info('- 15 Aulas en 3 edificios');
        $this->command->info('- ~315 Equipos (computadoras, proyectores)');
        $this->command->info('- 3 Materias (Programación, BD, Redes)');
        $this->command->info('- 9 Temas (3 por materia)');
        $this->command->info('- 3 Grupos activos');
        $this->command->info('- 6 Asignaciones de aula (2 días por grupo)');
    }
}
