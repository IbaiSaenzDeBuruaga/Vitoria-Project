<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'imagen',
        'plazas'
    ];



    public function activitiesCentros()
    {
         return $this->hasMany(ActivityCentro::class);
    }

}