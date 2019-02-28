<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Colores extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id','nombre'
    ];

    public function productos_base()
    {
        return $this->hasMany('App\Colores_productos_b', 'id_color');
    }

}
