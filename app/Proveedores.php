<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre','email','telefono', 'plazo_entrega', 'plazo_entrega_check', 'envio', 'envio_check',
        'metodo_pago', 'metodo_pago_check', 'precio_esp_campana', 'precio_esp_campana_check',
        'logistica', 'logistica_check', 'contrato', 'contrato_check', 'observaciones'
    ];

		 /**
     * Get the post that owns the comment.
     */

    public function productos()
    {
        return $this->hasMany('App\Productos_pedidos', 'id_proveedor');
    }

    public function productos_base()
    {
        return $this->hasMany('App\Productos_base', 'id_proveedor');
    }




}
