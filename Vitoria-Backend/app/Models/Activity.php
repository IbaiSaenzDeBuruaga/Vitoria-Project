<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;
use Faker\Factory as Faker; // Importa Faker

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
        $actividadesBase = [
            ['nombre' => 'Fútbol', 'imagen' => '/images/futbol.jpg', 'plazas' => 20, 'edad_min' => 8, 'edad_max' => 16, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Baloncesto', 'imagen' => '/images/baloncesto.jpg', 'plazas' => 15, 'edad_min' => 10, 'edad_max' => 18, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Tai Chi', 'imagen' => '/images/taichi.jpg', 'plazas' => 25, 'edad_min' => 18, 'edad_max' => 65, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Ping Pong', 'imagen' => '/images/pingpong.jpg', 'plazas' => 10, 'edad_min' => 6, 'edad_max' => 99, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Yoga', 'imagen' => '/images/yoga.png', 'plazas' => 20, 'edad_min' => 16, 'edad_max' => 60, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Zumba', 'imagen' => '/images/zumba.jpg', 'plazas' => 30, 'edad_min' => 14, 'edad_max' => 50, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Pilates', 'imagen' => '/images/pilates.png', 'plazas' => 25, 'edad_min' => 16, 'edad_max' => 60, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Danza Moderna', 'imagen' => '/images/danza_moderna.jpg', 'plazas' => 15, 'edad_min' => 8, 'edad_max' => 16, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Patinaje', 'imagen' => '/images/patinaje.jpg', 'plazas' => 12, 'edad_min' => 6, 'edad_max' => 12, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Escalada', 'imagen' => '/images/escalada.jpg', 'plazas' => 8, 'edad_min' => 12, 'edad_max' => 25, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Tenis', 'imagen' => '/images/tenis.jpg', 'plazas' => 10, 'edad_min' => 10, 'edad_max' => 20, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Badminton', 'imagen' => '/images/badminton.jpg', 'plazas' => 12, 'edad_min' => 10, 'edad_max' => 20, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Boxeo', 'imagen' => '/images/boxeo.jpg', 'plazas' => 15, 'edad_min' => 16, 'edad_max' => 35, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Artes Marciales', 'imagen' => '/images/artesmarciales.jpg', 'plazas' => 20, 'edad_min' => 8, 'edad_max' => 16, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Senderismo', 'imagen' => '/images/senderismo.jpg', 'plazas' => 30, 'edad_min' => 12, 'edad_max' => 65, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Ajedrez', 'imagen' => '/images/ajedrez.jpg', 'plazas' => 16, 'edad_min' => 6, 'edad_max' => 99, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Manualidades', 'imagen' => '/images/manualidades.png', 'plazas' => 20, 'edad_min' => 6, 'edad_max' => 99, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Cocina', 'imagen' => '/images/cocina.jpg', 'plazas' => 12, 'edad_min' => 12, 'edad_max' => 99, 'horario' => 'vespertino', 'idioma' => 'es'],
            ['nombre' => 'Teatro', 'imagen' => '/images/teatro.jpg', 'plazas' => 25, 'edad_min' => 8, 'edad_max' => 99, 'horario' => 'matutino', 'idioma' => 'es'],
            ['nombre' => 'Fotografía', 'imagen' => '/images/fotografia.jpg', 'plazas' => 15, 'edad_min' => 16, 'edad_max' => 99, 'horario' => 'vespertino', 'idioma' => 'es'],
        ];

        $faker = Faker::create();
        $totalActividades = 50; // Define el número total de actividades que quieres

        // 1. Inserta una de cada actividad base (si no existen)
        foreach ($actividadesBase as $actividadData) {
            Activity::updateOrCreate(
                ['nombre' => $actividadData['nombre']], // Busca por nombre
                $actividadData // Actualiza o crea con todos los datos
            );
        }

        // 2. Calcula cuántas actividades aleatorias adicionales necesitas
        $numActividadesBase = count($actividadesBase);
        $numActividadesAdicionales = max(0, $totalActividades - $numActividadesBase);

        // 3. Inserta las actividades aleatorias adicionales
        for ($i = 0; $i < $numActividadesAdicionales; $i++) {
            // Selecciona una actividad aleatoria del array de actividades base
            $actividadAleatoria = $actividadesBase[array_rand($actividadesBase)];

            // Genera valores aleatorios para algunos campos (opcional)
            $plazasAleatorias = $faker->numberBetween(5, 40); // Plazas aleatorias entre 5 y 40

            // Crea la actividad adicional
            Activity::create([
                'nombre' => $actividadAleatoria['nombre'], // Usa el mismo nombre para que se considere la misma actividad
                'imagen' => $actividadAleatoria['imagen'],
                'plazas' => $plazasAleatorias,  // Usa un número de plazas aleatorio
                'edad_min' => $actividadAleatoria['edad_min'],
                'edad_max' => $actividadAleatoria['edad_max'],
                'horario' => $actividadAleatoria['horario'],
                'idioma' => $actividadAleatoria['idioma'],
            ]);
        }
    }
}