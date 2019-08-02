<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use View;
use Illuminate\Support\Facades\DB;
use App\Productos_vp;
use App\Campanas;
use App\Productos_campana;
use App\Palets;
use App\Productos_palets;
use App\Origen_pedidos;
use App\User;
Use Validator;
use Input;
use DateTime;
use Mail;
use App;
use PDF;
use Dompdf\Dompdf;
use Excel;
use \Milon\Barcode\DNS1D;
use Session;
use PHPExcel_Worksheet_Drawing;

class CampController extends Controller
{
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
  * Show the application dashboard.
  *
  * @return \Illuminate\Http\Response
  */

  public function inicio(Request $request){

    $campanas = Campanas::orderBy('id','DESC')->get();
    $origenes = Origen_pedidos::get();

    return View::make('campanas/inicio', array('campanas' => $campanas, 'origenes' => $origenes));
  }

  public function crear(){
    $origenes = Origen_pedidos::get();

    return View::make('campanas/crear', array('origenes' => $origenes));
  }

  public function crear_POST(Request $request){
    $ok = true;
    $errors = array();
    $success = array();
    //dd($request);

    // Validamos que el nombre no sea nulo.
    $nombre = $request->input('nombre');
    if(strlen($nombre) == 0) {
      $ok = false;
      array_push($errors,'Error: El campo Nombre no puede estar vacío.');
    }

    // Validamos que el nombre no este repetido.
    if(Campanas::where('nombre','=',$nombre)->exists()){
      $ok = false;
      array_push($errors,'Error: Ya existen Campañas con el nombre indicado.');
    }

    if($request['selectpickmult_origen'] == ''){
      $ok = false;
      array_push($errors, 'Error: El campo Origen és obligatorio.');
    }

    if($request['fecha_inicio'] > $request['fecha_fin']) {
      $ok = false;
      array_push($errors, 'Error: La Fecha de inicio no puede ser mayor a la Fecha final.');
    }

    $campana = new Campanas;
    $campana->referencia = $request['referencia'];
    $campana->nombre = $request['nombre'];
    $campana->fecha_inicio = $request['fecha_inicio'];
    $campana->fecha_fin = $request['fecha_fin'];
    $campana->total = $request['total'];
    $campana->origen_id = $request['selectpickmult_origen'];
    $campana->nombre_envio = $request['nombre_envio'];
    $campana->direccion_envio = $request['direccion_envio'];
    $campana->ciudad_envio = $request['ciudad_envio'];
    $campana->estado_envio = $request['estado_envio'];
    $campana->pais_envio = $request['pais_envio'];
    $campana->cp_envio = $request['cp_envio'];

    if($ok) { // Si todo está ok, igualamos los atributos y guardamos, por último volvemos a la vista de Acabados.
      if($campana->save()){
        array_push($success,'Campaña creada correctamente.');
      }

      $vaciar_form = new Campanas;
      Session::put('campanaErr',$vaciar_form);
      Session::put('success',$success);

      return back();

    } else { //Si algo no és correcto, enviamos los errores, el acabado erroneo y volvemos al formulario.
      Session::put('campanaErr',$campana);

      return back()->with(array('errors' => $errors));
    }
  }

  public function editar($id){
    $origenes = Origen_pedidos::get();
    $campana = Campanas::find($id);

    return View::make('campanas/editar', array('origenes' => $origenes, 'campana' => $campana));
  }

