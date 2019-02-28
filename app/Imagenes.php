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
    protected $fillable = [
      'id',	'url','principal'
    ];

    public function producto_base()
    {
        return $this->belongsTo('App\Productos_base','id_base');
    }



}
