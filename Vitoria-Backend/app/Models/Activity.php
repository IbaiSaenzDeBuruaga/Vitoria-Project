<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['nombre', 'imagen'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function centros_civicos(){
        return $this->belongsToMany(CentroCivico::class)
            ->using(ActivityCentro::class)
            ->withPivot('fecha', 'horario');
    }
}
