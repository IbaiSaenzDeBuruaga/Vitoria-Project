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
         return $this->hasMany(ActivityCentro::class);
    }
}