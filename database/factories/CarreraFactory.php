<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Carrera>
 */
class CarreraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $carreras = [
            ['nombre' => 'Ingeniería en Sistemas Computacionales', 'clave' => 'ISC'],
            ['nombre' => 'Ingeniería Industrial', 'clave' => 'II'],
            ['nombre' => 'Ingeniería Mecatrónica', 'clave' => 'IM'],
            ['nombre' => 'Ingeniería Electrónica', 'clave' => 'IE'],
            ['nombre' => 'Ingeniería en Gestión Empresarial', 'clave' => 'IGE'],
        ];

        $carrera = fake()->randomElement($carreras);

        return [
            'nombre' => $carrera['nombre'],
            'clave' => $carrera['clave'],
            'activo' => true,
        ];
    }
}
