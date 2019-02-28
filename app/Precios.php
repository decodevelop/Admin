<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precios extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'precio_coste', 'precio_transporte', 'precio_b2b','pvp', 'descuento'
    ];

    public function producto()
    {
      return $this->belongsTo('App\Productos','id_producto');
    }



}
