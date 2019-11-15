<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use View;
use Illuminate\Support\Facades\DB;
use App\Productos_amazon;
use App\Pedidos_wix_importados;
use App\Origen_pedidos;
use App\User;
use App\Pedidos;
use App\Productos_pedidos;
use App\Clientes_pedidos;
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

class PlazosController extends Controller
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
    public function index(Request $request)
    {
      $productos = Productos_pedidos::where('estado_envio', '!=', 1 )->get();
      $p = new Pedidos;
      $fechas = array();
      foreach ($productos as $producto) {
        $fecha_salida_F = $p->fecha_de_salida_producto($producto);
        $fecha_salida = strtotime($fecha_salida_F);
        //dd($producto);
        if( isset($fechas[$fecha_salida]) ){
          array_push($fechas[$fecha_salida], $producto);
        }else{
          $fechas[$fecha_salida] = array();
          array_push($fechas[$fecha_salida], $producto);
        }
        $fechas[$fecha_salida]['fecha'] = $fecha_salida_F ;
      }
      ksort($fechas,1);
      //dd($fechas);
      return View::make('plazos/inicio' ,array('fechas' => $fechas));
    }

    public function productos(Request $request)
    {
      $inputs = $request->all();
      //dd($inputs['fecha_pedido']);

    }




}
