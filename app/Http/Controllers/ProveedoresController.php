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
use App\Proveedores;
use App\Rappels;
use App\Seguimiento_proveedores;
use App\Personal_proveedores;
use App\Horario_proveedores;
use App\Valoraciones_proveedores;
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

class ProveedoresController extends Controller
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
    $proveedores = Proveedores::where('nombre', '!=', 'NULL')->get();
    $seguimientos = Seguimiento_proveedores::where('destacado', '=', true)->get();
    $usuarios = User::get();

    return View::make('proveedores/inicio', array('proveedores' => $proveedores, 'seguimientos' => $seguimientos, 'usuarios' => $usuarios));
  }

  public function detalle($id){
    $proveedor = Proveedores::find($id);
    $rappel = Rappels::where('id_proveedor', '=', $id)->get();
    $personal = Personal_proveedores::where('id_proveedor', '=', $id)->get();
    $horario = Horario_proveedores::where('id_proveedor', '=', $id)->first();
    $seguimiento = Seguimiento_proveedores::where('id_proveedor','=',$id)->get();
    $valoraciones = Valoraciones_proveedores::where('id_proveedor','=',$id)->get();
    $usuarios = User::get();

    return View::make('proveedores/detalle', array('proveedor' => $proveedor,
    'rappel' => $rappel,
    'personal' => $personal,
    'horario' => $horario,
    'seguimiento' => $seguimiento,
    'valoraciones' => $valoraciones,
    'usuarios' => $usuarios));
  }

  public function nuevo(){
    return View::make('proveedores/nuevo');
  }

  public function nuevo_POST(Request $request){
    $ok = true;
    $errors = array();
    $success = array();
    $alerts = array();
    $inputs = $request->all();
    //dd($request['pers_cargo']);

    // Validamos que el nombre no sea nulo.
    $nombre = $request->input('nombre');
    if(strlen($nombre) == 0) {
      $ok = false;
      array_push($errors,'Error: El campo Nombre no puede estar vacío.');
    }

    // Validamos que el nombre no este repetido.
    if(Proveedores::where('nombre','=',$nombre)->exists()){
      $ok = false;
      array_push($errors,'Error: Ya existen Proveedores con el nombre indicado.');
    }

    if($request['vacaciones_inicio'] > $request['vacaciones_fin']) {
      $ok = false;
      array_push($errors, 'Error: La fecha de inicio de vacaciones no puede ser mayor a la fecha final.');
    }

    foreach ($request['pers_cargo'] as $key => $value) {
      if($request['pers_cargo'][$key] == '') {
        $ok = false;
        array_push($errors, 'Error: El campo cargo es obligatorio.');
        break;
      }
    }

    for ($i=0; $i < count($request['pers_cargo']); $i++) {
      $cont = 0;

      for ($j=0; $j < count($request['pers_cargo']); $j++) {
        if($request['pers_cargo'][$j] == $request['pers_cargo'][$i]){
          $cont = $cont + 1;

          if($cont == 2){
            $ok = false;
            array_push($errors,'Error: El nuevo Personal no puede contener el mismo cargo.');
            break 2;
          }
        }
      }
    }

    if($ok) {
      $proveedor = new Proveedores;
      $proveedor->nombre = $request['nombre'];
      $proveedor->email = $request['email'];
      $proveedor->telefono = $request['telefono'];
      $proveedor->plazo_entrega = $request['plazo_entrega'];
      $proveedor->plazo_entrega_web = $request['plazo_entrega_web'];
      $proveedor->envio = $request['envio'];
      $proveedor->metodo_pago = $request['metodo_pago'];
      $proveedor->precio_esp_campana = $request['precio_esp_campana'];
      $proveedor->logistica = $request['logistica'];
      $proveedor->contrato = $request['contrato'];
      $proveedor->observaciones = $request['observaciones'];
      $proveedor->ultima_visita = $request['ultima_visita'];
      $proveedor->vacaciones_inicio = $request['vacaciones_inicio'];
      $proveedor->vacaciones_fin = $request['vacaciones_fin'];

      if((strlen($proveedor->plazo_entrega) > 0) && (strlen($proveedor->envio) > 0) && (strlen($proveedor->metodo_pago) > 0)) {
        $proveedor->listo_para_vender = true;
      } else {
        $proveedor->listo_para_vender = false;
      }

      if($proveedor->save()){
        array_push($success,'Proveedor creado correctamente.');
      }

      // Subir PDF
      if(isset($inputs['contrato_pdf'])){
        $dir = 'contratos/'.$proveedor->id.'/';
        $nombreArchivo = $proveedor->id.'_contrato.pdf';

        if(\Storage::disk('pdfs')->put($dir.$nombreArchivo,  \File::get($inputs['contrato_pdf']))){
          array_push($success , 'Contrato subido correctamente.');
          $proveedor->contrato_pdf = true;
          $proveedor->save();

        } else {
          array_push($errors , 'Error al subir el contrato, por favor contacte con un desarrollador.');
        }
      }

      foreach ($request['condiciones'] as $key => $value) {
        if((strlen($request['condiciones'][$key]) > 0 && $request['condiciones'][$key] != "<div><br></div>") || (strlen($request['max'][$key]) > 0) || (strlen($request['min'][$key]) > 0)){

          $rappel = new Rappels;
          $rappel->id_proveedor = $proveedor->id;
          $rappel->condiciones = $request['condiciones'][$key];
          $rappel->max = $request['max'][$key];
          $rappel->min = $request['min'][$key];
          $rappel->save();
        }
      }

      foreach ($request['pers_cargo'] as $key => $value) {
        $personal = new Personal_proveedores;
        $personal->id_proveedor = $proveedor->id;
        $personal->cargo = $request['pers_cargo'][$key];
        $personal->nombre = $request['pers_nombre'][$key];
        $personal->correo = $request['pers_correo'][$key];
        $personal->telefono = $request['pers_telefono'][$key];
        $personal->save();
      }

      $horario = new Horario_proveedores;
      $horario->id_proveedor = $proveedor->id;
      $horario->lunes = $request['hor_lunes'];
      $horario->martes = $request['hor_martes'];
      $horario->miercoles = $request['hor_miercoles'];
      $horario->jueves = $request['hor_jueves'];
      $horario->viernes = $request['hor_viernes'];
      $horario->sabado = $request['hor_sabado'];
      $horario->domingo = $request['hor_domingo'];
      $horario->save();

      $vaciar_form = new Proveedores;
      Session::put('request',$vaciar_form);
      Session::put('success',$success);

      return back();

    } else {
      if(isset($inputs['contrato_pdf'])){
        array_push($alerts , 'Recuerde volver a subir los PDFs.');
      }

      Session::put('alerts',$alerts);

      $requestErr = $request->all();
      unset($requestErr['contrato_pdf']);
      //dd($requestErr);
      Session::put('request',$requestErr);
      return back()->with(array('errors' => $errors));
    }
  }

  public function modificar_proveedor($id){
    $proveedor = Proveedores::find($id);
    $horario = Horario_proveedores::where('id_proveedor', '=', $id)->first();
    //dd($horario);
    return View::make('proveedores/modificar_proveedor', array('proveedor' => $proveedor, 'horario' => $horario));
  }

  public function modificar_proveedor_POST($id, Request $request){
    $ok = true;
    $errors = array();
    $success = array();
    $alerts = array();
    $proveedor = Proveedores::find($id);
    //dd($campana);

    // Validamos que el nombre no sea nulo.
    $nombre = $request->input('nombre');
    if(strlen($nombre) == 0) {
      $ok = false;
      array_push($errors,'Error: El campo Nombre no puede estar vacío.');
    }

    // Validamos que el nombre no este repetido.
    if(Proveedores::where('nombre','=',$nombre)
    ->where('nombre','!=',$proveedor->nombre)
    ->exists()){
      $ok = false;
      array_push($errors,'Error: Ya existen Proveedores con el nombre indicado.');
    }

    if($request['vacaciones_inicio'] > $request['vacaciones_fin']) {
      $ok = false;
      array_push($errors, 'Error: La fecha de inicio de vacaciones no puede ser mayor a la fecha final.');
    }

    if($ok) {
      $proveedor->nombre = $request['nombre'];
      $proveedor->email = $request['email'];
      $proveedor->telefono = $request['telefono'];
      $proveedor->plazo_entrega = $request['plazo_entrega'];
      $proveedor->plazo_entrega_web = $request['plazo_entrega_web'];
      $proveedor->envio = $request['envio'];
      $proveedor->metodo_pago = $request['metodo_pago'];
      $proveedor->precio_esp_campana = $request['precio_esp_campana'];
      $proveedor->logistica = $request['logistica'];
      $proveedor->contrato = $request['contrato'];
      $proveedor->observaciones = $request['observaciones'];
      $proveedor->ultima_visita = $request['ultima_visita'];
      $proveedor->vacaciones_inicio = $request['vacaciones_inicio'];
      $proveedor->vacaciones_fin = $request['vacaciones_fin'];

      if((strlen($proveedor->plazo_entrega) > 0) && (strlen($proveedor->envio) > 0) && (strlen($proveedor->metodo_pago) > 0)) {
        $proveedor->listo_para_vender = true;
      } else {
        $proveedor->listo_para_vender = false;
      }

      // Horario
      $horario = Horario_proveedores::where('id_proveedor', '=', $id)->first();

      if($horario == null){
        $horario = new Horario_proveedores;
      }

      $horario->id_proveedor = $id;
      $horario->lunes = $request['hor_lunes'];
      $horario->martes = $request['hor_martes'];
      $horario->miercoles = $request['hor_miercoles'];
      $horario->jueves = $request['hor_jueves'];
      $horario->viernes = $request['hor_viernes'];
      $horario->sabado = $request['hor_sabado'];
      $horario->domingo = $request['hor_domingo'];
      $horario->save();

      // Subir PDF
      if(isset($request['contrato_pdf'])){
        $dir = 'contratos/'.$proveedor->id.'/';
        $nombreArchivo = $proveedor->id.'_contrato.pdf';

        if(\Storage::disk('pdfs')->put($dir.$nombreArchivo,  \File::get($request['contrato_pdf']))){
          array_push($success , 'Contrato subido correctamente.');
          $proveedor->contrato_pdf = true;

        } else {
          array_push($errors , 'Error al subir el contrato, por favor contacte con un desarrollador.');
        }
      }

      if($proveedor->save()){
        array_push($success,'Proveedor actualizado correctamente.');
      }

      Session::put('success',$success);
      Session::put('proveedor',$proveedor);

      return back();

    } else {
      if(isset($request['contrato_pdf'])){
        array_push($alerts , 'Recuerde volver a subir los PDFs.');
      }

      Session::put('alerts',$alerts);

      return back()->with(array('errors' => $errors));
    }
  }

  public function modificar_rappel($id_proveedor, $id_rappel){
    $rappel = Rappels::find($id_rappel);
    $proveedor = Proveedores::find($id_proveedor);

    return View::make('proveedores/modificar_rappel', array('rappel' => $rappel, 'proveedor' => $proveedor));
  }

  public function modificar_rappel_POST($id_proveedor, $id_rappel, Request $request){
    $success = array();

    $rappel = Rappels::find($id_rappel);
    $rappel->condiciones = $request['condiciones'];
    $rappel->max = $request['max'];
    $rappel->min = $request['min'];

    if($rappel->save()){
      array_push($success,'Rappel actualizado correctamente.');
    }

    Session::put('success',$success);
    //Session::put('proveedor',$proveedor);

    return back();
  }

  public function nuevo_rappel($id_proveedor){
    $proveedor = Proveedores::find($id_proveedor);

    return View::make('proveedores/nuevo_rappel', array('proveedor' => $proveedor));
  }

  public function nuevo_rappel_POST($id_proveedor, Request $request){
    $success = array();

    if((strlen($request['condiciones']) > 0 && $request['condiciones'] != "<div><br></div>") || (strlen($request['max']) > 0) || (strlen($request['min']) > 0)){
      $rappel = new Rappels;
      $rappel->id_proveedor = $id_proveedor;
      $rappel->condiciones = $request['condiciones'];
      $rappel->max = $request['max'];
      $rappel->min = $request['min'];

      if($rappel->save()){
        array_push($success,'Rappel añadido correctamente.');
      }
    }

    Session::put('success',$success);
    //Session::put('proveedor',$proveedor);

    return back();
  }

  function eliminar_rappel($id_proveedor, $id_rappel){
    $rappel = Rappels::find($id_rappel);
    $rappel->delete();

    return redirect('/proveedores/detalle/'.$id_proveedor);
  }

  public function seguimiento_proveedores($id, Request $request){
    $post = $request->all();

    try {
      $proveedor = Proveedores::find($id);
      $seguimiento = new Seguimiento_proveedores;

      $seguimiento->id_proveedor =  $proveedor->id;
      $seguimiento->mensaje = $post["comentario_seguimiento"];
      //$seguimiento->created_at = date('Y-m-d H:i:s');
      $seguimiento->id_usuario = Auth::user()->id;
      //dd($seguimiento);
      $seguimiento->save();

    } catch(Exception $e) {
      return "No se ha podido actualizar, contactar con el administrador developer@decowood.es";
    }

    return "Actualizado.";
  }

  public function seguimiento_destacado($id, Request $request) {
    try {
      $seguimientos = Seguimiento_proveedores::where('id_proveedor','=',$id)->get();

      foreach ($seguimientos as $s) {
        $s->destacado = false;
        $s->save();
      }

      $seguimiento = Seguimiento_proveedores::find($request['destacado']);
      $seguimiento->destacado = true;
      $seguimiento->save();
    } catch(Exception $e) {
      return "No se ha podido actualizar, contactar con el administrador developer@decowood.es";
    }
    return "Actualizado.";
  }

  public function valoracion_proveedores($id, Request $request){
    $post = $request->all();
    //dd($post);

    try {
      $proveedor = Proveedores::find($id);
      $valoracion = new Valoraciones_proveedores;

      $valoracion->id_proveedor =  $proveedor->id;
      $valoracion->comentario = $post["comentario_valoracion"];
      $valoracion->puntuacion = $post["puntuacion"];
      //$valoracion->created_at = date('Y-m-d H:i:s');
      $valoracion->id_usuario = Auth::user()->id;
      //dd($valoracion);
      $valoracion->save();

      $valoraciones = Valoraciones_proveedores::where('id_proveedor', '=', $id)->get();
      $total = 0;

      foreach ($valoraciones as $v) {
        $total += $v->puntuacion;
      }

      $proveedor->valoracion_media = $total / count($valoraciones);
      $proveedor->save();

    } catch(Exception $e) {
      return "No se ha podido actualizar, contactar con el administrador developer@decowood.es";
    }

    return "Actualizado.";
  }

  function subirPDF($dir, $nombreArchivo, $file){
    /*try {
    if(file_exists($dir.$nombreArchivo)){ //Si ya existe un pdf, lo borramos
    unlink($dir.$nombreArchivo);

  } else { //Si no, comprobamos que exista la carpeta y la creamos.
  if(!file_exists($dir)){
  mkdir($dir, 0777, true);
}
}

if (move_uploaded_file($_FILES[$file]['tmp_name'], $dir.$nombreArchivo)) {
return true;

} else {
return false;
}

} catch(Exception $e){
return false;
}*/
}

