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

    public function adaptar()
    {
      $productos = Productos_pedidos::where('estado_envio', '!=', 1 )->get();
      foreach ($productos as $producto) {
        $producto->fecha_max_salida = $producto->pedido->fecha_de_salida_producto($producto);
        $producto->save();
      }
      return 'OK';
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

    public function descargar_hoy(Request $request)
    {
      $inputs = $request->all();
      $hoy = date('Y-m-d');
      $productos = Productos_pedidos::where('estado_envio', '!=', 1 )->where('fecha_max_salida', '<=' , $hoy)->orderBy('fecha_max_salida', 'asc')->get();
      //dd($productos);
      $parametros = array();


      $productos_excel = Excel::create('Productos_salida_max_'.$hoy, function($excel) use($productos) {

        $excel->sheet('Sheetname', function($sheet) use($productos) {
          // headers del documento xls
          $header = [];
          $row = 1;


          $header = array(
            'Numero albaran',
            'Nombre producto',
            'Referencia',
            'Ean'
          );

          //dd($productos);
          // Bucle para rellenar el documento segun el numero de pedidos
          foreach($productos as $producto){
            $row++;

            $product_excel = array(
              $producto->pedido->numero_albaran,
              $producto->nombre_esp,
              $producto->SKU,
              $producto->ean
            );



            $sheet->row($row, $product_excel);


          }

          $sheet->fromArray($header, null, 'A1', true);


        });

      });

      return $productos_excel->download("xlsx");

      /*Mail::send('mail.envio_max', $parametros, function($message) use($hoy,$productos_excel)
      {
        $message->from('info@decowood.es', 'Info ');
        //$message->to('sandra@decowood.es', 'Información')->subject('Productos que salen hoy (prueba)');
        $message->to('carlos@decowood.es', 'Información')->subject('Productos que salen hoy (prueba)');
        //$message->to('f.jimenez@decowood.es', 'Información')->subject('Productos que salen hoy (prueba)');

        $message->attach($productos_excel->store("xlsx",false,true)['full']);

      });*/

    }


}
