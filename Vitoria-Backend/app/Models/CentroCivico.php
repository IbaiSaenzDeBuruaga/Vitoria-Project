<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentroCivico extends Model
{
    protected $fillable = [
        'nombre',
        'direccion',
    ];

    public function Activities(){
        return $this->hasMany(Activity::class);
    }
}
