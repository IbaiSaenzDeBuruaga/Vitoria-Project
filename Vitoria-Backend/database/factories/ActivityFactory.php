<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name(),
            'edad_min' => $this->faker->numberBetween($min = 3, $max =  18),
            'edad_max' => $this->faker->numberBetween($min = 18, $max =  100),
            'horario' => $this->faker->randomElement(['matutino', 'vespertino']),
            'idioma' => $this->faker->randomElement(['es', 'en', 'eu']),
            'plazas' => $this->faker->numberBetween($min = 5, $max = 30),
        ];
    }
}
