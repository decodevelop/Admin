<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productos_palets extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id',	'id_producto_campana','id_palet',	'cantidad'
    ];

    public function producto()
    {
        return $this->belongsTo('App\Productos_campana','id_producto_campana');
    }


    public function palet()
    {
        return $this->belongsTo('App\Palets', 'id_palet');
    }

}
