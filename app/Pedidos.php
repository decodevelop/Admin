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

  public function fecha_de_salida_producto($producto){
    $dia_max = 0;

    if(isset($producto->proveedor)){
      if($producto->proveedor->plazo){
        $dia = $producto->proveedor->plazo->dias;
        if($dia > $dia_max) { $dia_max = $dia; }
      }

    }
    if(isset($producto->pedido)){
      return $this->sumasdiasemana($producto->pedido->fecha_pedido,$producto->pedido->hora,$dia_max);
    }

  }

  public function fecha_de_salida($productos = false){
    $dia_max = 0;
    if($productos){
      foreach ($productos as $producto) {
        if(isset($producto->proveedor)){
          if($producto->proveedor->plazo){
            $dia = $producto->proveedor->plazo->dias;
            //dd($dia);
            if($dia > $dia_max) { $dia_max = $dia; }
          }

        }

      }
    }

    return $this->sumasdiasemana($this->fecha_pedido,$this->hora,$dia_max);
  }

  private function sumasdiasemana($fecha,$hora,$dias){

    $festivos = new Festivos;
    $festivos = $festivos->todos();

    if($hora > "15:00"){
      $dias = $dias + 1;
    }

    //Timestamp De Fecha De Comienzo
    $comienzo = strtotime($fecha);

    //Inicializo la Fecha Final
    $fecha_venci_noti = $comienzo;


    $i = 0;
    $dias = $dias;
    while ($i < $dias) { //Le Sumo un Dia a La Fecha Final (86400 Segundos)
      $fecha_venci_noti += 86400;


        //Inicializo a FALSE La Variable Para Saber Si Es Feriado
        $es_festivo = FALSE;
        //Recorro Todos Los Feriados
        foreach ($festivos as $key => $festivo) {
          //Verifico Si La Fecha Final Actual Es Feriado O No
          if (date("Y-m-d", $fecha_venci_noti) === date("Y-m-d", strtotime($festivo->fecha))) {
            //En Caso de Ser feriado Cambio Mi variable A TRUE
            $es_festivo = TRUE;
          }
        }

       //Verifico Que No Sea Un Sabado, Domingo O Feriado
       //dd(date("Y-m-d", $fecha_venci_noti));
     if (!(date("w", $fecha_venci_noti) == 6 || date("w", $fecha_venci_noti) == 0 || $es_festivo)) {
          //En Caso De No Ser Sabado, Domingo O Feriado Aumentamos Nuestro contador
          $i++;
        }
      }

      return date('Y-m-d',$fecha_venci_noti);

}


}
