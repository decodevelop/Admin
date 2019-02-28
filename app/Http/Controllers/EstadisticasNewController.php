<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use View;
use Illuminate\Support\Facades\DB;
use App\Pedidos_wix_importados;
use App\Seguimiento_pedidos;
use App\Pedidos;
use App\Direcciones;
use App\Clientes_pedidos;
use App\Origen_pedidos;
use App\Metodos_pago;
use App\Proveedores;
use App\Transportistas;
use App\Incidencias;
use App\Productos_pedidos;
use App\Productos_incidencias;
use App\User;
use App\Motivos_incidencias;
use App\Gestiones_incidencias;
use App\PrestaShopWebservice;
Use Validator;
use Input;
use DateTime;
use Mail;
use App;
use PDF;
use Dompdf\Dompdf;
use Excel;

class EstadisticasNewController extends Controller
{
    /**
    * Constructor y middleware
    * @return void(true/false auth)
    */
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function estadisticas(){
      $any = date('Y');
      $origenes = Origen_pedidos::get();

      $estadisticas['semana'] = $this->getEstadisticas('WEEK (fecha_pedido) = WEEK(current_date) - 0 AND YEAR(fecha_pedido) = YEAR(current_date)');//FUNCION CON PARAMENTROS = query intervalo de fechas
      $estadisticas['mes'] = $this->getEstadisticas('MONTH(fecha_pedido) = MONTH(current_date) - 0 AND YEAR(fecha_pedido) = YEAR(current_date)');
      $estadisticas['mes_anterior'] = $this->getEstadisticas('MONTH(fecha_pedido) = MONTH(current_date) - 1 AND YEAR(fecha_pedido) = YEAR(current_date)');
      $estadisticas['mes_año_anterior'] = $this->getEstadisticas('MONTH(fecha_pedido) = MONTH(current_date) AND YEAR(fecha_pedido) = YEAR(current_date) -1');
      $estadisticas['año'] = $this->getEstadisticas('YEAR(fecha_pedido) = YEAR(current_date)');
      $estadisticas['año_anterior'] = $this->getEstadisticas('YEAR(fecha_pedido) = YEAR(current_date)-1');
      $estadisticas['2años_anteriores'] = $this->getEstadisticas('YEAR(fecha_pedido) = YEAR(current_date)- 2');

      $incidencias['semana'] = $this->getIncidencias('WEEK (fecha_incidencia) = WEEK(current_date) - 0 AND YEAR(fecha_incidencia) = YEAR(current_date)');//FUNCION CON PARAMENTROS = query intervalo de fechas
      $incidencias['mes'] = $this->getIncidencias('MONTH(fecha_incidencia) = MONTH(current_date) - 0 AND YEAR(fecha_incidencia) = YEAR(current_date)');
      $incidencias['mes_anterior'] = $this->getIncidencias('MONTH(fecha_incidencia) = MONTH(current_date) - 1 AND YEAR(fecha_incidencia) = YEAR(current_date)');
      $incidencias['mes_año_anterior'] = $this->getIncidencias('MONTH(fecha_incidencia) = MONTH(current_date) AND YEAR(fecha_incidencia) = YEAR(current_date) -1');
      $incidencias['año'] = $this->getIncidencias('YEAR(fecha_incidencia) = YEAR(current_date)');
      $incidencias['año_anterior'] = $this->getIncidencias('YEAR(fecha_incidencia) = YEAR(current_date)-1');
      $incidencias['2años_anteriores'] = $this->getIncidencias('YEAR(fecha_incidencia) = YEAR(current_date)- 2');

      for ($a=$any; $a > 2015 ; $a--) {
        $estadistica_anual_final[$a] = 0;
        for ($m=1; $m < 13 ; $m++) {
          $estadistica_mensual_final[$a][$m] = ($this->getEstadisticas('MONTH(fecha_pedido) = '.$m.' - 0 AND YEAR(fecha_pedido) = '.$a)['total'] - $this->getIncidencias('MONTH(fecha_incidencia) = '.$m.' - 0 AND YEAR(fecha_incidencia) = '.$a));
          if($a == 2016){
            $estadistica_anual_final[$a]=  481440.86;
          }else{
            $estadistica_anual_final[$a] += ($this->getEstadisticas('MONTH(fecha_pedido) = '.$m.' - 0 AND YEAR(fecha_pedido) = '.$a)['total'] - $this->getIncidencias('MONTH(fecha_incidencia) = '.$m.' - 0 AND YEAR(fecha_incidencia) = '.$a));
          }
        }
      }


      return View::make('estadisticas/inicioNew', array('estadisticas'  => $estadisticas,
                                                        'origenes'      => $origenes,
                                                        'incidencias'   => $incidencias,
                                                        'estadistica_mensual_final' => $estadistica_mensual_final,
                                                        'estadistica_anual_final' => $estadistica_anual_final));
    }

