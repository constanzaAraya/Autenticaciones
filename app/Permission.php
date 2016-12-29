<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
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
    public function aplicacion() {
        return $this->belongsToMany('App\Aplication')->withTimestamps();
    }
    public function cliente() {
        return $this->belongsToMany('App\Client')->withTimestamps();
    }*/
}