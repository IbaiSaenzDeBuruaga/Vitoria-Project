<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'centro_id' // Clave foránea correcta para la tabla pivote
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityCentro()
    {
        return $this->belongsTo(ActivityCentro::class, 'centro_id'); // Usa la clave foránea correcta
    }

    public static function seedActivityUsers()
    {
        // Obtener todos los usuarios y las relaciones ActivityCentro
        $users = User::all();
        $activityCentros = ActivityCentro::all();

        // Asignar actividades a usuarios aleatoriamente
        foreach ($users as $user) {
            // Asigna un número aleatorio de actividades (entre 0 y 3) a cada usuario
            foreach ($activityCentros->random(rand(0, 3)) as $activityCentro) {
                ActivityUser::create([
                    'user_id' => $user->id,
                    'activity_id' => $activityCentro->id,
                ]);
            }
        }
    }
}