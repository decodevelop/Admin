<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use View;
use Illuminate\Support\Facades\DB;
use App\Pedidos_wix_importados;
use App\User;
Use Validator;
use Input;
use DateTime;
use Mail;
use App;
use PDF;
use Dompdf\Dompdf;
use Excel;
use App\Incidencias_usuarios;

class IncidenciasController extends Controller
{
    /**
     * Constructor y middleware
     * @return void(true/false auth)
     */
    public function __construct()
    {

        $this->middleware('auth');
    }

	// **** IMPORTACIÓN ****

	/**
     * Muestra vista 'importar CSV' en /herramientas/importar_csv.blade.php
     * @return view
     */

     public function index(Request $request){

 		/*-------------- ORDENACIONES --------------*/
 		$getParams = $request->query();
 		if(isset($getParams["ob"],$getParams["obt"])){
 			$orderBy = $getParams["ob"];
 			$orderByType = $getParams["obt"];
 		} else {
 			$orderBy = "created_at";
 			$orderByType = "desc";
 			$orderBy2 = "numero_pedido";
 			$orderByType2 = "desc";
 		}

    if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
      $ocsvs = explode(',', $getParams["o_csv"] );
    }else{
      $ocsvs = '';
    }

    $where = "1=1 ";
    if(isset($getParams["id"]) && $getParams["id"]!="") $where .= " and id = ".$getParams["id"]."";
		//if(isset($getParams["o_csv"])&& $getParams["o_csv"]!="") $where .= " and o_csv = '".$getParams["o_csv"]."'";
    if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
      foreach ($ocsvs as $keyocsvs => $origen) {
        if($keyocsvs == 0){
          $where .= " and o_csv = '".$origen."'";
          if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
          if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
          if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
          if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
          if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
          if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
          if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
          if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."'";
          $where .= " and estado_incidencia <> 0";
        }else{
          $where .= " or o_csv = '".$origen."'";
          if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
          if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
          if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
          if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
          if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
          if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
          if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
          if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."'";
          $where .= " and estado_incidencia <> 0";
        }
      }
    }else{
      if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
      if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
      if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
      if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
      if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
      if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
      if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
      if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."'";
      $where .= " and estado_incidencia <> 0";

    }


 		$pedidos_agrupados = "";

 		// Variables de busqueda y retención.
 		$last_value = "";
 		$keyIndex = "";


 		/*-------------- QUERY MySQL -------------- orderBy("numero_pedido", "desc")-> */
 		$listado_pedidos = DB::table('pedidos_wix_importados')->whereRaw($where)->orderBy($orderBy,$orderByType)->orderBy($orderBy2,$orderByType)->paginate(20);


 		/*-------------- PROCESAR RESULTADO --------------*/

 		/* Obtenemos un array con los números de pedido para despues buscar el total de pedidos en el mismo id */
 		foreach($listado_pedidos as $key => $value) {

 			if($key==0){
 				$last_value = $value->o_csv.$value->numero_pedido;
 				$keyIndex = $key;
 			}

 			if($value->o_csv.$value->numero_pedido==$last_value && $key!=0){
 				//$repetidos++;
 				$listado_pedidos[$keyIndex]->nombre_producto .= ",".$value->nombre_producto;
 				$listado_pedidos[$keyIndex]->variante_producto .= ",".$value->variante_producto;
 				$listado_pedidos[$keyIndex]->sku_producto .= ",".$value->sku_producto;
 				$listado_pedidos[$keyIndex]->cantidad_producto .= ",".$value->cantidad_producto;
 				$listado_pedidos[$keyIndex]->precio_producto .= ",".$value->precio_producto;
        $listado_pedidos[$keyIndex]->id .= ",".$value->id;
 				unset($listado_pedidos[$key]);
 				continue;
 			} else {
 				//$norepetidos++;
 				$keyIndex = $key;
 				$last_value = $value->o_csv.$value->numero_pedido;
 			}

 		}

    //Array de los diferentes origenes

    $o_csv_array = array(
      'CA' => 'Cajasdemadera.com',
      'CB' => 'Cabeceros.com',
      'TT' => 'Tetedelit.fr',
      'CM' => 'Cajasdemadera.com (manual)',
      'CC' => 'Cabeceros.com (manual)',
      'TL' => 'Latetedelit.fr (manual)',
      'MA' => 'Milanuncios',
      'DT' => 'Descontalia',
      'IC' => 'Icommerce',
      'MV' => 'MiVinteriores',
      'HT' => 'Hogarterapia',
      'DA' => 'Decoratualma',
      'CJ' => 'Cojines.es',
      'CO' => 'Cojines.es (Manual)',
      'WL' => 'Wallapop',
      'AR' => 'Areas',
      'AM' => 'Amazon',
      'MM' => 'Mercado de María',
      'CR' => 'Carrefour',
      'AS' => 'Otros'
    );

 		return View::make('incidencias/inicio', array('listado_pedidos' => $listado_pedidos, 'filtros' => array($orderBy, $orderByType),  'o_csv_array' => $o_csv_array  ));
 	}

  /**
     * Actualiza con nuevos datos la incidencia del pedido especificado.
     * @return $mensaje_resultado
     */
    public function actualizar_incidencia($id, Request $request){

		$post = $request->all();
    $id_productos = explode(',',$post['productos_incidencia']);
    $descontado = false;
		try{
        foreach ($id_productos as $key => $id_producto) {
          $pedido = Pedidos_wix_importados::find($id_producto);
  				$pedido->estado_incidencia = $post['estado_incidencia'];
  				$pedido->mensaje_incidencia = $post['mensaje_incidencia'];
          $pedido->gestion_incidencia = $post['gestion_incidencia'];
          if(isset( $post['historial_incidencia'])){
            $pedido->historial_incidencia = $post['historial_incidencia'];

            /*if(($post['estado_incidencia']==2) && ($pedido->entrada_principal == '1' ) ){
              $pedido->total = $pedido->total - $post['historial_incidencia'];

            }else if($post['estado_incidencia']==2){

              $descontado = true;
              $pedido_descontado = Pedidos_wix_importados::where('numero_pedido','=',$pedido->numero_pedido)
                                                          ->where('entrada_principal','=','1')
                                                          ->get();

              $total_real= ($pedido_descontado[0]->total) - ($post['historial_incidencia']);
              $pedido_descontado[0]->total = $total_real;

              $numero_pedido = $pedido->numero_pedido;
              //dd($pedido_descontado[0]);
              $pedido_descontado[0]->save();
            }*/

          }
  				if($pedido->creador_incidencia==null){
  				$pedido->creador_incidencia = Auth::user()->id;
          }
  				$pedido->save();
        }

      /*if($post['estado_incidencia']==2){
        $pedidos_a_descontar = Pedidos_wix_importados::where('numero_pedido','=', $numero_pedido)
                                                      ->get();

        foreach ($pedidos_a_descontar as $y => $pedidos_descontados) {
          $pedidos_descontados->total = $total_real;

          $pedidos_descontados->save();
        }
      }*/
        return "Se ha actualizado correctamente el estado de la incidencia.";


		} catch(Exception $e){
			return "Ha habido un error durante la actualización, si el error persiste contacte con developer@decowood.es";
		}

	}

}
