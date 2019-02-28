<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acabados_productos_b extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'acabados_productos_b';
    protected $fillable = [
      'id',	'id_base','id_acabado'
    ];

    public function producto_base()
    {
        return $this->belongsTo('App\Productos_base','id_base');
    }

    public function acabado()
    {
        return $this->belongsTo('App\Acabados','id_acabado');
    }

}
