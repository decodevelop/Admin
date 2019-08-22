<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes_pedidos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'nombre_apellidos','email','telefono_facturacion',
         'email_facturacion','dni',  'telefono', 'nombre_envio', 'recurrente'
    ];

	 /**
     * Get the post that owns the comment.
     */
    public function direcciones()
    {
        return $this->hasMany('App\Direcciones', 'id_cliente');
    }
    public function pedidos()
    {
        return $this->hasMany('App\Pedidos','id_cliente');
    }
}
