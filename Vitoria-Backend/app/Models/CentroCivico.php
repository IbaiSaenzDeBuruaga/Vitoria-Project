<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentroCivico extends Model
{
    protected $fillable = ['nombre', 'direccion'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function actividades()
    {
        return $this->belongsToMany(Activity::class)
            ->using(ActivityCentro::class)
            ->withPivot('fecha', 'horario');
    }
}
