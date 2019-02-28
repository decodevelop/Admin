<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedidos_wix_importados extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero_pedido', 'numero_pedido_ps', 'codigo_factura', 'fecha_pedido', 'hora_pedido', 'cliente_facturacion'
		, 'pais_facturacion', 'estado_facturacion', 'ciudad_facturacion', 'direccion_facturacion'
		, 'cp_facturacion', 'cliente_envio', 'pais_envio', 'estado_envio', 'ciudad_envio'
		, 'direccion_envio', 'cp_envio', 'telefono_comprador', 'correo_comprador', 'metodo_entrega'
		, 'nombre_producto', 'variante_producto', 'sku_producto', 'cantidad_producto'
		, 'precio_producto', 'peso_producto', 'texto_especial_producto', 'cupon'
		, 'envio', 'tasas', 'total', 'entrada_principal', 'forma_de_pago', 'pago', 'orden_completada'
		, 'o_csv','enviado','estado_incidencia','mensaje_incidencia','creador_incidencia','historial_incidencia','observaciones',
		'created_at', 'updated_at'
    ];
	
}
