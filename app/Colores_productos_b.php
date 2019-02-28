<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Colores_productos_b extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'colores_productos_b';
    protected $fillable = [
      'id',	'id_base','id_color'
    ];

    public function producto_base()
    {
        return $this->belongsTo('App\Productos_base','id_base');
    }

    public function color()
    {
        return $this->belongsTo('App\Colores','id_color');
    }

}
