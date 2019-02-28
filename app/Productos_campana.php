<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productos_campana extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'productos_campana';
    protected $fillable = [
      'id',	'id_producto','id_campana',	'comanda','restantes'
    ];

    public function campana()
    {
        return $this->belongsTo('App\Campanas','id_campana');
    }

    public function productos_palets()
    {
        return $this->hasMany('App\Productos_palets', 'id_producto_campana');
    }

    public function producto()
    {
        return $this->belongsTo('App\Productos_vp', 'id_producto');
    }

}
