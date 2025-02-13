<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\ActivityUser;
use App\Models\CentroCivico;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityCentro>
 */
class ActivityCentroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'activity_id' => Activity::pluck('id')->random(),
            'centro_id' => CentroCivico::pluck('id')->random(),
            'fecha_inicio' => $this->faker->date(),
            'fecha_fin' => $this->faker->date(),
            'hora_inicio' => $this->faker->time(),
            'hora_fin' => $this->faker->time(),
            'dias' => collect(['L', 'M', 'X', 'J', 'V', 'S', 'D'])
                ->random(rand(1, 5))
                ->implode(''),
        ];
    }
}
