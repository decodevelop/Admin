<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acabados extends Model
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
        return $this->hasMany('App\Acabados_productos_b', 'id_acabado');
    }

}
