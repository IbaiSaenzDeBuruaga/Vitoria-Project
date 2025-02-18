<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject; // Importa la interfaz JWTSubject
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class User extends Authenticatable implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'primer_apellido',
        'segundo_apellido',
        'email',
        'password',
        'n_tarjeta',
        'n_barcos',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

     /**
     * Define the relationship with activities.
     *
     * @return BelongsToMany
     */


    public function activitiesUsers(){
        return $this->hasMany(ActivityUser::class);
    }

    public static function seedUsers()
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Admin',
            'primer_apellido' => 'AdminAp1',
            'segundo_apellido' => 'AdminAp2',
            'email' => 'i@i.com',
            'password' => Hash::make('adminibai'),
            'n_tarjeta' => '1234567890123456',
            'n_barcos' => 0,
            'rol' => 'admin', // o el rol que corresponda a administrador
        ]);

       
        
    }
}