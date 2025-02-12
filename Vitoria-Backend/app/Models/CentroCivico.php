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
    public function activities(): BelongsToMany
    {
         return $this->belongsToMany(Activity::class, 'activivity_centros', 'centro_id', 'activity_id')
            ->withPivot('fecha', 'horario_inicio')
            ->withTimestamps();
    }
}