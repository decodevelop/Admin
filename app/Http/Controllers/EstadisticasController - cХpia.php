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
use App\Origen_pedidos;

class EstadisticasController extends Controller{
    /**
     * Constructor y middleware
     * @return void(true/false auth)
     */
    public function __construct(){
        $this->middleware('auth');
    }

	// **** IMPORTACIÓN ****

	/**
     * Muestra vista 'importar CSV' en /herramientas/importar_csv.blade.php
     * @return view
     */
    public function inicio(Request $request){
    		/*-------------- ORDENACIONES --------------*/
    		$getParams = $request->query();
    		if(isset($getParams["ob"],$getParams["obt"])){
    			$orderBy = $getParams["ob"];
    			$orderByType = $getParams["obt"];
    		} else {
    			$orderBy = "id";
    			$orderByType = "desc";
    		}

    		/*-------------- FILTROS --------------*/
    		$where = "1=1 ";
    		if(isset($getParams["id"]) && $getParams["id"]!="") $where .= " and id = ".$getParams["id"]."";
    		if(isset($getParams["o_csv"])&& $getParams["o_csv"]!="") $where .= " and o_csv = '".$getParams["o_csv"]."'";
    		if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
    		if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
    		if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
    		if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido = '".$getParams["fecha_pedido"]."'";
    		if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
    		if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
    		if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."'";


    		$pedidos_agrupados = "";

    		// Variables de busqueda y retención.
    		$last_value = "";
    		$keyIndex = "";



    		/* VARIABLES GRAFICO */
    		$estadistica_preformateado_grafico = "";
    		$temp = 0;
    		/*-------------- QUERY MySQL -------------- orderBy("numero_pedido", "desc")-> */


    		// RAW estadistica semana actual - ingresos
    		$estadisticas_glob["semana"] = DB::select("select op.nombre, wix.fecha_pedido,DAYNAME(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color, round(sum(wix.historial_incidencia),2) as total_incidencias from pedidos_wix_importados as wix
    													inner join origen_pedidos as op on op.referencia = wix.o_csv
    													where entrada_principal = 1
    													and WEEK (wix.fecha_pedido) = WEEK(current_date) - 0 AND YEAR(wix.fecha_pedido) = YEAR(current_date)
    													group by op.grupo ORDER BY nombre desc");
        $productos_incidencia["semana"] = DB::select("select MAX(historial_incidencia) as suma
                              FROM `pedidos_wix_importados`
                              WHERE '0' NOT LIKE estado_incidencia AND historial_incidencia > 0 AND WEEK(fecha_pedido) = WEEK(current_date) AND YEAR(fecha_pedido)  = YEAR(current_date) GROUP BY o_csv, numero_pedido");
        //$productos_incidencia["semana"]["total"] = 0;
        $total =0;
        foreach ($productos_incidencia["semana"] as $o => $suma) {
            $total += $suma->suma;
        }
        $productos_incidencia["semana"]["total"] = $total;
        //dd($productos_incidencia["semana"]);
        //=====================================================
    		$estadisticas_glob["mes"] = DB::select("select op.nombre, wix.fecha_pedido,MONTH(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color, round(sum(wix.historial_incidencia),2) as total_incidencias from pedidos_wix_importados as wix
    													inner join origen_pedidos as op on op.referencia = wix.o_csv
    													where entrada_principal = 1
    													and MONTH(wix.fecha_pedido) = MONTH(current_date) - 0 AND YEAR(wix.fecha_pedido) = YEAR(current_date)
    													group by op.grupo ORDER BY nombre desc");
        $productos_incidencia["mes"] = DB::select("select MAX(historial_incidencia) as suma
                              FROM `pedidos_wix_importados`
                              WHERE '0' NOT LIKE estado_incidencia AND historial_incidencia > 0 AND MONTH(fecha_pedido) = MONTH(current_date) AND YEAR(fecha_pedido)  = YEAR(current_date) GROUP BY o_csv, numero_pedido");
        //$productos_incidencia["semana"]["total"] = 0;
        $total =0;
        foreach ($productos_incidencia["mes"] as $o => $suma) {
            $total += $suma->suma;
        }
        $productos_incidencia["mes"]["total"] = $total;
        //=====================================================
    		$estadisticas_glob["mesAnterior"] = DB::select("select op.nombre, wix.fecha_pedido,MONTH(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color, round(sum(wix.historial_incidencia),2) as total_incidencias from pedidos_wix_importados as wix
    													inner join origen_pedidos as op on op.referencia = wix.o_csv
    													where entrada_principal = 1
    													and MONTH(wix.fecha_pedido) = MONTH(current_date) - 1 AND YEAR(wix.fecha_pedido) = YEAR(current_date)
    													group by op.grupo ORDER BY nombre desc");
        $productos_incidencia["mesAnterior"] = DB::select("select MAX(historial_incidencia) as suma
                              FROM `pedidos_wix_importados`
                              WHERE '0' NOT LIKE estado_incidencia AND historial_incidencia > 0 AND MONTH(fecha_pedido) = MONTH(current_date) - 1 AND YEAR(fecha_pedido)  = YEAR(current_date) GROUP BY o_csv, numero_pedido");
        //$productos_incidencia["semana"]["total"] = 0;
        $total =0;
        foreach ($productos_incidencia["mesAnterior"] as $o => $suma) {
            $total += $suma->suma;
        }
        $productos_incidencia["mesAnterior"]["total"] = $total;
        //==========================================================
        $estadisticas_glob["mesAñoAnterior"] = DB::select("select op.nombre, wix.fecha_pedido,MONTH(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color, round(sum(wix.historial_incidencia),2) as total_incidencias from pedidos_wix_importados as wix
    													inner join origen_pedidos as op on op.referencia = wix.o_csv
    													where entrada_principal = 1
    													and MONTH(wix.fecha_pedido) = MONTH(current_date) AND YEAR(wix.fecha_pedido) = YEAR(current_date) -1
    													group by op.grupo ORDER BY nombre desc");
        $productos_incidencia["mesAñoAnterior"] = DB::select("select MAX(historial_incidencia) as suma
                              FROM `pedidos_wix_importados`
                              WHERE '0' NOT LIKE estado_incidencia AND historial_incidencia > 0 AND MONTH(fecha_pedido) = MONTH(current_date) AND YEAR(fecha_pedido)  = YEAR(current_date) - 1 GROUP BY o_csv, numero_pedido");
        //$productos_incidencia["semana"]["total"] = 0;
        $total =0;
        foreach ($productos_incidencia["mesAñoAnterior"] as $o => $suma) {
            $total += $suma->suma;
        }
        $productos_incidencia["mesAñoAnterior"]["total"] = $total;
        //=============================================================
    		$estadisticas_glob["año"] = DB::select("select op.nombre, wix.fecha_pedido,YEAR(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color, round(sum(wix.historial_incidencia),2) as total_incidencias from pedidos_wix_importados as wix
    													inner join origen_pedidos as op on op.referencia = wix.o_csv
    													where entrada_principal = 1
    													and YEAR(wix.fecha_pedido) = YEAR(current_date) - 0 AND YEAR(wix.fecha_pedido) = YEAR(current_date)
    													group by op.grupo ORDER BY total desc");
        $productos_incidencia["año"] = DB::select("select MAX(historial_incidencia) as suma
                              FROM `pedidos_wix_importados`
                              WHERE '0' NOT LIKE estado_incidencia AND historial_incidencia > 0 AND YEAR(fecha_pedido) = YEAR(current_date) GROUP BY o_csv, numero_pedido");
        //$productos_incidencia["semana"]["total"] = 0;
        $total =0;
        foreach ($productos_incidencia["año"] as $o => $suma) {
            $total += $suma->suma;
        }
        $productos_incidencia["año"]["total"] = $total;
        //=============================================================
    		$estadisticas_glob["añopasado"] = DB::select("select op.nombre, wix.fecha_pedido,YEAR(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color, round(sum(wix.historial_incidencia),2) as total_incidencias from pedidos_wix_importados as wix
    													inner join origen_pedidos as op on op.referencia = wix.o_csv
    													where entrada_principal = 1
    													and YEAR(wix.fecha_pedido) = YEAR(current_date)-1
    													group by op.grupo ORDER BY total desc");
        $productos_incidencia["añopasado"] = DB::select("select MAX(historial_incidencia) as suma
                              FROM `pedidos_wix_importados`
                              WHERE '0' NOT LIKE estado_incidencia AND historial_incidencia > 0 AND YEAR(fecha_pedido) = YEAR(current_date) - 1 GROUP BY o_csv, numero_pedido");
        //$productos_incidencia["semana"]["total"] = 0;
        $total =0;
        foreach ($productos_incidencia["añopasado"] as $o => $suma) {
            $total += $suma->suma;
        }
        $productos_incidencia["añopasado"]["total"] = $total;
        //===================================================================
        $estadisticas_glob["2años"] = DB::select("select op.nombre, wix.fecha_pedido,YEAR(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color, round(sum(wix.historial_incidencia),2) as total_incidencias from pedidos_wix_importados as wix
    													inner join origen_pedidos as op on op.referencia = wix.o_csv
    													where entrada_principal = 1
    													and YEAR(wix.fecha_pedido) = YEAR(current_date)- 2
    													group by op.grupo ORDER BY total desc");
        $productos_incidencia["2años"] = DB::select("select MAX(historial_incidencia) as suma
                              FROM `pedidos_wix_importados`
                              WHERE '0' NOT LIKE estado_incidencia AND historial_incidencia > 0 AND YEAR(fecha_pedido) = YEAR(current_date) - 2 GROUP BY o_csv, numero_pedido");
        //$productos_incidencia["semana"]["total"] = 0;
        $total =0;
        foreach ($productos_incidencia["2años"] as $o => $suma) {
            $total += $suma->suma;
        }
        $productos_incidencia["2años"]["total"] = $total;
        //===================================================================
        $estadisticas_glob["julio"] = DB::select("select op.nombre, wix.fecha_pedido,MONTH(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color, round(sum(wix.historial_incidencia),2) as total_incidencias from pedidos_wix_importados as wix
    													inner join origen_pedidos as op on op.referencia = wix.o_csv
    													where entrada_principal = 1
    													and MONTH(wix.fecha_pedido) = 7 AND YEAR(wix.fecha_pedido) = YEAR(current_date)
    													group by op.grupo ORDER BY nombre desc");





    		// RAW estadistica mes actual - ingresos
    		//$estadisticas_glob["mes"] = DB::select('select o_csv, round(sum(total),2) as total from  pedidos_wix_importados where entrada_principal = 1
    		//and MONTH (fecha_pedido) = MONTH(current_date) - 0 AND YEAR(fecha_pedido) = YEAR(current_date)
    		//group by o_csv');

    		// RAW estadistica año actual - ingresos
    		//$estadisticas_glob["año"] = DB::select('select o_csv, round(sum(total),2) as total from  pedidos_wix_importados where entrada_principal = 1
    		//and YEAR (fecha_pedido) = YEAR(current_date) - 0 AND YEAR(fecha_pedido) = YEAR(current_date)
    		//group by o_csv');

    		/* Estadística mensual */
    		$estadistica_mensual_final = array();
    		for ($i=0; $i < 12; $i++) {
    			$estadistica_mensual = DB::select("select op.nombre, wix.fecha_pedido,MONTH(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color from pedidos_wix_importados as wix inner join origen_pedidos as op on op.referencia = wix.o_csv where entrada_principal = 1  and MONTH(wix.fecha_pedido) = ".($i+1)." AND YEAR(wix.fecha_pedido) = YEAR(current_date) group by op.grupo ORDER BY nombre desc");
    			if(count($estadistica_mensual)>0){
    				$suma = 0;
    				foreach ($estadistica_mensual as $key => $pedido_act) {
    					$suma+=$pedido_act->total;
    				}

    				$estadistica_mensual_final[$i] = $suma;
    				unset($estadistica_mensual);
    			}else $estadistica_mensual_final[$i] = 0;
    		}
        /* Estadística mensual año anterior */
    		$estadistica_mensual_final_1 = array();
    		for ($i=0; $i < 12; $i++) {
    			$estadistica_mensual = DB::select("select op.nombre, wix.fecha_pedido,MONTH(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color from pedidos_wix_importados as wix inner join origen_pedidos as op on op.referencia = wix.o_csv where entrada_principal = 1  and MONTH(wix.fecha_pedido) = ".($i+1)." AND YEAR(wix.fecha_pedido) = YEAR(current_date)-1 group by op.grupo ORDER BY nombre desc");
    			if(count($estadistica_mensual)>0){
    				$suma = 0;
    				foreach ($estadistica_mensual as $key => $pedido_act) {
    					$suma+=$pedido_act->total;
    				}

    				$estadistica_mensual_final_1[$i] = $suma;
    				unset($estadistica_mensual);
    			}else $estadistica_mensual_final_1[$i] = 0;
    		}
        /* Estadística mensual 2 años anterior */
        $estadistica_mensual_final_2 = array();
        for ($i=0; $i < 12; $i++) {
          $estadistica_mensual = DB::select("select op.nombre, wix.fecha_pedido,MONTH(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color from pedidos_wix_importados as wix inner join origen_pedidos as op on op.referencia = wix.o_csv where entrada_principal = 1  and MONTH(wix.fecha_pedido) = ".($i+1)." AND YEAR(wix.fecha_pedido) = YEAR(current_date)-2 group by op.grupo ORDER BY nombre desc");
          if(count($estadistica_mensual)>0){
            $suma = 0;
            foreach ($estadistica_mensual as $key => $pedido_act) {
              $suma+=$pedido_act->total;
            }

            $estadistica_mensual_final_2[$i] = $suma;
            unset($estadistica_mensual);
          }else $estadistica_mensual_final_2[$i] = 0;
        }
    		/* Estadística anual - de 2017 a 2020 */
    		// 2016 está sumado a mano en la vista ya que no tenemos todos los pedidos en la bbdd.
    		$estadistica_anual_final = array();
    		$fecha_principio = 2017;
    		for ($j=0; $j < 5; $j++) {
    			$estadistica_anual = DB::select("select op.nombre, wix.fecha_pedido,MONTH(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color from pedidos_wix_importados as wix inner join origen_pedidos as op on op.referencia = wix.o_csv where entrada_principal = 1 AND YEAR(wix.fecha_pedido) = ".$fecha_principio." group by op.grupo ORDER BY nombre desc");

    			if(count($estadistica_anual)>0){
    				$suma = 0;
    				foreach ($estadistica_anual as $key2 => $anual_act) {
    					$suma+= $anual_act->total;
    				}

    				$estadistica_anual_final[$j] = $suma;
    				unset($estadistica_anual);
    			}else $estadistica_anual_final[$j] = 0;
    			$fecha_principio+=1;
    		}

    		/* Estadística global */
    		foreach($estadisticas_glob as $tipo_est =>  $estadistica){
    			foreach($estadistica as $posicion => $origen){
    					$estadistica_preformateado_grafico[$tipo_est][$posicion] = array( "value" => $origen->total, "color" => $origen->color, "highlight" => $origen->color, "label" => $origen->nombre, "value" => $origen->total );
    			}
    		}

    		/* Colores Quesito */
    		$coloresQuesito = [
    		"#0099ff",
    		"#ff0000",
    		"#00cc00",
    		"#ffff00",
    		"#ff6600",
    		"#ffc800",
    		"#999999",
    		"#003366",
    		"#003300",
    		"#b380ff",
    		"#000000",
    		"#bfff80",
    		"#b3d9ff",
    		"#ff9999",
    		"#66ffff",
    		"#993366",
    		"#ffff80",
        "#ffff80",
        "#ffff80",
        "#ffff80"

    		];

    		return View::make('estadisticas/inicio', array('estadisticas_glob' => $estadisticas_glob, 'flot' => $estadistica_preformateado_grafico, 'estadistica_mensual_final' => $estadistica_mensual_final,
        'estadistica_mensual_final_1' => $estadistica_mensual_final_1, 'estadistica_mensual_final_2' => $estadistica_mensual_final_2, 'estadistica_anual_final' => $estadistica_anual_final, 'coloresQuesito' => $coloresQuesito, 'productos_incidencia' => $productos_incidencia));
        }

    	/**
         * Carga las estadísticas de los pedidos.
         *
         * @return view
         */
    public function pedidos(){

      	/* ESTADÍSTICA MENSUAL */
  		$estadistica_mensual_final = array();
  		for ($i=0; $i < 12; $i++) {
  			$mes = date('m');

  			//Hacemos las consultas para sacar los pedidos entregados y el total de pedidos de cada mes.
  			$estadistica_mensual_total = DB::select("select count(*) as count from pedidos_wix_importados as wix inner join origen_pedidos as op on op.referencia = wix.o_csv where entrada_principal = 1  and MONTH(wix.fecha_pedido) = ".($i+1)." AND YEAR(wix.fecha_pedido) = YEAR(current_date)");
  			$estadistica_mensual_total = $estadistica_mensual_total[0];
  			$estadistica_mensual_entregados = DB::select("select count(*) as count from pedidos_wix_importados as wix inner join origen_pedidos as op on op.referencia = wix.o_csv where entrada_principal = 1  and MONTH(wix.fecha_pedido) = ".($i+1)." AND YEAR(wix.fecha_pedido) = YEAR(current_date) and enviado=1");
  			$estadistica_mensual_entregados = $estadistica_mensual_entregados[0];

  			//Comprobamos que tenemos la información y que el mes que estamos mirando es menor o igual al actual.
  			if(count($estadistica_mensual_total)>0 && count($estadistica_mensual_entregados)>0 && (($i+1)<=$mes)){
  				//Hacemos la operación para sacar el porcentaje de enviados.
  				$entregados = (($estadistica_mensual_entregados->count * 100)/$estadistica_mensual_total->count);
  				$estadistica_mensual_final[$i] = $entregados;
  			}else $estadistica_mensual_final[$i] = 0;
  		}

  		/* ESTADÍSTICA GENERAL */
          $entregados_enviados =  DB::select("select count(*) as count from pedidos_wix_importados where entrada_principal = 1 and enviado=1");
          $entregados_enviados = $entregados_enviados[0];
          $entregados_total_pedidos =  DB::select("select count(*) as count from pedidos_wix_importados where entrada_principal = 1");
          $entregados_total_pedidos = $entregados_total_pedidos[0];

          $entregados_general = (($entregados_enviados->count * 100)/$entregados_total_pedidos->count);

          /* ESTADÍSTICAS ANUALES */
          $fecha_principio = 2016;
          $estadistica_anual_final = array();

          for ($j=0; $j < 25; $j++) {
          	//Si la fecha que vamos a mirar es más grande que la actual, metemos el final a 0 y saltamos.
          	if(date("Y")<$fecha_principio) break;


          	//Hacemos las consultas.
          	$estadistica_anual_total = DB::select("select count(*) as count from pedidos_wix_importados as wix inner join origen_pedidos as op on op.referencia = wix.o_csv where entrada_principal = 1 AND YEAR(wix.fecha_pedido) = ".$fecha_principio."");
          	$estadistica_anual_total = $estadistica_anual_total[0];
          	$estadistica_anual_entregados = DB::select("select count(*) as count from pedidos_wix_importados as wix inner join origen_pedidos as op on op.referencia = wix.o_csv where entrada_principal = 1 AND YEAR(wix.fecha_pedido) = ".$fecha_principio." and enviado=1");
          	$estadistica_anual_entregados = $estadistica_anual_entregados[0];

          	//Miramos si tenemos respuestas y a continuación almacenamos la operación.
          	if(count($estadistica_mensual_total)>0 && count($estadistica_mensual_entregados)>0){
          		$entregados_anuales = (($estadistica_anual_entregados->count * 100)/$estadistica_anual_total->count);
          		$estadistica_anual_final[$fecha_principio] = $entregados_anuales;
          	}else $estadistica_anual_final[$fecha_principio] = 0;
          	$fecha_principio+=1;
          }


  		return View::make('estadisticas/pedidos', array('estadisticas_mensual' => $estadistica_mensual_final, 'entregados_general' => $entregados_general, 'estadistica_anual'=>$estadistica_anual_final));
	   }
    public function incidencias(Request $request){


      $date = getdate();
      $mes = $date['mon'];
      $any = $date['year'];
      $fecha = array( 'mes' => $mes, 'any' => $any);
      //dd($mes);
      $total_cabeceros = array('CB' => 0,
                              'CC' => 0);
      if(isset($request['mes']) && isset($request['any'])){
        $fecha['mes'] = $request['mes'];
        $fecha['any'] = $request['any'];
      }
      $origen_pedidos = DB::select('select * from `origen_pedidos`');
      //dd($origen_pedidos);
      //$o_csv_array = array('CA','CB','TT','FS','CM','CC','TL','FX','MA','DT','IC','MV','HT','DA','CJ','CO','WL','AR','AM','MM','CR','AS');

      /* Colores Quesito */
      //$coloresQuesito = ["#0099ff","#ff0000","#00cc00","#ffff00","#ff6600","#ffc800","#999999","#003366","#003300","#b380ff","#000000","#bfff80","#b3d9ff","#ff9999","#66ffff","#993366","#ffff80"];
      //========
      $productos_incidencia = DB::select("select id,MAX(historial_incidencia),o_csv,numero_pedido,cliente_facturacion,metodo_entrega,nombre_producto,precio_producto,estado_incidencia,mensaje_incidencia,gestion_incidencia,truncate((historial_incidencia/1.21) ,2) as historial_incidencia,fecha_pedido,cantidad_producto,MONTH(fecha_pedido)
        FROM `pedidos_wix_importados`
        WHERE '0' NOT LIKE estado_incidencia AND MONTH(fecha_pedido) =".$fecha['mes']." AND YEAR(fecha_pedido)  = ".$fecha['any']." GROUP BY o_csv,numero_pedido");

      $estadisticas_glob_mes = DB::select("select round(sum(wix.total),2) as total from pedidos_wix_importados as wix
    													inner join origen_pedidos as op on op.referencia = wix.o_csv
    													where entrada_principal = 1 and MONTH(wix.fecha_pedido) = ".$fecha['mes']." AND YEAR(wix.fecha_pedido)= ".$fecha['any']." group by op.grupo ORDER BY nombre desc");

        //dd($estadisticas_glob_mes);
        //dd($productos_incidencia);
        $facturacion_total = 0;
        foreach ($estadisticas_glob_mes as $key => $facturacion) {
          $facturacion_total += $facturacion->total;
        }
        $msg_incidencia = array('1' => array('tipo' => 'Rotura en transporte',
                                            'color' => '#9b9213'),
                                '2' => array('tipo' => 'Rotura en transporte por mal embalaje',
                                            'color' => '#fddc41'),
                                '3' => array('tipo' => 'Error de referencia',
                                            'color' => '#fb2b21'),
                                '4' => array('tipo' => 'Producto incompleto',
                                            'color' => '#fb4993'),
                                '5' => array('tipo' => 'Error de producción',
                                            'color' => '#942a71'),
                                '6' => array('tipo' => 'Fallo de documentación',
                                            'color' => '#308aa5'),
                                '7' => array('tipo' => 'Entrega fuera de plazo',
                                            'color' => '#2a8e6c'),
                                '8' => array('tipo' => 'No se ajusta a las necesidades del cliente',
                                            'color' => '#583112'),
                                '9' => array('tipo' => 'Error de compra',
                                            'color' => '#fb2348'));
        $msg_gestion = array('1' => array('tipo' => 'Devolución',
                                          'color' => '#f48b3a'),
                            '2' => array('tipo' => 'Reposición',
                                          'color' => '#09a8be'),
                            '3' => array('tipo' => 'Descuento por tara',
                                          'color' => '#53ba4f'));
        //dd($facturacion_total);
        $patron_otros = '/^Otros:/';
        $tabla_cantidad_valor = array();
        $cantidad_incidencias = array();
        $cantidad_gestion = array();

        //Creamos array para tabla de cantidad
        foreach ($origen_pedidos as $k => $o) {
          $tabla_cantidad_valor[$o->referencia] = array('cantidad' => 0,
                                                        'valor' => 0,
                                                        'color' => $o->color,
                                                        'nombre' => $o->nombre );
        }
        for ($i=0; $i < 10 ; $i++) {
          $cantidad_incidencias[$i] = 0 ;
        }
        $cantidad_incidencias['otros'] = 0;
        $cantidad_incidencias['total'] = 0;
        $cantidad_incidencias['solucionados'] = 0;
        for ($j=0; $j < 4 ; $j++) {
          $cantidad_gestion[$j] = 0 ;
        }
        $cantidad_gestion['otros'] = 0 ;
        $cantidad_gestion['total'] = 0 ;
        //dd($productos_incidencia);
        $product_ant =  DB::select("select id,historial_incidencia,o_csv,numero_pedido,cliente_facturacion,metodo_entrega,nombre_producto,precio_producto,estado_incidencia,mensaje_incidencia,gestion_incidencia,historial_incidencia,fecha_pedido,cantidad_producto,MONTH(fecha_pedido)
          FROM `pedidos_wix_importados`
          WHERE id = 237");
          $product_ant = $product_ant[0];
          //dd($product_ant);
        //rellenamos tabla de cantidad
        foreach ($productos_incidencia as $key => $producto) {

          /*if((($producto->o_csv != $product_ant->o_csv)&&($producto->numero_pedido != $product_ant->numero_pedido))){*/
            if(($producto->o_csv == 'CB') || ($producto->o_csv == 'CN')){
              $total_cabeceros['CB']++;
            }elseif ($producto->o_csv == 'CC') {
              $total_cabeceros['CC']++;
            }
            $tabla_cantidad_valor[$producto->o_csv]['cantidad']++;

            $tabla_cantidad_valor[$producto->o_csv]['valor'] += $producto->historial_incidencia;

            if(preg_match($patron_otros, $producto->mensaje_incidencia)){
              $cantidad_incidencias['otros']++;
            }else{

              $i_mens = explode(": ",$producto->mensaje_incidencia);

              if(array_key_exists($i_mens[0], $cantidad_incidencias)){
                $cantidad_incidencias[$i_mens[0]]++;
              }
            }

            if($producto->estado_incidencia != '1'){
              $cantidad_incidencias['solucionados']++;
            }

            if(preg_match($patron_otros, $producto->gestion_incidencia)){
              $cantidad_gestion['otros']++;
            }else{

              $i_ges = explode(": ",$producto->gestion_incidencia);

              if(array_key_exists($i_ges[0], $cantidad_gestion)){
                $cantidad_gestion[$i_ges[0]]++;
              }
            }
            $product_ant = $producto;
          /*}*/
        }

        //dd($tabla_cantidad_valor);
        //dd($total_cabeceros);
        foreach ($cantidad_incidencias as $r => $incidencia) {
          if($r != 'solucionados'){
            $cantidad_incidencias['total'] += $incidencia;
          }
        }
        foreach ($cantidad_gestion as $l => $gestion) {
          $cantidad_gestion['total'] += $gestion;
        }


        $facturacion_total = $facturacion_total/1.21;
       //dd($total_cabeceros);
       $datos_array= array('tabla_cantidad_valor' => $tabla_cantidad_valor,
                                                         'cantidad_incidencias' => $cantidad_incidencias,
                                                         'cantidad_gestion' => $cantidad_gestion,
                                                         'origen_pedidos' => $origen_pedidos,
                                                         'mensaje_incidencia' => $msg_incidencia,
                                                         'mensaje_gestion' => $msg_gestion,
                                                         'fecha' => $fecha,
                                                         'productos_incidencia' => $productos_incidencia,
                                                         'total_cabeceros' => $total_cabeceros,
                                                         'facturacion_total' => $facturacion_total);
       if(!$request['pdf']){
        return View::make('estadisticas/incidencia', $datos_array);
        }else{

          /* //dd($cantidad_gestion);
          $nombre_pdf = "incidencias_".$fecha['mes']."_".$fecha['any'];
          $view = View::make('estadisticas/incidencia_pdf', $datos_array)->render();
          $dompdf = new Dompdf();
          $dompdf->loadHtml($view);
          $dompdf->set_option('enable_css_float',true);

          // Renderizamos PDF
          $dompdf->render();
          // Enviamos resultado al navegador
          return $dompdf->stream($nombre_pdf); */
          $ruta_doc_xls = "documentos/albaranes/agrupados/";
          $nombre_xls = "";
          return Excel::create('incidencias', function($excel) use($datos_array) {
            $excel->getDefaultStyle()
                   ->getAlignment()
                   ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getDefaultStyle()
                   ->getAlignment()
                   ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->sheet('Valor de las incidencias', function($sheet) use($datos_array) {
              // headers del documento xls
              $header = [];
              $row = 1;

              //Crear headers
              $header_valor = array(
                'origen',
                'valor total',
                'cantidad'
              );

              //añadimos las rows

              //dd($productos_amazon);

             foreach ($datos_array['tabla_cantidad_valor'] as $key => $fila) {
                if($fila['cantidad'] > 0){
                    $filaFinal = Array();
                    //dd($fila);
                      $filaFinal['origen']= $fila['nombre'];
                      $filaFinal['valor']= $fila['valor'];
                      $filaFinal['cantidad']= $fila['cantidad'];

                    //dd($filaFinal);
                    $row++;
                    $sheet->row($row, $filaFinal);
                  }
              }


              $header = array_map('strtoupper', $header_valor);
              $sheet->fromArray($header_valor, null, 'A1', true);
              $sheet->getStyle("A1:D1")->getFont()->setBold(true);

            });

            $excel->sheet('Cantidad incidencias', function($sheet) use($datos_array) {
              // headers del documento xls
              $header = [];
              $row = 1;

              //Crear headers
              $header_cantidad = array(
                'incidencias',
                'cantidad',
                'porcentaje'
              );
              //añadimos las rows

              //dd($datos_array['cantidad_incidencias']);

             foreach ($datos_array['cantidad_incidencias'] as $key => $fila) {
                if($fila > 0){
                    $filaFinal = Array();
                    //dd($fila);
                    if(is_numeric($key)){
                      $filaFinal['incidencias']= $datos_array['mensaje_incidencia'][$key]['tipo'];
                    }else{
                      $filaFinal['incidencias']= $key;
                    }
                      $filaFinal['cantidad']= $fila;
                      $filaFinal['porcentaje']= (($fila * 100)/ $datos_array['cantidad_incidencias']['total']);

                    //dd($filaFinal);
                    $row++;
                    $sheet->row($row, $filaFinal);
                  }
              }


              $header = array_map('strtoupper', $header_cantidad);
              $sheet->fromArray($header_cantidad, null, 'A1', true);
              $sheet->getStyle("A1:D1")->getFont()->setBold(true);

            });

            $excel->sheet('Gestión incidencias', function($sheet) use($datos_array) {
              // headers del documento xls
              $header = [];
              $row = 1;

              //Crear headers
              $header_cantidad = array(
                'gestión',
                'cantidad',
                'porcentaje'
              );
              //añadimos las rows

              //dd($datos_array['cantidad_incidencias']);

             foreach ($datos_array['cantidad_gestion'] as $key => $fila) {
                if($fila > 0){
                    $filaFinal = Array();
                    //dd($fila);
                    if(is_numeric($key)){
                      $filaFinal['gestion']= $datos_array['mensaje_gestion'][$key]['tipo'];
                    }else{
                      $filaFinal['gestion']= $key;
                    }
                      $filaFinal['cantidad']= $fila;
                      $filaFinal['porcentaje']= (($fila * 100)/$datos_array['cantidad_gestion']['total']);
                    //dd($filaFinal);
                    $row++;
                    $sheet->row($row, $filaFinal);
                  }
              }


              $header = array_map('strtoupper', $header_cantidad);
              $sheet->fromArray($header_cantidad, null, 'A1', true);
              $sheet->getStyle("A1:D1")->getFont()->setBold(true);

            });

          })->export('xls');

        }
      }
    public function filtrarEstadisticas(Request $request){
      if(isset($request['mes']) && isset($request['any'])){
        $fecha['mes'] = $request['mes'];
        $fecha['any'] = $request['any'];
      }
      $origen_pedidos = DB::select('select * from `origen_pedidos`');
      $productos_incidencia = DB::select("select id, MAX(historial_incidencia),o_csv,numero_pedido,cliente_facturacion,metodo_entrega,nombre_producto,precio_producto,estado_incidencia,mensaje_incidencia,gestion_incidencia,historial_incidencia,fecha_pedido,cantidad_producto,MONTH(fecha_pedido)
        FROM `pedidos_wix_importados`
        WHERE '0' NOT LIKE estado_incidencia AND MONTH(fecha_pedido) =".$fecha['mes']." AND YEAR(fecha_pedido)  = ".$fecha['any']. " GROUP BY o_csv,numero_pedido");
      $tabla_cantidad_valor = array();
      //Creamos array para tabla de cantidad
      foreach ($origen_pedidos as $k => $o) {
        $tabla_cantidad_valor[$o->referencia] = array('cantidad' => 0,
                                                      'valor' => 0,
                                                      'color' => $o->color,
                                                      'nombre' => $o->nombre );
      }
      //rellenamos tabla de cantidad
      $valor_total_incidencias = 0;
      foreach ($productos_incidencia as $key => $producto) {
        $tabla_cantidad_valor[$producto->o_csv]['cantidad']++;
        $tabla_cantidad_valor[$producto->o_csv]['valor'] += $producto->historial_incidencia;
        $valor_total_incidencias += $producto->historial_incidencia;
      }


      $estadisticas_glob["mes"] = DB::select("select op.nombre, wix.fecha_pedido,MONTH(wix.fecha_pedido), round(sum(wix.total),2) as total, op.grupo , op.color as color from pedidos_wix_importados as wix
                            inner join origen_pedidos as op on op.referencia = wix.o_csv
                            where entrada_principal = 1
                            and MONTH(wix.fecha_pedido) = ".$fecha['mes']." - 0 AND YEAR(wix.fecha_pedido) = ".$fecha['any']."
                            group by op.grupo ORDER BY total desc");


      //dd($estadisticas_glob);
      return View::make('estadisticas/filtro', array('estadisticas_glob' => $estadisticas_glob,
                                                      'fecha' => $fecha,
                                                      'valor_total_incidencias' => $valor_total_incidencias));

    }

    public function productos(Request $request){
      if(isset($request['mes']) && isset($request['any'])){
        $fecha['mes'] = $request['mes'];
        $fecha['any'] = $request['any'];
      }else{
        $fecha['mes'] = date('m');
        $fecha['any'] = date('Y');
      }

      if(!isset($request['filtro-producto'])){
        $name_product = "";
        $where = "WHERE MONTH(wix.fecha_pedido) = ".$fecha['mes']." AND YEAR(wix.fecha_pedido) = ".$fecha['any']." and sku_producto != '' ";
        $where_all = "WHERE MONTH(wix.fecha_pedido) = ".$fecha['mes']." AND YEAR(wix.fecha_pedido) = ".$fecha['any']." ";
        $limit = "LIMIT 20";
      }else {
        $name_product = $request['filtro-producto'];
        $where = "WHERE MONTH(wix.fecha_pedido) = ".$fecha['mes']." AND YEAR(wix.fecha_pedido) = ".$fecha['any']." and sku_producto != ''  AND wix.nombre_producto like '%".$name_product."%' ";
        $where_all = "WHERE MONTH(wix.fecha_pedido) = ".$fecha['mes']." AND YEAR(wix.fecha_pedido) = ".$fecha['any']." AND wix.nombre_producto like '%".$name_product."%' and sku_producto != '' ";
        $limit = "LIMIT 30";
      }
      $productos_vendidos = DB::select("select wix.nombre_producto ,wix.sku_producto, wix.sku_producto, sum(wix.cantidad_producto) as cantidad ".
      "from pedidos_wix_importados as wix ".$where.
      "group by wix.sku_producto ORDER BY cantidad DESC ".$limit);

      $total_productos = DB::select("select sum(wix.cantidad_producto) as cantidad ".
      "from pedidos_wix_importados as wix ".$where_all.
      "ORDER BY cantidad DESC ");
      $total_productos = $total_productos[0]->cantidad;

      //dd($productos_vendidos);

      return View::make('estadisticas/productos', array('productos_vendidos' => $productos_vendidos,
                                                      'fecha' => $fecha,
                                                      'name_product' => $name_product,
                                                      'total_productos' => $total_productos));

    }

}
