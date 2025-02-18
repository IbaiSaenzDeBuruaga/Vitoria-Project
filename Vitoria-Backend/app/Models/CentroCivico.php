<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CentroCivico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
    ];

     /**
     * Define the relationship with activities.
     *
     * @return BelongsToMany
     */
    public function activitiesCentros()
    {
        return $this->hasMany(ActivityCentro::class, 'centro_civico_id'); // Clave foránea en ActivityCentro
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_centro', 'centro_civico_id', 'activity_id')
                    ->withPivot('fecha_inicio', 'fecha_fin', 'hora_inicio', 'hora_fin', 'dias', 'precio', 'horario')
                    ->withTimestamps();
    }

    public static function seedCentroCivicos()
    {
        $centros = [
            ['nombre' => 'Abetxuko', 'direccion' => 'Dirección de Abetxuko'],
            ['nombre' => 'Aldabe', 'direccion' => 'Dirección de Aldabe'],
            ['nombre' => 'Arana', 'direccion' => 'Dirección de Arana'],
            ['nombre' => 'Ariznabarra', 'direccion' => 'Dirección de Ariznabarra'],
            ['nombre' => 'Arriaga', 'direccion' => 'Dirección de Arriaga'],
            ['nombre' => 'El Campillo', 'direccion' => 'Dirección de El Campillo'],
            ['nombre' => 'El Pilar', 'direccion' => 'Dirección de El Pilar'],
            ['nombre' => 'Hegoalde', 'direccion' => 'Dirección de Hegoalde'],
            ['nombre' => 'Ibaiondo', 'direccion' => 'Dirección de Ibaiondo'],
            ['nombre' => 'Iparralde', 'direccion' => 'Dirección de Iparralde'],
            ['nombre' => 'Judimendi', 'direccion' => 'Dirección de Judimendi'],
            ['nombre' => 'Lakua', 'direccion' => 'Dirección de Lakua'],
            ['nombre' => 'Salburua', 'direccion' => 'Dirección de Salburua'],
            ['nombre' => 'Zabalgana', 'direccion' => 'Dirección de Zabalgana'],
        ];

        foreach ($centros as $centroData) {
            CentroCivico::create($centroData);
        }
    }
}