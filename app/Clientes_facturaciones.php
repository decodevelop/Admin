<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes_facturaciones extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "clientes_facturaciones";
    protected $fillable = [
         'telefono_facturacion', 'email_facturacion', 'direccion_facturacion', 'ciudad_facturacion', 'estado_facturacion' , 'pais_facturacion', 'cp_facturacion'
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
        return $this->hasMany('App\Pedidos','id_cliente_facturacion');
    }
}