public function nuevo_personal($id_proveedor){
  $proveedor = Proveedores::find($id_proveedor);

  return View::make('proveedores/nuevo_personal', array('proveedor' => $proveedor));
}

public function nuevo_personal_POST($id_proveedor, Request $request){
  $ok = true;
  $errors = array();
  $success = array();
  $alerts = array();
  $inputs = $request->all();
  //dd($request);

  if($request['pers_cargo'] == '') {
    $ok = false;
    array_push($errors, 'Error: El campo Cargo es obligatorio.');
  }

  $personalExist = Personal_proveedores::where('id_proveedor','=',$id_proveedor)->get();

  foreach ($personalExist as $p) {
    if($p->cargo == $request['pers_cargo']){
      $ok = false;
      array_push($errors,'Error: Ya existe Personal con el cargo indicado.');
      break;
    }
  }

  if($ok) {

    $personal = new Personal_proveedores;
    $personal->id_proveedor = $id_proveedor;
    $personal->cargo = $request['pers_cargo'];
    $personal->nombre = $request['pers_nombre'];
    $personal->correo = $request['pers_correo'];
    $personal->telefono = $request['pers_telefono'];

    if($personal->save()){
      array_push($success, 'Personal creado correctamente.');
    }

    $vaciar_form = new Personal_proveedores;
    Session::put('request',$vaciar_form);
    Session::put('success',$success);

    return back();

  } else {
    Session::put('alerts',$alerts);

    $requestErr = $request->all();
    //dd($requestErr);
    Session::put('request',$requestErr);
    return back()->with(array('errors' => $errors));
  }
}