    private function getEstadisticas($queryRaw){

      //Origenes agrupamos por grupo
      $grupos_origenes = Origen_pedidos::groupBy('grupo')->get();
      $estadistica = array();
      $estadistica['total']= 0;
      //bucle con cada grupo
      foreach ($grupos_origenes as $grupo_origenes) {

        //Obtención de los origenes de cada grupo.
        $origenes = Origen_pedidos::where('grupo','=',$grupo_origenes->grupo)->get();

        //dd($origenes->find(1)->referencia);
        $estadistica_glob = Pedidos::where( function ($query) use($origenes) {
          foreach ($origenes as $origen) {
            $query->orWhere('origen_id','=',$origen->id);
          }
        })
        ->whereRaw($queryRaw)
        ->orderBy('fecha_pedido','DESC')
        ->get();

        if(!empty($estadistica_glob[0])){
          if($estadistica_glob->sum('total') > 0 ){
            $estadistica[$estadistica_glob[0]->origen_id] = $estadistica_glob->sum('total')/1.21;
            $estadistica['total'] += $estadistica[$estadistica_glob[0]->origen_id];
          }
        }
        //array_push($estadistica,$estadistica_glob);
      }
      arsort($estadistica);

      /*foreach ($estadistica as $key => $est) {
        $estadistica[$key] = number_format( $est , 2, ',', '.' );
      }*/
      return $estadistica;

    }

    private function getEstadisticasWeb($queryRaw){

      //Origenes agrupamos por grupo
      $grupos_origenes = Origen_pedidos::groupBy('grupo')->get();
      $estadistica = array();
      $estadistica['total']= 0;
      //bucle con cada grupo
      foreach ($grupos_origenes as $grupo_origenes) {

        //Obtención de los origenes de cada grupo.
        $origenes = Origen_pedidos::where('grupo','=',$grupo_origenes->grupo)
                                  ->whereNotNull('web')
                                  ->get();

        //dd($origenes->find(1)->referencia);
        $estadistica_glob = Pedidos::where( function ($query) use($origenes) {
          foreach ($origenes as $origen) {
            $query->orWhere('origen_id','=',$origen->id);
          }
        })
        ->whereRaw($queryRaw)
        ->orderBy('fecha_pedido','DESC')
        ->get();

        if(!empty($estadistica_glob[0])){
          if($estadistica_glob->sum('total') > 0 ){
            $estadistica[$estadistica_glob[0]->origen_id] = $estadistica_glob->sum('total')/1.21;
            $estadistica['total'] += $estadistica[$estadistica_glob[0]->origen_id];
          }
        }
        //array_push($estadistica,$estadistica_glob);
      }
      arsort($estadistica);

      /*foreach ($estadistica as $key => $est) {
        $estadistica[$key] = number_format( $est , 2, ',', '.' );
      }*/
      return $estadistica;

    }

    function transformPrice($number){
      return number_format( $number , 2, ',', '.' );
    }

    private function getIncidencias($queryRaw){

      $listado_incidencias = Incidencias::whereRaw($queryRaw)->get();
      $total_incidencias = 0;
      $numero_incidencia = 0;
      if(!is_null($listado_incidencias)){

        $total_incidencias = $listado_incidencias->sum('cantidad_descontar');

      }
      return $total_incidencias/1.21 ;
    }


