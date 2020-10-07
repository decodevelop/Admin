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

class ToolsController extends Controller
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

      //Session::put('amazonRepetidos', $productos);
      /*Session::forget('productosConsulta');
      Session::forget('productosConsultaSQL');
      Session::forget('productosConsultaManu');*/
      return View::make('development/inicio');
    }


    public function generarConsultaEan(Request $request){
      $productos = array();
      $success= array();
      $errors = array();
      Session::forget('productosConsultaSQL');
      Session::forget('productosConsultaManu');
      Session::put('productosConsulta', $productos);
      if(isset($request["inputSeguridad"]) && $request["inputSeguridad"]=='565dsad4874#@3sfasf' && $request['csv']!=NULL){ //Controlamos que no venga vacío el fichero
        // 1. Validar formato documento importado
        if(strtolower($request['csv']->getClientOriginalExtension())=='xls'){ //Controlamos la extensión del fichero.
          // 2. Generar nombre del fichero
          $nombreFichero = 'excel_ean_prestashop_'.date('d-m-Y_H-i-s').'.'.$request->csv->getClientOriginalExtension();
          // 3. Subir fichero al directorio de archivos
          $request->csv->move(public_path('documentos/tools'), $nombreFichero);
          // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
          //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
          //* el cleanup aquí mismo al cargar un nuevo fichero.

          // Paso 5: Bucle donde iremos subiendo los productos uno a uno.

        //  $productosexistentes = Productos::get();

          $contHechos = 0;
           Excel::load('documentos/tools/'.$nombreFichero, function($archivo) use($productos){

               $result=$archivo->get();
              // dd($result);
               foreach($result as $key => $value){

                 //$ids = explode('_',$value["id_producto_prestashop"]);
                  //dd($ids);
                 array_push($productos, array('id' => $value["id_product_attribute"],
                                              'ean'=>$value["ean"]));

               }
                 Session::put('productosConsulta', $productos);



           })->get();


           array_push($success,'Fichero subido correctamente');


        }else array_push($errors,'Formato de fichero no válido.');
      }else array_push($errors,'No has subido fichero.');

      return View::make('development/inicio',  array('errors' => $errors, 'success' => $success));
    }

    public function generarConsultaPreciosSql(Request $request){
      $productos = array();
      $success= array();
      $errors = array();

      Session::put('preusConsultaSQL', $productos);

      if(isset($request["inputSeguridad"]) && $request["inputSeguridad"]=='565dsad4874#@3sfasf' && $request['csv']!=NULL){ //Controlamos que no venga vacío el fichero
        // 1. Validar formato documento importado
        if(strtolower($request['csv']->getClientOriginalExtension())=='xlsx'){ //Controlamos la extensión del fichero.
          // 2. Generar nombre del fichero
          $nombreFichero = 'excel_categories_prestashop_'.date('d-m-Y_H-i-s').'.'.$request->csv->getClientOriginalExtension();
          // 3. Subir fichero al directorio de archivos
          $request->csv->move(public_path('documentos/tools'), $nombreFichero);
          // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
          //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
          //* el cleanup aquí mismo al cargar un nuevo fichero.

          // Paso 5: Bucle donde iremos subiendo los productos uno a uno.

        //  $productosexistentes = Productos::get();

          $contHechos = 0;
           Excel::load('documentos/tools/'.$nombreFichero, function($archivo) use($productos){

               $result=$archivo->get();
              // dd($result);
               foreach($result as $key => $value){
                 //dd($value);
                 //$ids = explode('_',$value["id_producto_prestashop"]);
                  //dd($ids);
                 array_push($productos, array('id_product' => $value["id_product"],
                                              'id_product_attribute'=>$value["id_product_attribute"],
                                              'reference'=>$value["reference"],
                                              'price'=>$value["price"],
                                              'desc'=>$value["desc"],
                                              'new_price'=>$value["new_price"]));

               }
                 Session::put('preusConsultaSQL', $productos);



           })->get();




           array_push($success,'Fichero subido correctamente');


        }else array_push($errors,'Formato de fichero no válido.');
      }else array_push($errors,'No has subido fichero.');

      return Excel::create('SQL_precios', function($excel) {

        $excel->sheet('Sheetname', function($sheet) {
          // headers del documento xls
          $header = [];
          $row = 1;


          $header = array('SQL_DELETE','SQL_COMBIS','SQL_COMBIS_2','INSERT_TO');
          $productos = Session::get('preusConsultaSQL');
          //dd($productos);
          // Bucle para rellenar el documento segun el numero de pedidos
          foreach($productos as $producto){
            $row++;

            $product_excel = array(
              "delete FROM ps_specific_price WHERE id_product = ".$producto['id_product'].";",
              "update ps_product_attribute SET price=".$producto['new_price']
              ." WHERE id_product_attribute=".$producto['id_product_attribute'].";",
              "update ps_product_attribute_shop SET price=".$producto['new_price']
              ." WHERE id_product_attribute=".$producto['id_product_attribute'].";",
              "insert into `ps_specific_price`(`id_specific_price`,`id_specific_price_rule`"
              .",`id_cart`,`id_product`,`id_shop`,`id_shop_group`,`id_currency`,`id_country`,"
              ."`id_group`,`id_customer`,`id_product_attribute`,`price`,`from_quantity`"
              .",`reduction`,`reduction_tax`,`reduction_type`,`from`,`to`)VALUES(NULL,'0','0','"
              .$producto['id_product']
              ."','0','0','0','0','0','0','"
              .$producto['id_product_attribute']
              ."','-1.000000','1','"
              .$producto['desc']
              ."','1','percentage','','');"
            );

            /*$product_excel = array(
              "delete FROM psdw_specific_price WHERE id_product = ".$producto['id_product'].";",
              "update psdw_product_attribute SET price=".$producto['new_price']
              ." WHERE id_product_attribute=".$producto['id_product_attribute'].";",
              "update psdw_product_attribute_shop SET price=".$producto['new_price']
              ." WHERE id_product_attribute=".$producto['id_product_attribute'].";",
              "insert into `psdw_specific_price`(`id_specific_price`,`id_specific_price_rule`"
              .",`id_cart`,`id_product`,`id_shop`,`id_shop_group`,`id_currency`,`id_country`,"
              ."`id_group`,`id_customer`,`id_product_attribute`,`price`,`from_quantity`"
              .",`reduction`,`reduction_tax`,`reduction_type`,`from`,`to`)VALUES(NULL,'0','0','"
              .$producto['id_product']
              ."','0','0','0','0','0','0','"
              .$producto['id_product_attribute']
              ."','-1.000000','1','"
              .$producto['desc']
              ."','1','percentage','','');"
            );*/



            $sheet->row($row, $product_excel);


          }

          $sheet->fromArray($header, null, 'A1', true);


        });

      })->export('xls');



      return View::make('development/inicio',  array('errors' => $errors, 'success' => $success));
    }

    public function generarConsultaCategoriasSql(Request $request){
      $productos = array();
      $success= array();
      $errors = array();
      Session::forget('productosConsulta');
      Session::forget('productosConsultaManu');
      Session::put('productosConsultaSQL', $productos);
      if(isset($request["inputSeguridad"]) && $request["inputSeguridad"]=='565dsad4874#@3sfasf' && $request['csv']!=NULL){ //Controlamos que no venga vacío el fichero
        // 1. Validar formato documento importado
        if(strtolower($request['csv']->getClientOriginalExtension())=='xls'){ //Controlamos la extensión del fichero.
          // 2. Generar nombre del fichero
          $nombreFichero = 'excel_categories_prestashop_'.date('d-m-Y_H-i-s').'.'.$request->csv->getClientOriginalExtension();
          // 3. Subir fichero al directorio de archivos
          $request->csv->move(public_path('documentos/tools'), $nombreFichero);
          // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
          //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
          //* el cleanup aquí mismo al cargar un nuevo fichero.

          // Paso 5: Bucle donde iremos subiendo los productos uno a uno.

        //  $productosexistentes = Productos::get();

          $contHechos = 0;
           Excel::load('documentos/tools/'.$nombreFichero, function($archivo) use($productos){

               $result=$archivo->get();
              // dd($result);
               foreach($result as $key => $value){

                 //$ids = explode('_',$value["id_producto_prestashop"]);
                  //dd($ids);
                 array_push($productos, array('id' => $value["id"],
                                              'pos'=>$value["pos"]));

               }
                 Session::put('productosConsultaSQL', $productos);



           })->get();


           array_push($success,'Fichero subido correctamente');


        }else array_push($errors,'Formato de fichero no válido.');
      }else array_push($errors,'No has subido fichero.');



      return View::make('development/inicio',  array('errors' => $errors, 'success' => $success));
    }

    public function generarConsultaManufacturerSql(Request $request){
      $productos = array();
      $success= array();
      $errors = array();
      Session::forget('productosConsulta');
      Session::forget('productosConsultaSQL');
      Session::put('productosConsultaManu', $productos);
      if(isset($request["inputSeguridad"]) && $request["inputSeguridad"]=='565dsad4874#@3sfasf' && $request['csv']!=NULL){ //Controlamos que no venga vacío el fichero
        // 1. Validar formato documento importado
        if(strtolower($request['csv']->getClientOriginalExtension())=='xls'){ //Controlamos la extensión del fichero.
          // 2. Generar nombre del fichero
          $nombreFichero = 'excel_categories_prestashop_'.date('d-m-Y_H-i-s').'.'.$request->csv->getClientOriginalExtension();
          // 3. Subir fichero al directorio de archivos
          $request->csv->move(public_path('documentos/tools'), $nombreFichero);
          // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
          //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
          //* el cleanup aquí mismo al cargar un nuevo fichero.

          // Paso 5: Bucle donde iremos subiendo los productos uno a uno.

        //  $productosexistentes = Productos::get();

          $contHechos = 0;
           Excel::load('documentos/tools/'.$nombreFichero, function($archivo) use($productos){

               $result=$archivo->get();
              // dd($result);
               foreach($result as $key => $value){

                 //$ids = explode('_',$value["id_producto_prestashop"]);
                  //dd($ids);
                 array_push($productos, array('id' => $value["id"]));

               }
                 Session::put('productosConsultaManu', $productos);



           })->get();


           array_push($success,'Fichero subido correctamente');


        }else array_push($errors,'Formato de fichero no válido.');
      }else array_push($errors,'No has subido fichero.');

      return View::make('development/inicio',  array('errors' => $errors, 'success' => $success));
    }

    public function pruebas(){

      //$pedido = new Pedidos;
      $producto_pedido = Productos_pedidos::find(1);
      $pedido = Pedidos::where('numero_pedido','=','10589')
        ->whereHas('cliente',  function ($query) {
          $query->where('nombre_apellidos', 'like', "%Jonatan%");
        })
        ->whereHas('productos',  function ($query) {
          $query->where('nombre', 'like', "%%");
        })
        ->whereHas('origen',  function ($query) {
          $query->Where( function ($query) {
              $query->orWhere('referencia', 'like', "%CB%");
              $query->orWhere('referencia', 'like', "%FS%");
          });
        })
        ->get();
      //$origen = Origen_pedidos::find(1);
      //dd($pedido->productos[0]->SKU);
      dd($pedido);


    }

    public function exportarClientesWebs($web ,Request $request){
      $productos = array();
      $success= array();
      $errors = array();

      return Excel::create('clientes_web', function($excel) use($web){

        $excel->sheet('Sheetname', function($sheet) use($web) {
          // headers del documento xls
          $header = [];
          $row = 1;


          $header = array('Nombre','email_facturacion','web');

          $clientes = Clientes_pedidos::whereHas('pedidos',  function ($query) use($web) {
            $query->whereHas('origen',  function ($query) use($web) {
              $query->where('id', '=', $web);
            });
          })->get();

          //dd($productos);
          // Bucle para rellenar el documento segun el numero de pedidos
          foreach($clientes as $cliente){
            if(isset($cliente->pedidos[0])){
              $row++;

              $cliente_excel = array(
                $cliente->nombre_apellidos,
                $cliente->email_facturacion,
                $cliente->pedidos[0]->origen->nombre
              );

              $sheet->row($row, $cliente_excel);

            }
          }

          $sheet->fromArray($header, null, 'A1', true);


        });

      })->export('xls');
    }


    public function importarProductosParaEtiquetasQR(Request $request){
      $page_name= "Generador etiquetas QR";
      $format = ".XSLX";
      return View::make('herramientas/importar' , array('page_name' => $page_name,
                                                        'format' => $format));

    }


    public function generador_etiquetas(){
       return View::make('herramientas/generador_etiquetas');
    }
    public function etiquetas_almacen(Request $request){
        return View::make('herramientas/etiquetas_almacen' , array('datos' => $request));
    }

    public function descargar_imagen(){


      $imagen = file_get_contents('https://decowood.es/7743/corcho-mapa-del-mundo-blanco.jpg');
      mkdir("imagenes_limango/090201-S001-01-MB", 0700);
      file_put_contents('090201-S001-01-MB/foto.jpg', $imagen);

    }

    public function generarEtiquetasQR(Request $request){
      $productos = array();
      $success= array();
      $errors = array();
      if(isset($request["inputSeguridad"]) && $request["inputSeguridad"]=='565dsad4874#@3sfasf' && $request['csv']!=NULL){ //Controlamos que no venga vacío el fichero
        // 1. Validar formato documento importado
        if(strtolower($request['csv']->getClientOriginalExtension())=='xlsx'){ //Controlamos la extensión del fichero.
          // 2. Generar nombre del fichero
          $nombreFichero = 'excel_etiquetasQR_'.date('d-m-Y_H-i-s').'.'.$request->csv->getClientOriginalExtension();
          // 3. Subir fichero al directorio de archivos
          $request->csv->move(public_path('documentos/tools'), $nombreFichero);
          // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
          //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
          //* el cleanup aquí mismo al cargar un nuevo fichero.

          // Paso 5: Bucle donde iremos subiendo los productos uno a uno.

        //  $productosexistentes = Productos::get();

          $contHechos = 0;
           $pr = Excel::load('documentos/tools/'.$nombreFichero, function($archivo) use($productos){


//                 Session::put('productosConsulta', $productos);


           })->get();

          // dd($result);
           foreach($pr as $key => $value){

             //dd($value);
             array_push($productos, array('referencia' => $value["referencia"],
                                          'nombre'=>$value["nombre"],
                                          'unidades'=>$value["unidades"],
                                          'url'=>$value["url"]));

           }
           //dd($productos);
           array_push($success,'Fichero subido correctamente');


        }else array_push($errors,'Formato de fichero no válido.');
      }else array_push($errors,'No has subido fichero.');


       return View::make('campanas/imprimirEtiquetasQR', array('productos' => $productos));
    }


}
