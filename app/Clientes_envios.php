<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes_envios extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "clientes_envios";
    protected $fillable = [
         'nombre_envio', 'telefono_envio', 'dni_envio', 'email_envio', 'direccion_envio', 'ciudad_envio', 'estado_envio' , 'pais_envio', 'cp_envio'
    ];

	 /**
     * Get the post that owns the comment.
     */
    public function cliente()
    {
        return $this->belongsTo('App\Clients_pedidos', 'id_cliente');
    }
    public function pedidos()
    {
        return $this->hasMany('App\Pedidos','id_cliente_envio');
    }
}
