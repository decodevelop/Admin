<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id','nombre','descripcion'
    ];

    public function productos_base()
    {
        return $this->hasMany('App\Categorias_productos_b', 'id_categoria');
    }

}
