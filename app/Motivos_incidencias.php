<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Motivos_incidencias extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre','antigua_id','color'
    ];

		 /**
     * Get the post that owns the comment.
     */

     /*public function producto()
     {
         return $this->belongsTo('App\Productos_pedidos','id_producto_pedido');
     }*/


     public function incidencias()
     {
         return $this->hasMany('App\Incidencias', 'id_motivo');
     }


}
