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
        'id','nombre','email','telefono', 'plazo_entrega', 'envio',
        'metodo_pago', 'precio_esp_campana',
        'logistica', 'contrato', 'ultima_visita', 'observaciones', 'listo_para_vender'
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
