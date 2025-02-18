<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityCentro extends Model
{
    use HasFactory;

    protected $table = 'activity_centro';

    protected $fillable = [
        'activity_id',
        'centro_id',  // Cambiado a 'centro_id'
        'fecha_inicio',
        'fecha_fin',
        'hora_inicio',
        'hora_fin',
        'dias'
    ];

    protected $casts = [
        'dias' => 'array', // Para guardar los días como un array JSON
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function centroCivico()
    {
        return $this->belongsTo(CentroCivico::class, 'centro_id');  // Cambiado a 'centro_id'
    }

    public function users()
    {
        return $this->hasMany(ActivityUser::class, 'centro_id');
    }

    public static function seedActivityCentros()
    {
        // Obtener todos los centros cívicos y actividades
        $centrosCivicos = CentroCivico::all();
        $activities = Activity::all();

        // Datos de ejemplo para la tabla pivote
        $diasSemana = ['L', 'M', 'x', 'J', 'V', 'S', 'D'];

        foreach ($centrosCivicos as $centro) {
            foreach ($activities->random(rand(1, 5)) as $activity) {
                ActivityCentro::create([
                    'activity_id' => $activity->id,
                    'centro_id' => $centro->id,  // Cambiado a 'centro_id'
                    'fecha_inicio' => now()->addDays(rand(1, 30)),
                    'fecha_fin' => now()->addMonths(rand(1, 6)),
                    'hora_inicio' => sprintf('%02d:%02d', rand(9, 20), rand(0, 59)),
                    'hora_fin' => sprintf('%02d:%02d', rand(9, 20), rand(0, 59)),
                    'dias' => collect($diasSemana)->random(rand(1, count($diasSemana)))->toArray(),
                ]);
            }
        }
    }
}