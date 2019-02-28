<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materiales extends Model
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
        return $this->hasMany('App\Materiales_productos_b', 'id_material');
    }

}