  public function editar_POST($id, Request $request){
    $ok = true;
    $errors = array();
    $success = array();
    $campana = Campanas::find($id);
    //dd($campana);

    // Validamos que el nombre no sea nulo.
    $nombre = $request->input('nombre');
    if(strlen($nombre) == 0) {
      $ok = false;
      array_push($errors,'Error: El campo Nombre no puede estar vacío.');
    }

    // Validamos que el nombre no este repetido.
    if(Campanas::where('nombre','=',$nombre)
    ->where('nombre','!=',$campana->nombre)
    ->exists()){
      $ok = false;
      array_push($errors,'Error: Ya existen Campañas con el nombre indicado.');
    }


    if($request['selectpickmult_origen'] == ''){
      $ok = false;
      array_push($errors, 'Error: El campo Origen és obligatorio.');
    }

    if($request['fecha_inicio'] > $request['fecha_fin']) {
      $ok = false;
      array_push($errors, 'Error: La Fecha de inicio no puede ser mayor a la Fecha final.');
    }

    if($ok) { // Si todo está ok, igualamos los atributos y guardamos, por último volvemos a la vista de Acabados.

      $campana->referencia = $request['referencia'];
      $campana->nombre = $request['nombre'];
      $campana->fecha_inicio = $request['fecha_inicio'];
      $campana->fecha_fin = $request['fecha_fin'];
      $campana->total = $request['total'];
      $campana->origen_id = $request['selectpickmult_origen'];
      $campana->nombre_envio = $request['nombre_envio'];
      $campana->direccion_envio = $request['direccion_envio'];
      $campana->ciudad_envio = $request['ciudad_envio'];
      $campana->estado_envio = $request['estado_envio'];
      $campana->pais_envio = $request['pais_envio'];
      $campana->cp_envio = $request['cp_envio'];

      if($campana->save()){
        array_push($success,'Cambios guardados correctamente.');
      }

      Session::put('success',$success);
      Session::put('campana',$campana);

      return back();

    } else { //Si algo no és correcto, enviamos los errores, el acabado erroneo y volvemos al formulario.
      return back()->with(array('errors' => $errors));
    }
  }

  public function eliminar($id){
  $campana = Campanas::find($id);
  $campana->delete();

  return redirect('campanas');
}

  public function viewProductos($id_campana,Request $request){
    /*-------------- ORDENACIONES --------------*/
    $getParams = $request->query();
    $orderBy2 = "id";
    $orderByType2 = "desc";
    /*-------------- FILTROS --------------*/
    $where = "1=1 ";
    if(isset($getParams["nombre"]) && $getParams["nombre"]!="") $where .= " and nombre like '%".$getParams["nombre"]."%'";
    if(isset($getParams["referencia"]) && $getParams["referencia"]!="") $where .= " and referencia like '%".$getParams["referencia"]."%'";
    if(isset($getParams["ean"]) && $getParams["ean"]!="") $where .= " and ean like '%".$getParams["ean"]."%'";

    $productos = Productos_campana::where('id_campana','=',$id_campana)
    ->whereHas('producto',  function ($query) use($where) {
      $query->whereRaw($where);
    })
    ->orderBy('restantes','desc')
    ->paginate(30);

    return View::make('campanas/productos', array('productos' => $productos, 'id_campana' => $id_campana));
  }

  public function generar_excel_campana($id_campana){



    return Excel::create('campaña_'.$id_campana, function($excel) use($id_campana) {
        $excel->sheet('Sheetname', function($sheet) use($id_campana) {
          // headers del documento xls
          $header = [];
          $row = 1;

          $header = array('Nombre' ,
                          'Referencia' ,
                          'Codigo EAN' ,
                          'largo',
                          'alto',
                          'ancho',
                          'Cantidad' );


          $productos = Productos_campana::where('id_campana','=',$id_campana)->get();

          foreach ($productos as $producto) {

              $row++;
              $product_excel = array(
                $producto->producto->nombre,
                $producto->producto->referencia,
                $producto->producto->ean,
                $producto->producto->largo,
                $producto->producto->alto,
                $producto->producto->ancho,
                $producto->comanda
              );
              $sheet->row($row, $product_excel);




          }
          $sheet->fromArray($header, null, 'A1', true);
        });

    })->export('xls');


  }

  public function viewPalets($id_campana,Request $request){

    $palets = Palets::where('id_campana','=',$id_campana)->paginate(40);

    return View::make('campanas/palets', array('palets' => $palets));
  }

  public function modificarPaletView($id_palet){

    $palet = Palets::find($id_palet);
    $palets = Palets::where('id_campana','=',$palet->id_campana)->get();

    $productos_campana_tabla = Productos_campana::where('id_campana','=',$palet->id_campana);

    return View::make('campanas/modificarPalets', array('palet' => $palet,
    'palets' => $palets));
  }

  public function cambioPalet(Request $request){
    $id_producto_palet = json_decode($request->all()["id_producto_palet"],true);
    $id_palet = json_decode($request->all()["id_palet_añadido"],true);

    //$palet = Palets::find($id_palet);
    $producto_palet = productos_palets::find($id_producto_palet);

    $producto_palet->id_palet = $id_palet;
    $producto_palet->save();

    return 'actualizado';

  }

