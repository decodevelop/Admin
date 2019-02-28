<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productos_vp extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'productos_vp';
    protected $fillable = [
      'id','id_vp','nombre','referencia','ean'
    ];

    public function productos_campanas()
    {
        return $this->hasMany('App\Productos_campana', 'id_producto');
    }
}
