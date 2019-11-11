<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plazos_entrega extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'plazos_entrega';
    protected $fillable = [
      'id','nombre','dias'
    ];

    public function proveedores()
    {
        return $this->hasMany('App\Proveedores', 'id_plazo');
    }

}
