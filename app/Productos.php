<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nombre_final', 'sku', 'descripcion','ean', 'asin'
    ];

    public function base()
    {
      return $this->belongsTo('App\Productos_base','id_base');
    }

    public function medida()
    {
        return $this->hasOne('App\Medidas', 'id_producto');
    }

    public function webs()
    {
        return $this->hasMany('App\Webs_productos', 'id_producto');
    }

    public function precio()
    {
        return $this->hasOne('App\Precios', 'id_producto');
    }

    public function stock()
    {
        return $this->hasOne('App\Stocks', 'id_producto');
    }

}
