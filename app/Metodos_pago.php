<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Metodos_pago extends Model
{
     protected $table = 'metodos_pago';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'url'
    ];

		 /**
     * Get the post that owns the comment.
     */
     public function pedidos()
     {
         return $this->hasMany('App\Pedidos','id_metodo_pago');
     }

}
