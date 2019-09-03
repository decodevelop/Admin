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
use App\Clientes_pedidos;
use App\Direcciones;
use App\Seguimiento_proveedores;
use App\Personal_proveedores;
use App\Horario_proveedores;
use App\Valoraciones_proveedores;
use App\Transportistas;
use App\Metodos_pago;

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

class ClientesController extends Controller
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
    $filtro = $request->query();

    if(!$filtro){
      $clientes = DB::table('clientes_pedidos')
      ->orderBy('id', 'desc')
      ->paginate(50);
    } else {
      $clientes = DB::table('clientes_pedidos')
      ->orderBy('id', 'desc')
      ->paginate(50);
    }

    return View::make('clientes/inicio', array('clientes' => $clientes));
  }

  public function detalle($id){
    $cliente = Clientes_pedidos::find($id);
    $direcciones = Direcciones::where('id_cliente', '=', $id)->get();

    return View::make('clientes/detalle', array('cliente' => $cliente,'direcciones' => $direcciones));
  }

  public function nuevo_cliente(){
    return View::make('clientes/nuevo_cliente');
  }

  public function nuevo_cliente_POST(Request $request){
    $ok = true;
    $errors = array();
    $success = array();
    $alerts = array();
    $inputs = $request->all();
    //dd($request['pers_cargo']);

    // Validamos que el nombre no sea nulo.
    if(strlen($request->input('nombre_facturacion')) == 0) {
      $ok = false;
      array_push($errors,'Error: El campo Nombre de Facturación no puede estar vacío.');
    }

    // Validamos que si ya existen mas clientes con el mismo mail de facturación, no puedan crearse mas con el mismo mail de envio.
    $clientesAComparar = Clientes_pedidos::where('email_facturacion', '=', $request['email_facturacion'])->get();
    //dd($clientesAComparar);
    foreach ($clientesAComparar as $c) {
      if($c->email == $request['email_envio']){
        $ok = false;
        array_push($errors,'Error: Ya existen clientes con el mail indicado.');
        break;
      }
    }

    if($ok) {
      $cliente = new Clientes_pedidos;
      $cliente->dni = $request['dni'];

      $cliente->nombre_apellidos = $request['nombre_facturacion'];
      $cliente->email_facturacion = $request['email_facturacion'];
      $cliente->telefono_facturacion = $request['telefono_facturacion'];

      $cliente->nombre_envio = $request['nombre_envio'];
      $cliente->email = $request['email_envio'];
      $cliente->telefono = $request['telefono_envio'];

      if($cliente->save()){
        array_push($success,'Cliente creado correctamente.');
      }

      for ($i=0; $i < (int)$request['cont_direcciones']; $i++) {
        if(strlen($request['direccion_facturacion'][$i]) > 0){

          $direccion = new Direcciones;
          $direccion->id_cliente = $cliente->id;

          $direccion->direccion_facturacion = $request['direccion_facturacion'][$i];
          $direccion->cp_facturacion = $request['cp_facturacion'][$i];
          $direccion->ciudad_facturacion = $request['ciudad_facturacion'][$i];
          $direccion->estado_facturacion = $request['estado_facturacion'][$i];
          $direccion->pais_facturacion = $request['pais_facturacion'][$i];

          $direccion->direccion_envio = $request['direccion_envio'][$i];
          $direccion->cp_envio = $request['cp_envio'][$i];
          $direccion->ciudad_envio = $request['ciudad_envio'][$i];
          $direccion->estado_envio = $request['estado_envio'][$i];
          $direccion->pais_envio = $request['pais_envio'][$i];

          $direccion->save();
        }
      }

      $vaciar_form = new Clientes_pedidos;
      Session::put('request',$vaciar_form);
      Session::put('success',$success);

      return back();

    } else {
      Session::put('alerts',$alerts);

      $requestErr = $request->all();
      Session::put('request',$requestErr);

      return back()->with(array('errors' => $errors));
    }
  }

  public function modificar_cliente($id){
    $cliente = Clientes_pedidos::find($id);

    return View::make('clientes/modificar_cliente', array('cliente' => $cliente));
  }

  public function modificar_cliente_POST($id, Request $request){
    $ok = true;
    $errors = array();
    $success = array();
    $alerts = array();
    $cliente = Clientes_pedidos::find($id);

    // Validamos que el nombre no sea nulo.
    if(strlen($request->input('nombre_facturacion')) == 0) {
      $ok = false;
      array_push($errors,'Error: El campo Nombre de Facturación no puede estar vacío.');
    }

    if($ok) {
      $cliente->dni = $request['dni'];

      $cliente->nombre_apellidos = $request['nombre_facturacion'];
      $cliente->email_facturacion = $request['email_facturacion'];
      $cliente->telefono_facturacion = $request['telefono_facturacion'];

      $cliente->nombre_envio = $request['nombre_envio'];
      $cliente->email = $request['email_envio'];
      $cliente->telefono = $request['telefono_envio'];

      if($cliente->save()){
        array_push($success,'Cliente creado correctamente.');
      }

      Session::put('success',$success);

      return back();

    } else {
      if(isset($request['contrato_pdf'])){
        array_push($alerts , 'Recuerde volver a subir los PDFs.');
      }

      Session::put('alerts',$alerts);

      return back()->with(array('errors' => $errors));
    }
  }

  public function modificar_direccion($id_cliente, $id_direccion){
    $direccion = Direcciones::find($id_direccion);
    $cliente = Clientes_pedidos::find($id_cliente);

    return View::make('clientes/modificar_direccion', array('direccion' => $direccion, 'cliente' => $cliente));
  }

  public function modificar_direccion_POST($id_cliente, $id_direccion, Request $request){
    $success = array();

    $direccion = Direcciones::find($id_direccion);
    $direccion->direccion_envio = $request['direccion_envio'];
    $direccion->ciudad_envio = $request['ciudad_envio'];
    $direccion->estado_envio = $request['estado_envio'];
    $direccion->pais_envio = $request['pais_envio'];
    $direccion->cp_envio = $request['cp_envio'];
    $direccion->direccion_facturacion = $request['direccion_facturacion'];
    $direccion->ciudad_facturacion = $request['ciudad_facturacion'];
    $direccion->estado_facturacion = $request['estado_facturacion'];
    $direccion->pais_facturacion = $request['pais_facturacion'];
    $direccion->cp_facturacion = $request['cp_facturacion'];

    if($direccion->save()){
      array_push($success,'Dirección actualizada correctamente.');
    }

    Session::put('success',$success);

    return back();
  }

  public function nueva_direccion($id_cliente){
    $cliente = Clientes_pedidos::find($id_cliente);

    return View::make('clientes/nueva_direccion', array('cliente' => $cliente));
  }

  public function nueva_direccion_POST($id_cliente, Request $request){
    $success = array();

    if(strlen($request['direccion_facturacion']) > 0){
      $direccion = new Direcciones;
      $direccion->id_cliente = $id_cliente;

      $direccion->direccion_facturacion = $request['direccion_facturacion'];
      $direccion->cp_facturacion = $request['cp_facturacion'];
      $direccion->ciudad_facturacion = $request['ciudad_facturacion'];
      $direccion->estado_facturacion = $request['estado_facturacion'];
      $direccion->pais_facturacion = $request['pais_facturacion'];

      $direccion->direccion_envio = $request['direccion_envio'];
      $direccion->cp_envio = $request['cp_envio'];
      $direccion->ciudad_envio = $request['ciudad_envio'];
      $direccion->estado_envio = $request['estado_envio'];
      $direccion->pais_envio = $request['pais_envio'];

      if($direccion->save()){
        array_push($success,'Dirección añadida correctamente.');
      }
    }

    Session::put('success',$success);
    //Session::put('proveedor',$proveedor);

    return back();
  }

  function eliminar_direccion($id_cliente, $id_direccion){
    $dir = Direcciones::find($id_direccion);
    $dir->delete();

    return redirect('/clientes/detalle/'.$id_cliente);
  }

  public function generar_pedido($id_cliente, $id_direccion){
    $origenes = Origen_pedidos::orderBy('id','asc')->groupBy('grupo')->get();
    $transportistas = Transportistas::get();
    $proveedores = Proveedores::get();
    $metodos_pago = Metodos_pago::groupBy('grupo')->get();
    $direccion_cliente = Direcciones::find($id_direccion);
    $cliente = Clientes_pedidos::find($id_cliente);
    //dd($cliente);

    return View::make('pedidosnew/nuevo', array('origenes' => $origenes,
    'transportistas' => $transportistas,
    'proveedores' => $proveedores,
    'metodos_pago' => $metodos_pago,
    'direccion_cliente' => $direccion_cliente,
    'cliente' => $cliente));
  }
}