    public function pedidos(Request $request){
      $date = getdate();
      $semana =  date('W');
      //$semana =  33;
      $any = $date['year'];
      $fecha = array( 'semana' => $semana, 'any' => $any);
      $origenes = Origen_pedidos::get();
      if(isset($request['filtro_fecha'])){
        $filtro_fecha = $request['filtro_fecha'];
        $filter_date = new DateTime($request['filtro_fecha']);

        $fecha['any'] =  $filter_date->format('Y');
        //$fecha['semana'] = $filter_date->format('W');
        $fecha['semana'] = $filter_date->format('W');

      }else{
          $filtro_fecha = null;
      }

      //TOTAL SEMANA ACTUAL.
      //Consulta = origen, suma total, color, web
      //Obtención de la facturación de pedidos total esta semana. Agrupamos origenes
      $pedidos = DB::select("SELECT  o.referencia, round(SUM(p.total/1.21),2) as 'total', count(p.origen_id) as 'pedidos' , o.color, o.web FROM `pedidos` p
      LEFT JOIN origen_pedidos o ON (p.origen_id = o.id)
      WHERE o.web is not null and WEEK (p.fecha_pedido,7) = ".$fecha['semana']." AND YEAR(p.fecha_pedido) = ".$fecha['any']."
      GROUP BY p.origen_id
      ORDER BY round(SUM(p.total),2) DESC");

      //Obtenemos las fechas de los pedidos de esta semana para poder relacionarlos.
      $dias_semana = DB::select("SELECT p.fecha_pedido FROM `pedidos` p
      WHERE WEEK (p.fecha_pedido,7) = ".$fecha['semana']." AND YEAR(p.fecha_pedido) = ".$fecha['any']."
      GROUP BY p.fecha_pedido
      ORDER BY p.fecha_pedido ASC");

      //TOTAL DIAS DE LA SEMANA ACTUAL.
      //Consulta = origen, suma total, color, web
      //Obtención de la facturación de pedidos total esta semana separados por dias. Agrupamos origenes y por día.
      $pedidos_dia_semana = DB::select("SELECT o.referencia, round(SUM(p.total/1.21),2) as 'total', count(p.origen_id) as 'pedidos' , o.color, o.web, p.fecha_pedido
      FROM `pedidos` p
      LEFT JOIN origen_pedidos o ON (p.origen_id = o.id)
      where o.web is not null and WEEK (p.fecha_pedido,7) = ".$fecha['semana']." AND YEAR(p.fecha_pedido) = ".$fecha['any']."
      GROUP BY p.fecha_pedido, p.origen_id
      ORDER BY p.fecha_pedido, round(SUM(p.total),2) DESC");

      //dd($pedidos);

      if($request['excel'] ){
        //dd($pedidos_dia_semana);
        $this->excel_pedidos($dias_semana,$pedidos_dia_semana);
      }

  		return View::make('estadisticas/pedidosNew', array('origenes' => $origenes,
                                                      'pedidos' => $pedidos,
                                                      'dias_semana' => $dias_semana,
                                                      'pedidos_dia_semana' => $pedidos_dia_semana,
                                                      'filtro_fecha' => $filtro_fecha));
    }


    private function excel_pedidos($dias_semana,$pedidos_dia_semana){

        $webs = DB::select("SELECT referencia, web FROM `origen_pedidos` where api_key IS NOT NULL");

         $ruta_doc_xls = "documentos/albaranes/agrupados/";
         $nombre_xls = "";
         return Excel::create('Pedidos', function($excel) use($dias_semana,$pedidos_dia_semana,$webs) {
           $excel->getDefaultStyle()
                  ->getAlignment()
                  ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $excel->getDefaultStyle()
                  ->getAlignment()
                  ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
           $excel->sheet('Dias semana', function($sheet) use($dias_semana,$pedidos_dia_semana,$webs) {
             // headers del documento xls
             $header = [];
             $row = 1;

             //Crear headers
             $header_valor = array('WEB');
             foreach ($dias_semana as $dia_semana) {
                 array_push($header_valor, $dia_semana->fecha_pedido );
                 array_push($header_valor, $dia_semana->fecha_pedido."-ventas" );
             }
             //añadimos las rows

             //dd($productos_amazon);
              $filaFinal = array();
              foreach ($webs as $web) {//Recorremos las webs
                $filaFinal = array('web' => $web->web);//añadimos la primera columna que será la url

                foreach ($dias_semana as $dia_semana) {//recorremos los dias de la semana para las siguientes columnas
                  $filaFinal[$dia_semana->fecha_pedido] = '';
                  $filaFinal[$dia_semana->fecha_pedido."-ventas"] = '';
                  foreach ($pedidos_dia_semana as $pedido_dia_semana) {
                    if($pedido_dia_semana->referencia == $web->referencia){//buscamos en el array de pedidos el que coincida con web y dia de la semana
                      if($pedido_dia_semana->fecha_pedido == $dia_semana->fecha_pedido){
                        $filaFinal[$dia_semana->fecha_pedido] = $pedido_dia_semana->total;//se añade a las columnas
                        $filaFinal[$dia_semana->fecha_pedido."-ventas"] = $pedido_dia_semana->pedidos;
                      }
                    }
                  }
                }
                //dd($filaFinal);
                $row++;

                $sheet->row($row, $filaFinal);//se añade la fila.
              }



             $header = array_map('strtoupper', $header_valor);
             $sheet->fromArray($header_valor, null, 'A1', true);
             $sheet->getStyle("A1:D1")->getFont()->setBold(true);

           });

         })->export('xls');
    }

    public function filtrarEstadisticas(Request $request){

      if(isset($request['mes']) && isset($request['any'])){
        $fecha['mes'] = $request['mes'];
        $fecha['any'] = $request['any'];
      }

      $origenes = Origen_pedidos::get();
      $estadisticas = $this->getEstadisticas('MONTH(fecha_pedido) = '.$fecha['mes'].' AND YEAR(fecha_pedido) = '.$fecha['any']);//FUNCION CON PARAMENTROS = query intervalo de fechas
      $incidencias = $this->getIncidencias('MONTH(fecha_incidencia) = '.$fecha['mes'].' AND YEAR(fecha_incidencia) = '.$fecha['any']);

      return View::make('estadisticas/filtroNew',  array('estadisticas'  => $estadisticas,
                                                        'origenes'      => $origenes,
                                                        'incidencias'   => $incidencias,
                                                        'fecha' => $fecha));
    }

    public function incidencias(Request $request){
      $date = getdate();
      $mes = $date['mon'];
      $any = $date['year'];
      $fecha = array( 'mes' => $mes, 'any' => $any);

      if(isset($request['mes']) && isset($request['any'])){
        $fecha['mes'] = $request['mes'];
        $fecha['any'] = $request['any'];
      }

      $origenes = Origen_pedidos::get();
      $motivos = Motivos_incidencias::get();
      $gestiones = Gestiones_incidencias::get();

  //   $tabla_cantidad_valor = $this->getIncidenciasOrigen('MONTH(fecha_incidencia) = '.$fecha['mes'].' - 0 AND YEAR(fecha_incidencia) = '.$fecha['any'].' AND DAY(fecha_incidencia)=11');
  //   $tabla_cantidad_valor = $this->getIncidenciasOrigen('MONTH(fecha_incidencia) = '.$fecha['mes'].' - 0 AND YEAR(fecha_incidencia) = '.$fecha['any'].' AND (DAY(fecha_incidencia)=16 OR DAY(fecha_incidencia)=17 OR DAY(fecha_incidencia)=14 OR DAY(fecha_incidencia)=13 OR DAY(fecha_incidencia)=12)');
  //     $tabla_cantidad_valor = $this->getIncidenciasOrigen('MONTH(fecha_incidencia) = '.$fecha['mes'].' - 0 AND YEAR(fecha_incidencia) = '.$fecha['any'].' AND (DAY(fecha_incidencia)=16 OR DAY(fecha_incidencia)=17 OR DAY(fecha_incidencia)=14 OR DAY(fecha_incidencia)=13 OR DAY(fecha_incidencia)=12 OR DAY(fecha_incidencia)=11 OR DAY(fecha_incidencia)=10 OR DAY(fecha_incidencia)=09 OR DAY(fecha_incidencia)=08 )');
 // $tabla_cantidad_valor = $this->getIncidenciasOrigen('DAY(fecha_incidencia)=11 OR DAY(fecha_incidencia)=12  ');
 $tabla_cantidad_valor = $this->getIncidenciasOrigen('MONTH(fecha_incidencia) = '.$fecha['mes'].' - 0 AND YEAR(fecha_incidencia) = '.$fecha['any']);

      $motivos_incidencias = $this->getIncidenciasMotivo('MONTH(fecha_incidencia) = '.$fecha['mes'].' - 0 AND YEAR(fecha_incidencia) = '.$fecha['any']);

      $gestiones_incidencias = $this->getIncidenciasGestion('MONTH(fecha_incidencia) = '.$fecha['mes'].' - 0 AND YEAR(fecha_incidencia) = '.$fecha['any']);

    //  echo "El mes será: ".$fecha['mes'];

      $total_cabeceros = $this->getTotalCabeceros('MONTH(fecha_pedido) = '.$fecha['mes'].' AND YEAR(fecha_pedido) = '.$fecha['any']);
      $total_facturacion_mes = $this->getEstadisticas('MONTH(fecha_pedido) = '.$fecha['mes'].' AND YEAR(fecha_pedido) = '.$fecha['any'])['total'];
      $productos_incidencia = $this->getProductosIncidencias('MONTH(fecha_pedido) = '.$fecha['mes'].' AND YEAR(fecha_pedido) = '.$fecha['any']);
      //dd($total_facturacion_mes);

      $datos_array= array('tabla_cantidad_valor' => $tabla_cantidad_valor,
                          'motivos_incidencias' => $motivos_incidencias,
                          'gestiones_incidencias' => $gestiones_incidencias,
                          'total_cabeceros' => $total_cabeceros,
                          'total_facturacion_mes' => $total_facturacion_mes,
                          'productos_incidencia' => $productos_incidencia,
                          'origenes' => $origenes,
                          'motivos' => $motivos,
                          'gestiones' => $gestiones,
                          'fecha'=>$fecha);
      // var_dump($tabla_cantidad_valor);

      if($request['excel'] ){
        //dd($pedidos_dia_semana);
        $this->excel_incidencias($datos_array);
      }

      return View::make('estadisticas/incidenciaNew', $datos_array);
    }

    private function excel_incidencias($datos_array){
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
         foreach ($datos_array['tabla_cantidad_valor'] as $origen_id => $fila_valor) {
            if($origen_id != 'total'){
                $filaFinal = Array();
                //dd($fila);
                  $filaFinal['origen']= $datos_array['origenes']->find($origen_id)->nombre;
                  $filaFinal['valor']= $fila_valor['valor'];
                  $filaFinal['cantidad']= $fila_valor['cantidad'];

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

          //dd($datos_array['mensaje_incidencia']);

         foreach ($datos_array['motivos_incidencias'] as $motivo_id => $fila_valor) {
            if ($motivo_id != 'total'){
                  $filaFinal = Array();
                  //dd($fila);
                  $filaFinal['incidencias'] =  $datos_array['motivos']->find($motivo_id)->nombre;
                  $filaFinal['cantidad']= $fila_valor['cantidad'];
                  $filaFinal['porcentaje']= number_format(($fila_valor['cantidad'] * 100)/$datos_array['motivos_incidencias']['total']['cantidad'], 2, ',', '.' );

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

         foreach ($datos_array['gestiones_incidencias'] as $gestion_id => $fila_valor) {
            if($gestion_id != 'total'){
                $filaFinal = Array();

                $filaFinal['gestion']=  $datos_array['gestiones']->find($gestion_id)->nombre;
                $filaFinal['cantidad']= $fila_valor['cantidad'];
                $filaFinal['porcentaje']= number_format(($fila_valor['cantidad'] * 100)/$datos_array['gestiones_incidencias']['total']['cantidad'] , 2, ',', '.' );

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

    private function getIncidenciasGestion($queryRaw){
      $gestiones = Gestiones_incidencias::get();
      $cantidad_incidencias['total']['cantidad'] = 0;
      foreach ($gestiones as $gestion) {
        $total = Incidencias::where('id_gestion','=',$gestion->id)
                            ->whereRaw($queryRaw)
                            ->count('id_motivo');

        if($total > 0){
          $cantidad_incidencias[$gestion->id]['cantidad'] = $total;
          $cantidad_incidencias['total']['cantidad'] += $total;
        }
      }

      return $cantidad_incidencias;
    }

    private function getIncidenciasMotivo($queryRaw){
      $motivos = Motivos_incidencias::get();
      $cantidad_incidencias['total']['cantidad'] = 0;
      foreach ($motivos as $motivo) {
        $total = Incidencias::where('id_motivo','=',$motivo->id)
                            ->whereRaw($queryRaw)
                            ->count('id_motivo');

        //dd($total);
        if($total > 0){
          $cantidad_incidencias[$motivo->id]['cantidad'] = $total;
          $cantidad_incidencias['total']['cantidad'] += $total;
        }
      }

      return $cantidad_incidencias;

    }

    private function getIncidenciasOrigen($queryRaw){

      $origenes = Origen_pedidos::get();
      $tabla_cantidad_valor['total']['valor']= 0;
      $tabla_cantidad_valor['total']['cantidad']= 0;


      foreach ($origenes as $origen) {
        $total = Incidencias::whereHas('productos_incidencias', function($query) use($origen){
                              $query->whereHas('producto',function($query) use($origen){
                                $query->whereHas('pedido',function($query) use($origen){
                                  $query->whereHas('origen',function($query) use($origen){
                                    $query->where('referencia','=',$origen->referencia);
                                  });
                                });
                              });
                            })->whereRaw($queryRaw)
                            ->sum('cantidad_descontar');

        $cantidad = Incidencias::whereHas('productos_incidencias', function($query) use($origen){
                              $query->whereHas('producto',function($query) use($origen){
                                $query->whereHas('pedido',function($query) use($origen){
                                  $query->whereHas('origen',function($query) use($origen){
                                    $query->where('referencia','=',$origen->referencia);
                                  });
                                });
                              });
                            })->whereRaw($queryRaw)
                              ->count('id');
        if($cantidad > 0){

          $tabla_cantidad_valor[$origen->id]['cantidad'] = $cantidad;
          $tabla_cantidad_valor['total']['cantidad'] += $cantidad;

          $tabla_cantidad_valor[$origen->id]['valor'] = $this->transformPrice($total/1.21);
          $tabla_cantidad_valor['total']['valor'] += $total;


        }
      }
      $tabla_cantidad_valor['total']['valor'] =  $this->transformPrice($tabla_cantidad_valor['total']['valor']/1.21);
      return $tabla_cantidad_valor;

    }

    private function getTotalCabeceros($queryRaw){
      $total_cabeceros['web'] = 0;
      $total_cabeceros['manual'] = 0;
      $cabeceros = Productos_pedidos::where('nombre_esp','like','%cabecero%')
                                    ->whereHas('pedido',function($query) use($queryRaw){
                                      $query->whereRaw($queryRaw);
                                    })
                                    ->get();
      $cabeceros_incidencias = Productos_incidencias::whereHas('producto', function($query) use($queryRaw){
                                                      $query->where('nombre_esp','like','%cabecero%')
                                                            ->whereHas('pedido',function($query) use($queryRaw){
                                                                $query->whereRaw($queryRaw);
                                                              });
                                                            })->count();

      $total_cabeceros['incidencia'] = $cabeceros_incidencias;
      foreach ($cabeceros as $cabecero) {
        if(!is_null($cabecero->pedido->origen->web)){
          $total_cabeceros['web']++;
        }else{
          $total_cabeceros['manual']++;
        }
      }
      //dd($total_cabeceros);
      return $total_cabeceros;
    }

    private function getProductosIncidencias($queryRaw){
      $productos_incidencia = Productos_incidencias::whereHas('producto', function($query) use($queryRaw){
                                                      $query->whereHas('pedido',function($query) use($queryRaw){
                                                                $query->whereRaw($queryRaw);
                                                              });
                                                            })->get();
      return $productos_incidencia;
    }
}
