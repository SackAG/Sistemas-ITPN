<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Carrera;
use App\Models\Aula;
use App\Models\Equipo;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Tema;
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
        ]);

        // Profesores
        $profesor1 = User::create([
            'name' => 'Dr. Juan Pérez',
            'email' => 'juan.perez@tecnm.mx',
            'password' => Hash::make('profesor123'),
            'rol' => 'profesor',
            'carrera_id' => $isc->id,
        ]);

        $profesor2 = User::create([
            'name' => 'Ing. María González',
            'email' => 'maria.gonzalez@tecnm.mx',
            'password' => Hash::make('profesor123'),
            'rol' => 'profesor',
            'carrera_id' => $isc->id,
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

        // 6. Crear Temas
        $temasProg = [
            ['numero_tema' => 1, 'nombre' => 'Introducción a la programación', 'descripcion' => 'Conceptos básicos'],
            ['numero_tema' => 2, 'nombre' => 'Variables y tipos de datos', 'descripcion' => 'Tipos de datos primitivos'],
            ['numero_tema' => 3, 'nombre' => 'Estructuras de control', 'descripcion' => 'If, while, for'],
            ['numero_tema' => 4, 'nombre' => 'Funciones', 'descripcion' => 'Definición y uso de funciones'],
        ];

        foreach ($temasProg as $tema) {
            Tema::create(array_merge($tema, ['materia_id' => $materiasProgramacion->id]));
        }

        // 7. Crear Grupos
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

        // 8. Inscribir alumnos a grupos
        foreach ($alumnos as $index => $alumno) {
            if ($alumno->carrera_id === $isc->id) {
                // Inscribir a Programación I
                $grupo1->alumnos()->attach($alumno->id, [
                    'fecha_inscripcion' => now()->subDays(30),
                    'activo' => true,
                ]);

                // Algunos también a BD
                if ($index < 10) {
                    $grupo2->alumnos()->attach($alumno->id, [
                        'fecha_inscripcion' => now()->subDays(30),
                        'activo' => true,
                    ]);
                }
            }
        }

        $this->command->info('✅ Base de datos poblada exitosamente!');
        $this->command->info('📧 Admin: admin@tecnm.mx / admin123');
        $this->command->info('👨‍🏫 Profesor: juan.perez@tecnm.mx / profesor123');
        $this->command->info('👨‍🎓 Alumno: alumno1@tecnm.mx / alumno123');
    }
}
