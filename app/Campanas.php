<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campanas extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id','referencia','nombre','fecha_inicio',	'fecha_fin',	'total','nombre_envio','direccion_envio','ciudad_envio','pais_envio','cp_envio'
    ];

    public function productos()
    {
        return $this->hasMany('App\Productos_campana', 'id_campana');
    }

    public function origen()
    {
        return $this->belongsTo('App\Origen_pedidos','origen_id');
    }

}
