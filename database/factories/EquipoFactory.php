<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipo>
 */
class EquipoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipos = ['computadora', 'proyector', 'impresora', 'laptop', 'tablet'];
        $tipo = fake()->randomElement($tipos);
        $marcas = [
            'computadora' => ['Dell', 'HP', 'Lenovo', 'Acer'],
            'proyector' => ['Epson', 'BenQ', 'Sony', 'ViewSonic'],
            'impresora' => ['HP', 'Canon', 'Epson', 'Brother'],
            'laptop' => ['Dell', 'HP', 'Lenovo', 'Asus'],
            'tablet' => ['Samsung', 'Apple', 'Lenovo', 'Huawei'],
        ];

        return [
            'codigo_inventario' => 'INV-' . fake()->unique()->numerify('####'),
            'tipo' => $tipo,
            'marca' => fake()->randomElement($marcas[$tipo]),
            'modelo' => fake()->bothify('??-####'),
            'numero_serie' => fake()->optional(0.8)->bothify('SN-??########'),
            'estado' => fake()->randomElement(['operativo', 'operativo', 'operativo', 'mantenimiento']),
            'ubicacion_en_aula' => fake()->optional(0.7)->randomElement(['Mesa 1', 'Mesa 2', 'Mesa 3', 'Escritorio profesor']),
            'propiedad' => 'institucional',
            'fecha_adquisicion' => fake()->optional(0.6)->dateTimeBetween('-5 years', 'now'),
            'observaciones' => fake()->optional(0.3)->sentence(),
        ];
    }
}
