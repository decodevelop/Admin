<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients_pedidos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "clients_pedidos";
    protected $fillable = [
         'nombre_apellidos','email','telefono_facturacion','email_facturacion','dni',  'telefono', 'nombre_envio', 'recurrente'
    ];

	 /**
     * Get the post that owns the comment.
     */
    public function envios()
    {
        return $this->hasMany('App\Clientes_envios', 'id_cliente');
    }
    public function facturaciones()
    {
        return $this->hasMany('App\Clientes_facturaciones','id_cliente');
    }
}
