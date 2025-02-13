<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityCentro extends Model
{
    //

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

    public function activity(){
        return $this->belongsTo(Activity::class,'id_activity','id');
    }
    public function centro(){
        return $this->belongsTo(CentroCivico::class,'id_centro','id');
    }
    public function activitiesUsers(){
        return $this->hasMany(ActivityUser::class);
    }


}
