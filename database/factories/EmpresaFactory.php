<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->company(),
            'razon_social' => fake()->company() . ' S.A.S.',
            'nit' => fake()->unique()->numerify('#########-#'),
            'direccion' => fake()->address(),
            'correo' => fake()->companyEmail(),
            'celular' => fake()->phoneNumber(),
            'activa' => true,
        ];
    }
}
