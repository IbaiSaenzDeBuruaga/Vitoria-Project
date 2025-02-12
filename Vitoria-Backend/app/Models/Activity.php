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
    ];

     /**
     * Define the relationship with users.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

     /**
     * Define the relationship with centroCivicos.
     *
     * @return BelongsToMany
     */
    public function centroCivicos(): BelongsToMany
    {
        return $this->belongsToMany(CentroCivico::class, 'activivity_centros', 'activity_id', 'centro_id')
            ->withPivot('fecha', 'horario_inicio')
            ->withTimestamps();
    }

}