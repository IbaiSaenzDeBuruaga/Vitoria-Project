<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'imagen',
        'plazas',
        'edad_min',
        'edad_max',
        'horario',
        'idioma',
    ];

    public function activitiesCentros()
    {
        return $this->belongsToMany(CentroCivico::class, 'activity_centro', 'activity_id', 'centro_id')
                    ->withPivot('fecha_inicio', 'fecha_fin', 'hora_inicio', 'hora_fin', 'dias')
                    ->withTimestamps();
    }

    public function centros()
    {
        return $this->belongsToMany(CentroCivico::class, 'activity_centro', 'activity_id', 'centro_id')
                    ->withPivot('fecha_inicio', 'fecha_fin', 'hora_inicio', 'hora_fin', 'dias')
                    ->withTimestamps();
    }

    public static function seedActivities()
    {
        $actividades = [
            ['nombre' => 'Fútbol', 'imagen' => '/images/futbol.jpg', 'plazas' => 20, 'edad_min' => 8, 'edad_max' => 16, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Baloncesto', 'imagen' => '/images/baloncesto.jpg', 'plazas' => 15, 'edad_min' => 10, 'edad_max' => 18, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Tai Chi', 'imagen' => '/images/taichi.jpg', 'plazas' => 25, 'edad_min' => 18, 'edad_max' => 65, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Ping Pong', 'imagen' => '/images/pingpong.jpg', 'plazas' => 10, 'edad_min' => 6, 'edad_max' => 99, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Yoga', 'imagen' => '/images/yoga.png', 'plazas' => 20, 'edad_min' => 16, 'edad_max' => 60, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Zumba', 'imagen' => '/images/zumba.jpg', 'plazas' => 30, 'edad_min' => 14, 'edad_max' => 50, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Pilates', 'imagen' => '/images/pilates.png', 'plazas' => 25, 'edad_min' => 16, 'edad_max' => 60, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Danza Moderna', 'imagen' => 'url_imagen_danzamoderna.jpg', 'plazas' => 15, 'edad_min' => 8, 'edad_max' => 16, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Patinaje', 'imagen' => 'url_imagen_patinaje.jpg', 'plazas' => 12, 'edad_min' => 6, 'edad_max' => 12, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Escalada', 'imagen' => 'url_imagen_escalada.jpg', 'plazas' => 8, 'edad_min' => 12, 'edad_max' => 25, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Tenis', 'imagen' => 'url_imagen_tenis.jpg', 'plazas' => 10, 'edad_min' => 10, 'edad_max' => 20, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Badminton', 'imagen' => 'url_imagen_badminton.jpg', 'plazas' => 12, 'edad_min' => 10, 'edad_max' => 20, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Boxeo', 'imagen' => 'url_imagen_boxeo.jpg', 'plazas' => 15, 'edad_min' => 16, 'edad_max' => 35, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Artes Marciales', 'imagen' => 'url_imagen_artesmarciales.jpg', 'plazas' => 20, 'edad_min' => 8, 'edad_max' => 16, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Senderismo', 'imagen' => 'url_imagen_senderismo.jpg', 'plazas' => 30, 'edad_min' => 12, 'edad_max' => 65, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Ajedrez', 'imagen' => 'url_imagen_ajedrez.jpg', 'plazas' => 16, 'edad_min' => 6, 'edad_max' => 99, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Manualidades', 'imagen' => 'url_imagen_manualidades.jpg', 'plazas' => 20, 'edad_min' => 6, 'edad_max' => 99, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Cocina', 'imagen' => 'url_imagen_cocina.jpg', 'plazas' => 12, 'edad_min' => 12, 'edad_max' => 99, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Teatro', 'imagen' => '/images/teatro.jpg', 'plazas' => 25, 'edad_min' => 8, 'edad_max' => 99, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Fotografía', 'imagen' => 'url_imagen_fotografia.jpg', 'plazas' => 15, 'edad_min' => 16, 'edad_max' => 99, 'horario' => 'vespertino', 'idioma' => 'es'],
        ];

        foreach ($actividades as $actividadData) {
            $actividad = Activity::create($actividadData);
        }
    }
}