  public function cargarProductosPalets(Request $request){
    $id_palet = json_decode($request->all()["id_producto_palet"],true);

    $palet = Palets::find($id_palet);
    $productos_nuevo_palet = '';
    foreach ($palet->productos_palets as $productos_palets){
      $productos_nuevo_palet .=  '<tr id="productoPalet_'.$productos_palets->id.'" class="producto_palet_fijo" >'
      .'<td>'.$productos_palets->producto->producto->nombre.'('.$productos_palets->cantidad.')</td></tr>';
    }
    $productos_nuevo_palet .= "<tr class='cambio_palet' ><td> <a href='/campanas/palets/modificar/".$palet->id."' class='btn btn-primary pull-right' >Modificar este palet</a> </td></tr>";
    return $productos_nuevo_palet;


  }

  public function cargarProductosCampana(Request $request){
    $buscar_producto = json_decode($request->all()["buscar_producto"],true);
    $id_campana = json_decode($request->all()["id_campana"],true);

    $productos_campana = Productos_campana::where('id_campana','=',$id_campana)
    ->where('restantes', '>', 0)
    ->whereHas('producto',  function ($query) use($buscar_producto) {
      $query->where('nombre','like', $buscar_producto.'%');
    })
    ->orderBy('restantes','desc')
    ->get();
    $productos =  "";

    foreach ($productos_campana as $producto_campana) {

      $productos .= '<tr class="ver_producto num-'.$producto_campana->id.'" id="num-'.$producto_campana->id.'">';
      $productos .= '<td class="table-check" style="display:none"><input type="checkbox" class="flat-red input_id_producto" name="producto" value="'.$producto_campana->id.'"></td>';
      $productos .= '<td style="width: 140px" class="td_nombre_producto"><div>'.$producto_campana->producto->nombre.'</div><input type="text" value="'.$producto_campana->producto->nombre.'" class="form-control" style="display:none"></td>';
      $productos .= '<td style="width: 10px" class="td_referencia_producto"><div>'.$producto_campana->producto->referencia.'</div><input type="text" value="'.$producto_campana->producto->referencia.'" class="form-control" style="display:none"></td>';
      $productos .= '<td style="width: 10px" class="td_codigoean_producto"><div>'.$producto_campana->producto->ean.'</div><input type="text" value="'.$producto_campana->producto->ean.'" class="form-control" style="display:none"></td>';
      $productos .= '<td style="width: 10px"> <div> '.$producto_campana->restantes.'('.$producto_campana->comanda.') </div> </td>';
      $productos .= '<td style="width: 10px"><input name="cantidad" type="number" placeholder="Cantidad" class="form-control input-md input_cantidad_producto" value="1" min="1" max="'.$producto_campana->restantes.'"></td>';
      $productos .= '<td><button type="button" class="btn btn-block btn-default btn-sm agregarCarrito"><i class="fa fa-plus"></i> Agregar</button></td>';
      $productos .= '</tr>';
    }
    $productos  .= '<script>agregarAPalet();</script>';
    return $productos;
  }

  public function eliminarProductoPalet(Request $request){

    $id_eliminar = json_decode($request->all()["id_producto_palet"],true);
    $producto_palet = productos_palets::find($id_eliminar);
    $producto_campana = $producto_palet->producto;
    //dd($producto_campana);

    $producto_campana->restantes += $producto_palet->cantidad;
    $producto_campana->save();
    $producto_palet->delete();

    return "Eliminado";
  }

  public function subirProductosVP(){

    $repetidos = array();
    Session::put('vpRepetidos', $repetidos);
    return View::make('herramientas.importar_excel_vp');


  }

