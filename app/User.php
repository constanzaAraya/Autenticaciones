<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'cargo',
        'departamento',
        'password',
        'token'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
     // protected $connection = 'mysql';
    protected $hidden = [
        'password',
        'token'
    ];

    protected $visible = [
        'id',
        'nombre',
        'apellido',
        'email',
        'cargo',
        'departamento'
    ];

    public function aplications () {
        return $this->belongsToMany('App\Aplication', 'permisos', 'usuario_id', 'aplicacion_id')->withPivot('activo', 'primerLogin');
    }/*
    public function permisos () {
        return $this->hasMany('App\Permission')->withTimestamps();
    }*/
}
