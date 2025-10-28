<?php

namespace Database\Seeders;

use App\Models\Materia;
use App\Models\Tema;
use Illuminate\Database\Seeder;

class TemaSeeder extends Seeder
{
    /**
     * Ejecutar el seeder de temas.
     * 
     * Crea 3 temas por cada materia existente.
     */
    public function run(): void
    {
        // Obtener todas las materias
        $materias = Materia::all();

        if ($materias->isEmpty()) {
            $this->command->warn('⚠️  No hay materias en la base de datos. Ejecuta primero el seeder de Materias.');
            return;
        }

        // Definir temas genéricos por materia
        $temasPorMateria = [
            'Programación' => [
                ['nombre' => 'Introducción a la Programación', 'descripcion' => 'Conceptos básicos de programación y algoritmos'],
                ['nombre' => 'Estructuras de Control', 'descripcion' => 'Condicionales, ciclos y control de flujo'],
                ['nombre' => 'Funciones y Módulos', 'descripcion' => 'Creación y uso de funciones, modularización de código'],
            ],
            'Base de Datos' => [
                ['nombre' => 'Modelo Relacional', 'descripcion' => 'Fundamentos del modelo relacional y normalización'],
                ['nombre' => 'SQL Básico', 'descripcion' => 'Consultas SELECT, INSERT, UPDATE, DELETE'],
                ['nombre' => 'Diseño de Base de Datos', 'descripcion' => 'Modelado ER y creación de esquemas'],
            ],
            'Redes' => [
                ['nombre' => 'Modelo OSI', 'descripcion' => 'Capas del modelo OSI y protocolos de red'],
                ['nombre' => 'Direccionamiento IP', 'descripcion' => 'IPv4, subredes y máscaras de red'],
                ['nombre' => 'Protocolos de Aplicación', 'descripcion' => 'HTTP, FTP, DNS y otros protocolos'],
            ],
        ];

        $totalCreados = 0;

        foreach ($materias as $materia) {
            // Buscar temas específicos para esta materia
            $temasMateria = null;
            
            foreach ($temasPorMateria as $nombreMateria => $temas) {
                if (stripos($materia->nombre, $nombreMateria) !== false) {
                    $temasMateria = $temas;
                    break;
                }
            }

            // Si no encuentra temas específicos, usar temas genéricos
            if (!$temasMateria) {
                $temasMateria = [
                    ['nombre' => "Tema 1: {$materia->nombre}", 'descripcion' => "Introducción a {$materia->nombre}"],
                    ['nombre' => "Tema 2: {$materia->nombre}", 'descripcion' => "Conceptos intermedios de {$materia->nombre}"],
                    ['nombre' => "Tema 3: {$materia->nombre}", 'descripcion' => "Conceptos avanzados de {$materia->nombre}"],
                ];
            }

            // Crear los 3 temas para esta materia
            foreach ($temasMateria as $index => $temaData) {
                Tema::create([
                    'materia_id' => $materia->id,
                    'numero_tema' => $index + 1,
                    'nombre' => $temaData['nombre'],
                    'descripcion' => $temaData['descripcion'],
                ]);
                $totalCreados++;
            }
        }

        $this->command->info("✅ {$totalCreados} temas creados para {$materias->count()} materia(s).");
    }
}
