<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = [
    'numero_pedido','numero_pedido_ps','referencia_ps','fecha_pedido','hora',
    'total','precio_envio','observaciones','bultos','tasas','cupon','codigo_factura'
  ];
  protected $hidden = ['origen_id','id_cliente','id_metodo_pago','id_direccion'];
  /**
  * Get the post that owns the comment.
  */
  public function productos()
  {
    return $this->hasMany('App\Productos_pedidos', 'id_pedido');
  }
  //falta seguimientos
  public function cliente()
  {
    return $this->belongsTo('App\Clientes_pedidos','id_cliente');
  }
  public function origen()
  {
    return $this->belongsTo('App\Origen_pedidos','origen_id');
  }
  public function metodo_pago()
  {
    return $this->belongsTo('App\Metodos_pago','id_metodo_pago');
  }

  public function direccion(){
    return $this->belongsTo('App\Direcciones', 'id_direccion');
  }


}
