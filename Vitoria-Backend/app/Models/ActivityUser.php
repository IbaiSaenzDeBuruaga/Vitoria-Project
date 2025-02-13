<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityUser extends Model
{
    //
    protected $fillable = [
        'id_user',
        'id_activity'
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function activity(){
        return $this->belongsTo(ActivityCentro::class);
    }

}
