<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incidencias extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero_incidencia','motivo_info','gestion_info','estado','cantidad_descontar'
    ];
    protected $hidden = ['id_motivo','id_gestion'];

		 /**
     * Get the post that owns the comment.
     */

     /*public function producto()
     {
         return $this->belongsTo('App\Productos_pedidos','id_producto_pedido');
     }*/

     public function productos_incidencias()
     {
         return $this->hasMany('App\Productos_incidencias', 'id_incidencia');
     }

     public function gestion()
     {
         return $this->belongsTo('App\Gestiones_incidencias', 'id_gestion');
     }

     public function motivo()
     {
         return $this->belongsTo('App\Motivos_incidencias', 'id_motivo');
     }


}
