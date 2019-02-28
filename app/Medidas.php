<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medidas extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'largo', 'alto', 'ancho','peso', 'diametro' , 'largo_packagin', 'alto_packagin', 'ancho_packagin','peso_packagin', 'diametro_packagin'
    ];

    public function producto()
    {
      return $this->belongsTo('App\Productos','id_producto');
    }



}
