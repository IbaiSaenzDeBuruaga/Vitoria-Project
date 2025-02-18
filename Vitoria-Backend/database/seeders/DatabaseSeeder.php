<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CentroCivico;
use App\Models\Activity;
use App\Models\ActivityCentro;
use App\Models\ActivityUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::seedUsers();
        CentroCivico::seedCentroCivicos();
        Activity::seedActivities();
        ActivityCentro::seedActivityCentros();
        ActivityUser::seedActivityUsers();
    }
}