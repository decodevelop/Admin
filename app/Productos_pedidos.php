<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productos_pedidos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_pedido','id_order_product','nombre','nombre_esp','variante','cantidad','peso','precio_final','precio_base','texto_especial_producto','ean','estado_proveedor','estado_envio','fecha_envio','antigua_id','albaran_generado'
    ];
    protected $hidden = ['id_pedido','id_transportista','id_proveedor'];

		 /**
     * Get the post that owns the comment.
     */
    public function pedido()
    {
        return $this->belongsTo('App\Pedidos','id_pedido');
    }
    public function transportista()
    {
        return $this->belongsTo('App\Transportistas','id_transportista');
    }
    public function proveedor()
    {
        return $this->belongsTo('App\Proveedores','id_proveedor');
    }
    public function productos_incidencias()
    {
        return $this->hasMany('App\Productos_incidencias', 'id_producto_pedido');
    }



}
