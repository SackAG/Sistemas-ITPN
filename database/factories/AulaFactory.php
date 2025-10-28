<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aula>
 */
class AulaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $edificios = ['A', 'B', 'C', 'D', 'E'];
        $numero = fake()->numberBetween(101, 310);

        return [
            'nombre' => 'Aula ' . $numero,
            'edificio' => fake()->randomElement($edificios),
            'capacidad_alumnos' => fake()->randomElement([20, 25, 30, 35, 40]),
            'capacidad_equipos' => fake()->randomElement([15, 20, 25, 30]),
            'activo' => true,
        ];
    }
}
