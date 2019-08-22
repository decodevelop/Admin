<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Direcciones extends Model
{

	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'direccion_envio','ciudad_envio','estado_envio','pais_envio','cp_envio',
        'direccion_facturacion','ciudad_facturacion','estado_facturacion',
        'pais_facturacion','cp_facturacion'
    ];
		protected $hidden = ['id_cliente'];

	 /**
     * Get the post that owns the comment.
     */
		 public function cliente()
 		{
 				return $this->belongsTo('App\Clientes','id_cliente');
 		}
}
