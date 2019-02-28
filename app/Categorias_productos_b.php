<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorias_productos_b extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'categorias_productos_b';
    protected $fillable = [
      'id',	'id_base','id_categoria','principal'
    ];

    public function producto_base()
    {
        return $this->belongsTo('App\Productos_base','id_base');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Categorias','id_categoria');
    }

}