public function modificar_personal($id_proveedor, $id_personal){
  $personal = Personal_proveedores::find($id_personal);
  $proveedor = Proveedores::find($id_proveedor);

  return View::make('proveedores/modificar_personal', array('personal' => $personal, 'proveedor' => $proveedor));
}

public function modificar_personal_POST($id_proveedor, $id_personal, Request $request){
  $ok = true;
  $errors = array();
  $success = array();
  $alerts = array();
  $personal = Personal_proveedores::find($id_personal);
  //dd($request);

  if($request['pers_cargo'] == '') {
    $ok = false;
    array_push($errors, 'Error: El campo Cargo es obligatorio.');
  }

  $personalExist = Personal_proveedores::where('id_proveedor','=',$id_proveedor)->where('id', '!=', $personal->id)->get();

  foreach ($personalExist as $p) {
    if($p->cargo == $request['pers_cargo']){
      $ok = false;
      array_push($errors, 'Error: Ya existe Personal con el cargo indicado.');
      break;
    }
  }


  if($ok) {

    $personal->id_proveedor = $id_proveedor;
    $personal->cargo = $request['pers_cargo'];
    $personal->nombre = $request['pers_nombre'];
    $personal->correo = $request['pers_correo'];
    $personal->telefono = $request['pers_telefono'];

    if($personal->save()){
      array_push($success, 'Personal actualizado correctamente.');
    }

    Session::put('success',$success);

    return back();

  } else {
    Session::put('alerts',$alerts);

    $requestErr = $request->all();
    //dd($requestErr);
    return back()->with(array('errors' => $errors));
  }
}

function eliminar_personal($id_proveedor, $id_personal){
  $personal = Personal_proveedores::find($id_personal);
  $personal->delete();

  return redirect('/proveedores/detalle/'.$id_proveedor);
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
}