  public function subirProductosVP_post(Request $request){
    $repetidos = array();
    $all_rep= true;
    $success= array();
    $errors = array();
    $subidos= 0;
    Session::put('vpSubidos', 0);

    Session::put('vpRepetidos', $repetidos);
    if(isset($request["inputSeguridad"]) && $request["inputSeguridad"]=='565dsad4874#@3sfasf' && $request['csv']!=NULL){ //Controlamos que no venga vacío el fichero
      // 1. Validar formato documento importado
      if(strtolower($request['csv']->getClientOriginalExtension())=='xls'){ //Controlamos la extensión del fichero.
        // 2. Generar nombre del fichero
        $nombreFichero = 'excel_vp_'.date('d-m-Y_H-i-s').'.'.$request->csv->getClientOriginalExtension();
        // 3. Subir fichero al directorio de archivos
        $request->csv->move(public_path('documentos/vp'), $nombreFichero);
        // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
        //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
        //* el cleanup aquí mismo al cargar un nuevo fichero.

        // Paso 5: Bucle donde iremos subiendo los productos uno a uno.

        //  $productosexistentes = Productos::get();

        $contHechos = 0;
        Excel::load('documentos/vp/'.$nombreFichero, function($archivo) use($repetidos, $all_rep, $subidos){

          $result=$archivo->get();
          foreach($result as $key => $value){

            if (DB::table('Productos_vp')->where('ean', '=', $value['ean'])->exists()){
              array_push($repetidos, $value['ean'].' = '.$value['nombre']);
            }else{

              $all_rep = false;
              $producto_vp = new Productos_vp;

              $producto_vp->id_vp = $value['id'];
              $producto_vp->nombre = $value['nombre'];
              $producto_vp->referencia = $value['sku'];
              $producto_vp->ean = $value['ean'];
              $producto_vp->largo = $value['largo'];
              $producto_vp->alto = $value['alto'];
              $producto_vp->ancho = $value['ancho'];

              $producto_vp->save();

              $subidos++;
            }

          }

          //dd($repetidos);
          Session::put('vpRepetidos', $repetidos);
          Session::put('vpSubidos', $subidos);

          //Session::push('vpRepetidos',);
          //dd(session('vpRepetidos'));

        })->get();

        $repetidos = session('vpRepetidos');
        $subidos = session('vpSubidos');

        array_push($success,'Fichero subido correctamente');

        if(count($repetidos)>0){
          array_push($success, count($repetidos).' codigo(s) EAN repetido(s), no se han subido');
        }
        if($subidos>0){
          array_push($success, $subidos.' Productos subidos correctamente');
        }
      }else array_push($errors,'Formato de fichero no válido.');
    }else array_push($errors,'No has subido fichero.');

    return View::make('herramientas/importar_excel_vp', array('errors' => $errors, 'success' => $success));
  }

  public function addPaletModificado(Request $request){
    $producto_guardar = json_decode($request->all()["productos_amazon_carrito"],true);

    //dd($producto_guardar);

    $productos_palets = new Productos_palets;

    $productos_palets->id_producto_campana = $producto_guardar[0]['id_producto'];
    $productos_palets->id_palet = $producto_guardar[0]['id_palet'];
    $productos_palets->cantidad = $producto_guardar[0]['cantidad_producto'];


    $producto_campana = Productos_campana::find($producto_guardar[0]['id_producto']);

    $producto_campana->restantes -= $producto_guardar[0]['cantidad_producto'];

    $producto_campana->save();
    $productos_palets->save();

    return "añadido";

  }

  public function guardarCarrito(Request $request){
    $producto_guardar = json_decode($request->all()["productos_amazon_carrito"],true);

    if (!Session::exists('productosPalet_'.$producto_guardar[0]['id_campana'])) Session::put('productosPalet_'.$producto_guardar[0]['id_campana'], Array());
    Session::push('productosPalet_'.$producto_guardar[0]['id_campana'],$producto_guardar);

    $carrito_final = array('');

    foreach (session('productosPalet_'.$producto_guardar[0]['id_campana']) as $key => $productoCarrito) {
      $carrito_final[0] .= '<li><div class="col-xs-10 textoLineaCarrito">'.$productoCarrito[0]["cantidad_producto"].' x '.$productoCarrito[0]["nombre_producto"].': '.$productoCarrito[0]["ean"].'</div>';
      $carrito_final[0] .= '<div class="col-xs-2"><div class="eliminarIndividualCampana '.$productoCarrito[0]['ean'].'"><i class="fa fa-times"></i></div></div></li>';
    }
    $carrito_final[0] .= '<script>eliminarIndividualCampana();</script>';
    $carrito_final[1] = count(session('productosPalet_'.$producto_guardar[0]['id_campana']));
    return $carrito_final;
  }

