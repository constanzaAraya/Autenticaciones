<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permisos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'usuario_id',
        'aplicacion_id',
        'activo',
        'primerLogin'
    ];

    protected $visible = [
        'usuario_id',
        'aplicacion_id',
        'activo',
        'primerLogin'
    ];
    /*
    public function users()
    {
        return $this->morphToMany('App\User', 'permisos');
    }
    public function apps()
    {
        return $this->morphToMany('App\Aplication', 'permisos');
    }*/
}