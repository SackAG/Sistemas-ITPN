<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Materia>
 */
class MateriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $materias = [
            'Programación',
            'Bases de Datos',
            'Redes',
            'Estructuras de Datos',
            'Algoritmos',
            'Cálculo Diferencial',
            'Álgebra Lineal',
            'Física',
            'Contabilidad',
            'Administración',
        ];

        return [
            'nombre' => fake()->randomElement($materias) . ' ' . fake()->randomElement(['I', 'II', 'III', 'Avanzada']),
            'clave' => fake()->unique()->bothify('???-####'),
            'semestre' => fake()->numberBetween(1, 9),
            'activo' => true,
        ];
    }
}