  public function eliminarIndividual($id_campana,$codigo_ean, Request $request){

    if(session()->exists('productosPalet_'.$id_campana) && count(session('productosPalet_'.$id_campana))>0){
      foreach (session('productosPalet_'.$id_campana) as $key => $productoCarrito) {
        if($productoCarrito[0]["ean"] == $codigo_ean){
          $array_provisional = session('productosPalet_'.$id_campana);
          array_splice($array_provisional, $key, 1);
          Session::put('productosPalet_'.$id_campana,$array_provisional);
          break;
        }
      }
    }
    return count(session('productosPalet_'.$id_campana));
  }

  public function guardarPalet($id_campana,Request $request){
    if(session()->exists('productosPalet_'.$id_campana) && count(session('productosPalet_'.$id_campana))>0){
      $productos_palet=session('productosPalet_'.$id_campana);
      foreach ($productos_palet as $key => $producto) {
        $productos_palet[$key] = $producto[0];
      }
      session()->forget('productosPalet_'.$id_campana);

      //dd($productos_palet);

      $campana = Campanas::find($id_campana);

      $palet = new Palets;
      $palet->id_campana = $id_campana;
      $palet->save();

      $numero_palet = str_pad($palet->id_campana, 2, "0", STR_PAD_LEFT).str_pad($palet->id, 4, "0", STR_PAD_LEFT);
      $palet->referencia = 'P_'.$campana->origen->referencia.$numero_palet;
      $palet->save();

      foreach ($productos_palet as $producto_palet) {
        $productos_palets = new Productos_palets;

        $productos_palets->id_producto_campana = $producto_palet['id_producto'];
        $productos_palets->id_palet = $palet->id;
        $productos_palets->cantidad = $producto_palet['cantidad_producto'];


        $producto_campana = Productos_campana::find($producto_palet['id_producto']);

        $producto_campana->restantes -= $producto_palet['cantidad_producto'];

        $producto_campana->save();
        $productos_palets->save();


      }
      return back()
      ->with('success', "Palet guardado correctamente con referencia = ".$palet->referencia. " <a href='/campanas/palets/".$id_campana."'>Ver palets</a>");
    }else{
      return back();
    }
  }

  public function subirVentasVP($id_campana){
    $repetidos = array();
    Session::put('vpRepetidos', $repetidos);
    return View::make('herramientas/importar_excel_vp');
  }

  public function subirVentasVP_post($id_campana,Request $request){
    $repetidos = array();
    $all_rep= true;
    $success= array();
    $errors = array();
    $subidos= 0;
    Session::put('vpSubidos', 0);

    Session::put('vpRepetidos', $repetidos);
    if(isset($request["inputSeguridad"]) && $request["inputSeguridad"]=='565dsad4874#@3sfasf' && $request['csv']!=NULL){ //Controlamos que no venga vacío el fichero
      // 1. Validar formato documento importado
      if(strtolower($request['csv']->getClientOriginalExtension())=='xls'){ //Controlamos la extensión del fichero.
        // 2. Generar nombre del fichero
        $nombreFichero = 'excel_vp_ventas_'.date('d-m-Y_H-i-s').'.'.$request->csv->getClientOriginalExtension();
        // 3. Subir fichero al directorio de archivos
        $request->csv->move(public_path('documentos/vp_ventas'), $nombreFichero);
        // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
        //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
        //* el cleanup aquí mismo al cargar un nuevo fichero.

        // Paso 5: Bucle donde iremos subiendo los productos uno a uno.

        //  $productosexistentes = Productos::get();

        $contHechos = 0;
        Excel::load('documentos/vp_ventas/'.$nombreFichero, function($archivo) use($repetidos, $all_rep, $subidos,$id_campana){

          $result=$archivo->get();

          foreach($result as $key => $value){
            $producto_vp = Productos_vp::where('id_vp','=', $value['vp_producto_id'])->first();
            //dd($value);
            if(!is_null($producto_vp)){
              if(!(Productos_campana::where('id_producto','=',$producto_vp->id)->where('id_campana','=',$id_campana)->where('comanda','=',$value['cantidad'])->exists())
              || ($value['referencia_tamano'] == 'Total:')){



                $all_rep = false;
                $producto_camp = new Productos_campana;
                $producto_camp->comanda = $value['cantidad'];
                $producto_camp->restantes = $value['cantidad'];

                $producto_camp->id_producto = $producto_vp->id;
                $producto_camp->id_campana = $id_campana;

                $producto_camp->save();

                $subidos++;
              }else{
                $repetidos++;
              }
            }
          }



          //dd($repetidos);
          Session::put('vpRepetidos', $repetidos);
          Session::put('vpSubidos', $subidos);

          //Session::push('vpRepetidos',);
          //dd(session('vpRepetidos'));

        })->get();

        $repetidos = session('vpRepetidos');
        $subidos = session('vpSubidos');

        array_push($success,'Fichero subido correctamente');

        if(count($repetidos)>0){
          array_push($success, count($repetidos).' codigo(s) EAN repetido(s), no se han subido');
        }
        if($subidos>0){
          array_push($success, $subidos.' Productos subidos correctamente');
        }
      }else array_push($errors,'Formato de fichero no válido.');
    }else array_push($errors,'No has subido fichero.');

    return View::make('herramientas/importar_excel_vp', array('errors' => $errors, 'success' => $success));
  }

