<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productos_incidencias extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $fillable = [

    ];*/
    protected $hidden = ['id_producto_pedido','id_incidencia',''];

		 /**
     * Get the post that owns the comment.
     */

    public function producto()
    {
        return $this->belongsTo('App\Productos_pedidos','id_producto_pedido');
    }

    public function incidencia()
    {
        return $this->belongsTo('App\Incidencias','id_incidencia');
    }




}
