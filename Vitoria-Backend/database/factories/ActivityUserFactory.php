<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\ActivityCentro;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityUser>
 */
class ActivityUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::pluck('id')->random(),
            'activity_id' => ActivityCentro::pluck('id')->random(),
        ];
    }
}