  public function excelPalets($id_campana){

    $ruta_doc_xls = "documentos/palets/";
    $nombre_xls = "";

    return Excel::create('palets_campana_'.$id_campana, function($excel) use($id_campana) {
      $excel->sheet('Sheetname', function($sheet) use($id_campana) {
        // headers del documento xls
        $header = [];
        $row = 1;

        $header = array('Referencia' ,
        'Descripcion' ,
        'Codigo EAN' ,
        'Cantidad' ,
        'Referencia Palet');


        $palets = Palets::where('id_campana', '=', $id_campana)->get();

        foreach ($palets as $palet) {

          foreach ($palet->productos_palets as $productos_palets) {
            $row++;
            $product_excel = array(
              $productos_palets->producto->producto->referencia,
              $productos_palets->producto->producto->nombre,
              $productos_palets->producto->producto->ean,
              $productos_palets->cantidad,
              $palet->referencia
            );
            $sheet->row($row, $product_excel);

          }


        }
        $sheet->fromArray($header, null, 'A1', true);
      });

    })->export('xls');

  }

  public function generarEtiquetas($id_palet){
    $palet = Palets::find($id_palet);
    return View::make('campanas/imprimirEtiquetas', array('palet' => $palet));
  }

  public function generarAlbaran($id_palet, Request $request){
    $getParams = $request->query();
    $palet = Palets::find($id_palet);

    switch ($getParams['tamano']) {
      case 1:
      $palet->tamano= "1'20 x 0'80 x 1'80";
      break;
      case 2:
      $palet->tamano= "2'00 x 1'00 x 1'80";
      break;
      default:
      $palet->tamano= "2'20 x 1'00 x 1'80";
      break;
    }

    $palet->save();

    $campana = Campanas::find($palet->id_campana);
    $ruta_pdf = "documentos/albaranes/agrupados/";
    $nombre_pdf = "palet_".$palet->referencia;
    $view = "";

    $datos = array('palet' => $palet,
    'campana' => $campana);
    //return View::make('campanas.albaran', $datos);
    $view .= View::make('campanas.albaran', $datos)->render();

    $dompdf = new Dompdf();

    // Renderizamos view::make para poder generar pdf
    $dompdf->loadHtml($view);
    $dompdf->set_option('enable_css_float',true);

    // Renderizamos PDF
    $dompdf->render();
    $output = $dompdf->output();
    // Guardamos fichero y enviamos resultado al navegador
    file_put_contents($ruta_pdf.$nombre_pdf.".pdf", $output);

    return $dompdf->stream($nombre_pdf);

  }

  public function generarEtiquetasCampana($id_campana){
    $productos = Productos_campana::where('id_campana','=',$id_campana)->get();
    //dd($productos);
    return View::make('campanas/imprimirEtiquetasCampana', array('productos' => $productos));
  }

  public function eliminarPalet($id_palet){

    $palet = Palets::find($id_palet);
    foreach ($palet->productos_palets as $productos_palet) {

      $producto = Productos_campana::find($productos_palet->id_producto_campana);
      //dd($producto);
      $producto->restantes += $productos_palet->cantidad;
      $producto->save();
      $productos_palet->delete();


    }
    $palet->delete();

    return back()->with('success','Palet eliminado');

  }
}
