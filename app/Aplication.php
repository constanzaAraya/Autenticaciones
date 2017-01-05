<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Aplication extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'aplicaciones';
    
    protected $fillable = [
        'nombre',
        'alias',
        'url_app',
        'url_api'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    protected $visible = [
        'id',
        'nombre',
        'alias',
        'url_app',
        'url_api'
    ];
    
    public function users () {
        return $this->belongsToMany('App\User', 'permisos', 'aplicacion_id', 'usuario_id')->withTimestamps();
    }
}
