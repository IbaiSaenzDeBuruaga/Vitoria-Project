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
        'centro_id',
        'fecha_inicio',
        'fecha_fin',
        'hora_inicio',
        'hora_fin',
        'dias'
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function centroCivico()
    {
        return $this->belongsTo(CentroCivico::class);
    }

    public function users()
    {
        return $this->hasMany(ActivityUser::class, 'activity_id');
    }


}
