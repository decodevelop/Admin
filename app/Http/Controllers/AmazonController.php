<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use View;
use Illuminate\Support\Facades\DB;
use App\Productos_amazon;
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
use \Milon\Barcode\DNS1D;
use Session;
use PHPExcel_Worksheet_Drawing;

class AmazonController extends Controller
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

        /*-------------- ORDENACIONES --------------*/
        $getParams = $request->query();
        $orderBy2 = "id";
        $orderByType2 = "desc";

        /*-------------- FILTROS --------------*/
        $where = "1=1 ";
        if(isset($getParams["nombre"]) && $getParams["nombre"]!="") $where .= " and nombre like '%".$getParams["nombre"]."%'";
        if(isset($getParams["referencia"]) && $getParams["referencia"]!="") $where .= " and referencia like '%".$getParams["referencia"]."%'";
        if(isset($getParams["codigo_ean"]) && $getParams["codigo_ean"]!="") $where .= " and codigo_ean like '%".$getParams["codigo_ean"]."%'";
        if(isset($getParams["asin"]) && $getParams["asin"]!="") $where .= " and asin like '%".$getParams["asin"]."%'";

        /*-------------- QUERY MySQL ---------------> */
        $listado_productos = DB::table('productos_amazon')->whereRaw($where)->orderBy($orderBy2,$orderByType2)->paginate(30);

        return View::make('amazon/inicio', array('listado_productos' => $listado_productos, 'filtros' => array($orderBy2, $orderByType2)));
    }

   /**
     * Genera e imprime etiquetas.
     *
     * @return view
     */
   public function imprimirEtiquetas(Request $request)
    {
        $productos_imprimir = json_decode($request->all()["productos_amazon_imprimir"],true);
        return View::make('amazon/imprimirEtiquetas', array('productos_imprimir' => $productos_imprimir));
    }

    /**
     * Guarda en el carrito productos a imprimir.
     *
     * @return view
     */
   public function guardarCarrito(Request $request)
    {
        $producto_guardar = json_decode($request->all()["productos_amazon_carrito"],true);

        if (!Session::exists('productosCarrito')) Session::put('productosCarrito', Array());
        Session::push('productosCarrito',$producto_guardar);

        $carrito_final = array('');

        foreach (session('productosCarrito') as $key => $productoCarrito) {
          $carrito_final[0] .= '<li><div class="col-xs-10 textoLineaCarrito">'.$productoCarrito[0]["cantidad_producto"].' x '.$productoCarrito[0]["nombre_producto"].': '.$productoCarrito[0]["codigo_ean"].'</div>';
          $carrito_final[0] .= '<div class="col-xs-2"><div class="eliminarIndividual '.$productoCarrito[0]['codigo_ean'].'"><i class="fa fa-times"></i></div></div></li>';
        }
        $carrito_final[0] .= '<script>eliminarIndividual();</script>';
        $carrito_final[1] = count(session('productosCarrito'));
       return $carrito_final;
    }

    /**
     * Elimina el producto del carrito.
     *
     * @return view
     */
   public function eliminarIndividual($codigo_ean, Request $request)
    {
        if(session()->exists('productosCarrito') && count(session('productosCarrito'))>0){
            foreach (session('productosCarrito') as $key => $productoCarrito) {
                if($productoCarrito[0]["codigo_ean"] == $codigo_ean){
                    $array_provisional = session('productosCarrito');
                    array_splice($array_provisional, $key, 1);
                    Session::put('productosCarrito',$array_provisional);
                    break;
                }
            }
        }
        return count(session('productosCarrito'));
    }

    /**
     * Elimina todo el carrito.
     *
     * @return view
     */
   public function eliminarCarrito(Request $request)
    {
        if(session()->exists('productosCarrito') && count(session('productosCarrito'))>0)
            Session::put('productosCarrito',Array());

        return back();
    }

    /**
     * Imprimir todo el carrito.
     *
     * @return view
     */
   public function imprimirCarritoCompra(Request $request)
    {
        if(session()->exists('productosCarrito') && count(session('productosCarrito'))>0){
            $productos_imprimir=session('productosCarrito');
            foreach ($productos_imprimir as $key => $producto) {
                $productos_imprimir[$key] = $producto[0];
            }
            session()->forget('productosCarrito');
            return View::make('amazon/imprimirEtiquetas', array('productos_imprimir' => $productos_imprimir));
        }else{
            return back();
        }
    }

    /**
     * Redirección a la vista de subida de ficheros csv.
     *
     * @return view
     */
   public function importar_csv_amazon(Request $request)
    {
        return View::make('herramientas/importar_csv_amazon');
    }

    /**
     * Subida de un fichero CSV para la impresión de etiquetas.
     *
     * @return view
     */
   public function importar_csv_amazon_subida(Request $request)
    {
        $origen_csv = $request['o_csv'];
        $nombreFichero = array();
        $documento_csv = array();

      if($origen_csv == 'AMV' && $request['csv']!=null){
        foreach ($request['csv'] as $clau => $csv_file) {

          if($origen_csv == 'AMV' && $csv_file!=null){
            // 1. Validar formato documento importado
            $this->validate($request, [
                    'file'      => $csv_file,
                    'extension' => strtolower($csv_file->getClientOriginalExtension()),
                ],
                [
                    'file'          => 'required',
                    'extension'      => 'required|in:csv',

            ]);


            // 2. Generar nombre del fichero
            $nombreFichero[$clau] = 'CSV_'.$origen_csv.'_'.date('d-m-Y_H-i-s').'_'.$clau.'.'.$csv_file->getClientOriginalExtension();

            // 3. Subir fichero al directorio de archivos
            $csv_file->move(public_path('documentos/amazon'), $nombreFichero[$clau]);

            // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
            //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
            //* el cleanup aquí mismo al cargar un nuevo fichero.

            // 4. Abrimos el documento para realizar la lectura.
            $documento_csv[$clau] = fopen(public_path('documentos/amazon').'/'.$nombreFichero[$clau],"r");

          }else{
            return back();
          }
        }
      }else{
        return back();
      }
            // Paso 5: Bucle donde crearemos un array que pasaremos a la función de imprimir.

            /*
                Datos:
                    $data[0]  =>   Número de modelo
                    $data[1]  =>    ASIN
                    $data[2]  =>    SKU
                    $data[3]  =>    Título
                    $data[4]  =>    Fecha prevista de entrega
                    $data[5]  =>    Cantidad enviada
                    $data[6]  =>    Cantidad aceptada
                    $data[7]  =>    Cantidad prevista
                    $data[8]  =>    Cantidad recibida
                    $data[9]  =>    Cantidad restante,
                    $data[10]  =>   Coste por unidad
                    $data[11]  =>   Coste total
            */
            $imprimirCSVAmazon = array();
            $cont = 0;
            $contHechos = 0;

            foreach ($documento_csv as $i => $doc) {
              while ($data = fgetcsv ($doc, 500, ",")) {
                if(!empty($data[1]) && $cont>0){//Miramos si el campo SKU no esté vacío.
                  //Hacemos una consulta para sacar el código ean que tenemos nosotros asociado a ese producto
                  $resultado = DB::select("select * from productos_amazon where asin = '".$data[1]."'");

                  if($resultado!=null){
                      $resultado = $resultado[0];

                      //Introducimos los datos que necesitamos
                      array_push($imprimirCSVAmazon, array(
                          "id_producto" => $resultado->id,
                          "cantidad_producto" => $data[6],
                          "codigo_ean" => $resultado->codigo_ean,
                          "nombre_producto" => $resultado->nombre,
                          "referencia_producto" =>$resultado->referencia
                      ));
                      $contHechos += 1;
                  }


                }
                $cont += 1;
              }
            }

            return View::make('amazon/imprimirEtiquetas', array('productos_imprimir' => $imprimirCSVAmazon));

    }

    /** ========================== ANTIGUO ================================
     * Función creada provisionalmente. Debería hacerse un formulario. Por el momento se accederá poniendo amazon/subidaAmazon en la url.
     * Datos aceptados:
     *  1 - Nombre
     *  2 - Referencia
     *  3 - Codigo_ean
     *  4 - Asin (No obligatorio)
     *
     *  IMPORTANTE: Antes de hacer esta subida se deberá adaptar la función para que no intente subir productos ya existentes o en su defecto hacer un truncate de la tabla productos_amazon.
     *
     *  Se colocará en la carpeta public. Por defecto crearemos el archivo eanadmin.xlsx
     *
     * @return view
     */
    public function subidaAmazon(Request $request){
       Excel::load('public/eanadmin.xlsx', function($archivo)
          {
           $result=$archivo->get();
           foreach($result as $key => $value){
                DB::table('productos_amazon')->insert(
                        ['nombre' => ''.$value->nombre_producto.'', 'referencia' => ''.$value->referencia.'', 'codigo_ean' => $value->codigo_ean, 'asin' => $value->asin]
                    );
            }


      })->get();
    }
    /**
    * Función que retorna la vista del formulario de subida .xls
    *
    *
    * @return view
    */
    public function subir_excel_view(){
      $repetidos = array();
      Session::put('amazonRepetidos', $repetidos);
      return view('herramientas.importar_excel_amazon');
    }
    /**
    * Comprobación y Subida del fichero .xls
    * Datos aceptados:
    *  1 - Nombre
    *  2 - Referencia
    *  3 - Codigo_ean
    *  4 - Asin (No obligatorio)
    *
    * @return view
    */
    public function subir_excel(Request $request){
        $repetidos = array();
        $all_rep= true;
        $success= array();
        $errors = array();
        $subidos= 0;
        Session::put('amazonSubidos', 0);

        Session::put('amazonRepetidos', $repetidos);
      if(isset($request["inputSeguridad"]) && $request["inputSeguridad"]=='565dsad4874#@3sfasf' && $request['csv']!=NULL){ //Controlamos que no venga vacío el fichero
        // 1. Validar formato documento importado
        if(strtolower($request['csv']->getClientOriginalExtension())=='xls'){ //Controlamos la extensión del fichero.
          // 2. Generar nombre del fichero
          $nombreFichero = 'excel_amazon_'.date('d-m-Y_H-i-s').'.'.$request->csv->getClientOriginalExtension();
          // 3. Subir fichero al directorio de archivos
          $request->csv->move(public_path('documentos/amazon'), $nombreFichero);
          // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
          //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
          //* el cleanup aquí mismo al cargar un nuevo fichero.

          // Paso 5: Bucle donde iremos subiendo los productos uno a uno.

          //  $productosexistentes = Productos::get();

          $contHechos = 0;
           Excel::load('documentos/amazon/'.$nombreFichero, function($archivo) use($repetidos, $all_rep, $subidos){

               $result=$archivo->get();
               foreach($result as $key => $value){

                 if (DB::table('Productos_amazon')->where('codigo_ean', '=', $value['codigo_ean'])->exists()){
                  array_push($repetidos, $value['codigo_ean'].' = '.$value['nombre']);
                 }else{

                   $all_rep = false;
                   if($value['asin']==''){ $value['asin']= ''; }
                   DB::table('Productos_amazon')->insert([
                     'nombre' => $value['nombre'],
                     'referencia' => $value['referencia'],
                     'codigo_ean' => $value['codigo_ean'],
                     'asin' => $value['asin'],
                   ]);
                   $subidos++;
                 }

               }

              //dd($repetidos);
              Session::put('amazonRepetidos', $repetidos);
              Session::put('amazonSubidos', $subidos);

              //Session::push('amazonRepetidos',);
              //dd(session('amazonRepetidos'));

           })->get();

           $repetidos = session('amazonRepetidos');
           $subidos = session('amazonSubidos');

           array_push($success,'Fichero subido correctamente');

           if(count($repetidos)>0){
              array_push($success, count($repetidos).' codigo(s) EAN repetido(s), no se han subido');
           }
           if($subidos>0){
              array_push($success, $subidos.' Productos subidos correctamente');
           }
        }else array_push($errors,'Formato de fichero no válido.');
      }else array_push($errors,'No has subido fichero.');

      return View::make('herramientas/importar_excel_amazon', array('errors' => $errors, 'success' => $success));
    }

    public function descargar_excel(){
      $ruta_doc_xls = "documentos/albaranes/agrupados/";
      $nombre_xls = "";

      return Excel::create('productos_amazon', function($excel) {
        $excel->getDefaultStyle()
               ->getAlignment()
               ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getDefaultStyle()
               ->getAlignment()
               ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $excel->sheet('Sheetname', function($sheet){
          // headers del documento xls
          $header = [];
          $row = 1;

          //Crear headers
          $header = array(
            'nombre',
            'referencia',
            'codigo_ean',
            'asin'
          );
          //montamos el select
          $select = '';
          foreach ($header as $key => $campo) {
              $select .= $campo;
              if($key<count($header)-1) $select .=', ';
          }

          //añadimos las rows
          //hacemos la consulta
          $productos_amazon = DB::table('productos_amazon')
                              ->select(DB::raw($select))
                              ->get();

          //dd($productos_amazon);

          foreach ($productos_amazon as $key => $producto_amazon) {
              $productoAmazonFinal = Array();
              foreach ($producto_amazon as $clau => $val) {
                $productoAmazonFinal[$clau]= $val;
              }
              //dd($productoAmazonFinal);
              $row++;
              $sheet->row($row, $productoAmazonFinal);
          }

          $header = array_map('strtoupper', $header);
          $sheet->fromArray($header, null, 'A1', true);
          $sheet->getStyle("A1:D1")->getFont()->setBold(true);

        });
      })->export('xls');
    }

    public function modificar_producto(Request $request){
      $producto = json_decode($request->all()["producto_amazon"],true);
      //dd($producto);

      if(isset($producto[0]["codigo_ean"]) && $producto[0]["codigo_ean"]!=''){
      $producto_amazon = DB::table('productos_amazon')
                          ->whereRaw('codigo_ean = "'.$producto[0]["ean_original"].'"')
                          ->update(['nombre' => $producto[0]["nombre"],
                                    'referencia' => $producto[0]["referencia"],
                                    'codigo_ean' => $producto[0]["codigo_ean"],
                                    'asin' => $producto[0]["asin"]]);
      }
      //return "hola";
      //dd($producto_amazon[0]);

      /*if(isset($producto[0]["codigo_ean"]) && $producto[0]["codigo_ean"]!=''){
      $producto_amazon = DB::table('productos_amazon')
                          ->whereRaw('codigo_ean = "'.$producto[0]["codigo_ean"].'"')
                          ->get();
      }

      dd($producto_amazon[0]);*/

    }

    public function borrar_producto($ean){
      DB::table('productos_amazon')
                          ->whereRaw('codigo_ean = "'.$ean.'"')
                          ->delete();
    }


    public function descargar_plantilla(){
      $ruta_doc_xls = "documentos/albaranes/agrupados/";
      $nombre_xls = "";

      return Excel::create('productos_amazon', function($excel) {
        $excel->getDefaultStyle()
               ->getAlignment()
               ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getDefaultStyle()
               ->getAlignment()
               ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $excel->sheet('Sheetname', function($sheet){
          // headers del documento xls
          $header = [];
          $row = 1;

          //Crear headers
          $header = array(
            'nombre',
            'referencia',
            'codigo_ean',
            'asin'
          );

          $header = array_map('strtoupper', $header);
          $sheet->fromArray($header, null, 'A1', true);
          $sheet->getStyle("A1:D1")->getFont()->setBold(true);

        });
      })->export('xls');
    }


    /**
     * Redirección a la vista de subida de ficheros csv.
     *
     * @return view
     */
   public function importar_csv_amazon_new(Request $request)
    {
        return View::make('herramientas/importar_csv_amazon_new');
    }

    /**
     * Subida de un fichero CSV para la impresión de etiquetas.
     *
     * @return view
     */
   public function importar_csv_amazon_subida_new(Request $request){
        $origen_csv = $request['o_csv'];
        $nombreFichero = array();
        $documento_csv = array();
        $explorer = $request['explorer'];

      if($origen_csv == 'AMV' && $request['csv']!=null){
        foreach ($request['csv'] as $clau => $csv_file) {

          if($origen_csv == 'AMV' && $csv_file!=null){
            // 1. Validar formato documento importado
            $this->validate($request, [
                    'file'      => $csv_file,
                    'extension' => strtolower($csv_file->getClientOriginalExtension()),
                ],
                [
                    'file'          => 'required',
                    'extension'      => 'required|in:csv',

            ]);


            // 2. Generar nombre del fichero
            $nombreFichero[$clau] = 'CSV_'.$origen_csv.'_'.date('d-m-Y_H-i-s').'_'.$clau.'.'.$csv_file->getClientOriginalExtension();

            // 3. Subir fichero al directorio de archivos
            $csv_file->move(public_path('documentos/amazon'), $nombreFichero[$clau]);

            // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
            //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
            //* el cleanup aquí mismo al cargar un nuevo fichero.

            // 4. Abrimos el documento para realizar la lectura.
            $documento_csv[$clau] = fopen(public_path('documentos/amazon').'/'.$nombreFichero[$clau],"r");

            }else{
              return back();
            }
          }
        }else{
          return back();
        }
            // Paso 5: Bucle donde crearemos un array que pasaremos a la función de imprimir.

            /*
                Datos:
                    $data[0]  =>   Orden de Compra (PO)
                    $data[1]  =>    Proveedor
                    $data[2]  =>    Enviar a
                    $data[3]  =>    Número de modelo
                    $data[4]  =>    ASIN
                    $data[5]  =>    SKU
                    $data[6]  =>    Título
                    $data[7]  =>    Estado
                    $data[8]  =>    Entrega desde
                    $data[9]  =>    Entrega hasta
                    $data[10] =>  fecha prevista
                    $data[11] => fecha entrada
                    $data[12]  =>   Cantidad enviada
                    $data[13]  =>   Cantidad aceptada
                    $data[14] => "Cantidad recibida"
                    $data[15] => "Cantidad restante"
                    $data[16] => "Coste por unidad"
                    $data[17] => "Coste total"
            */

            $imprimirCSVAmazon = array();
            $cont = 0;
            $contHechos = 0;

            foreach ($documento_csv as $i => $doc) {

              while ($data = fgetcsv ($doc, 500, $explorer)) {

                if(!empty($data[4]) && $cont>0){//Miramos si el campo SKU no esté vacío.
                  //Hacemos una consulta para sacar el código ean que tenemos nosotros asociado a ese producto
                  $resultado = DB::select("select * from productos_amazon where asin = '".$data[4]."'");

                  if($resultado!=null){
                      $resultado = $resultado[0];

                      //Introducimos los datos que necesitamos


                      array_push($imprimirCSVAmazon, array(
                          "id_producto" => $resultado->id,
                          "cantidad_producto" => $data[15],//14
                          "codigo_ean" => $resultado->codigo_ean,
                          "nombre_producto" => $resultado->nombre,
                          "referencia_producto" =>$resultado->referencia
                      ));
                      $contHechos += 1;



                  }


                }
                $cont += 1;

              }
            }
          //  dd($imprimirCSVAmazon);
            return View::make('amazon/imprimirEtiquetas', array('productos_imprimir' => $imprimirCSVAmazon));

  }

    public function gpdf_albaran(Request $request){
      $cnt=0;
  		// Inicializamos variables a utilizar durante el proceso
  		//cargar csv
      $fecha_hoy = getdate();
      $hoy = $fecha_hoy['year'].'/'.$fecha_hoy['mon'].'/'.$fecha_hoy['mday'];

      $explorer = $request['explorer'];
      $origen_csv = $request['o_csv'];
      $bultos = $request['bultos'];
      $transporte = $request['transporte'];
      $nombreFichero = array();
      $documento_csv = array();
      $clientes = array(
        "XESC - Constantí, Tarragona" => array(
          "calle" => "Avenida de las Puntas 10",
          "ciudad" => "Tarragona",
          'estado' => "Catalunya",
          "cp" => "43120"
        ),
        "MAD4 - San Fernando de Henares (Madrid)" => array(
          "calle" => "Avenida de la Astronomía, 24 San Fernando de Henares",
          "ciudad" => "Madrid",
          'estado' => "Madrid",
          "cp" => "28830"
        ),
        "XESA - Alovera" => array(
          "calle" => "ES Norbert NS 3PL Avenida Rio Henares, 16 ND Logistics",
          "ciudad" => "Alovera",
          'estado' => "Castilla y la Mancha",
          "cp" => "19208"
        ),
        "BCN1 - El Prat de Llobregat, Barcelona" => array(
          "calle" => "Av. de les Garrigues núm. 6-8",
          "ciudad" => "El Prat de Llobregat, Barcelona",
          "estado" => "Catalunya",
          "cp" => "08820"
        ),
        "BCN2 - MARTORELLES, Barcelona" => array(
          "calle" => "Carrer de la VERNEDA 22",
          "ciudad" => "MARTORELLES, Barcelona",
          "estado" => "Catalunya",
          "cp" => "08107"
        )
      );
      $lastID = (Pedidos_wix_importados::where('o_csv', '=', 'AM')->where('entrada_principal', '=', 1)->max('ID'));
      $lastID = (Pedidos_wix_importados::where('ID', '=', $lastID)->max('numero_pedido'))+1;

      //$lastID = DB::select("select numero_pedido from pedidos_wix_importados where 'AM' LIKE o_csv");
      //  $lastID =


      //dd($lastID);

      if($origen_csv == 'AMV' && $request['csv']!=null){
        foreach ($request['csv'] as $clau => $csv_file) {

          if($origen_csv == 'AMV' && $csv_file!=null){
            // 1. Validar formato documento importado
            $this->validate($request, [
                    'file'      => $csv_file,
                    'extension' => strtolower($csv_file->getClientOriginalExtension()),
                ],
                [
                    'file'          => 'required',
                    'extension'      => 'required|in:csv',

            ]);


            // 2. Generar nombre del fichero
            $nombreFichero[$clau] = 'CSV_'.$origen_csv.'_'.date('d-m-Y_H-i-s').'_'.$clau.'.'.$csv_file->getClientOriginalExtension();

            // 3. Subir fichero al directorio de archivos
            $csv_file->move(public_path('documentos/amazon'), $nombreFichero[$clau]);

            // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
            //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
            //* el cleanup aquí mismo al cargar un nuevo fichero.

            // 4. Abrimos el documento para realizar la lectura.
            $documento_csv[$clau] = fopen(public_path('documentos/amazon').'/'.$nombreFichero[$clau],"r");

          }else{
            return back();
          }
        }
      }else{
        return back();
      }

      $pedidoAmazon = array();
      $cont = 0;
      $contHechos = 0;

      foreach ($documento_csv as $i => $doc) {

        while ($data = fgetcsv ($doc, 500, $explorer)) {
          //dd($data);
          if(!empty($data[4]) && $cont>0){//Miramos si el campo SKU no esté vacío.

            //Hacemos una consulta para sacar el código ean que tenemos nosotros asociado a ese producto
            $resultado = DB::select("select * from productos_amazon where asin = '".$data[4]."'");

            if($resultado!=null){
              $resultado = $resultado[0];
              /*
                  Datos:
                      $data[0]  =>   Orden de Compra (PO)
                      $data[1]  =>    Proveedor
                      $data[2]  =>    Enviar a
                      $data[3]  =>    Número de modelo
                      $data[4]  =>    ASIN
                      $data[5]  =>    SKU
                      $data[6]  =>    Título
                      $data[7]  =>    Estado
                      $data[8]  =>    Entrega desde
                      $data[9]  =>    Entrega hasta
                      $data[10] => fecha prevista
                      $data[11] => fecha entrada
                      $data[12]  =>   Cantidad enviada
                      $data[13]  =>   Cantidad aceptada
                      $data[14] => "Cantidad recibida"
                      $data[15] => "Cantidad restante"
                      $data[16] => "Coste por unidad"
                      $data[17] => "Coste total"
              */
              //Introducimos los datos que necesitamos
              $existe= false;
              //dd($pedidoAmazon);
              foreach ($pedidoAmazon as $j => $PO) {
                if(in_array($data[0],$PO) && ($pedidoAmazon[$j]["PO"] == $data[0])){
                  $pedidoAmazon[$j]["cantidad_producto"]= $data[12] + $PO["cantidad_producto"];//antes 15
                  $preu = explode(' ', $data[17]);// antes 17
                  $preu[1]= str_replace('.','',$preu[1]);
                  $preu[1] =  str_replace(',','.',$preu[1]);
                  $pedidoAmazon[$j]["PO_total"] = $preu[1] + $PO["PO_total"];
                  $existe= true;

                  $cnt++;

                  if($cnt==6){
                    //dd($data);
                    //dd($pedidoAmazon);
                  }
                }

              }
              //dd($data);
              if(!$existe){
              $preu = explode(' ', $data[16]);//17
              //dd($preu[1]);
              $preu[1]= str_replace('.','',$preu[1]);
              $preu[1]= str_replace(',','.',$preu[1]);

              array_push($pedidoAmazon, array(
                  "PO" => $data[0],
                  "cantidad_producto" => $data[12],//15
                  "fecha" => date_format(date_create($hoy), 'Y-m-d'),
                  "transporte" => $transporte,
                  "direccion" => $data[2],
                  "ciudad" => $clientes[$data[2]]["ciudad"].' , '.$clientes[$data[2]]["cp"],
                  "calle" => $clientes[$data[2]]["calle"],
                  "bultos" => $bultos,
                  "observaciones" => "Entrega hasta ".$data[9],
                  "cliente_envio" => $data[2],
                  "PO_total" => $preu[1],
                  "numero_pedido" =>  'AM'.sprintf( '%05d', $lastID )
              ));
              }
            }

          }
          $cont++;
        }

      }
      $totalFinal = 0;
      //dd($pedidoAmazon);
      foreach ($pedidoAmazon as $p => $pAmazon) {
        $pedido = new Pedidos_wix_importados;
        if($p == 0){
          $pedido->entrada_principal = 1;
        }
        $pedido->numero_pedido = $lastID;
        $pedido->fecha_pedido = date_format(date_create($hoy), 'Y-m-d');
        //$pedido->hora_pedido = $fila[2];
        /* datos facturación */
        $pedido->cliente_facturacion = $pAmazon['direccion'];
        $pedido->pais_facturacion = 'España';
        $pedido->estado_facturacion = $clientes[$pAmazon['direccion']]['estado'];
        $pedido->ciudad_facturacion = $clientes[$pAmazon['direccion']]['ciudad'];
        $pedido->direccion_facturacion = $clientes[$pAmazon['direccion']]['calle'];
        $pedido->cp_facturacion = $clientes[$pAmazon['direccion']]['cp'];
        /* datos envio */
        $pedido->cliente_envio = $pAmazon['direccion'];
        $pedido->pais_envio = 'España';
        $pedido->estado_envio = $clientes[$pAmazon['direccion']]['estado'];
        $pedido->ciudad_envio = $clientes[$pAmazon['direccion']]['ciudad'];
        $pedido->direccion_envio = $clientes[$pAmazon['direccion']]['calle'];
        $pedido->cp_envio = $clientes[$pAmazon['direccion']]['cp'];
        /* datos contacto */
        //$pedido->telefono_comprador = $fila[15];
        //$pedido->correo_comprador = $fila[16];
        /* Adicional envío */
        $pedido->metodo_entrega = $pAmazon['transporte'];
        /* detalles producto*/
        $pedido->nombre_producto = $pAmazon['PO'];
        //$pedido->variante_producto = $fila[19];
        //$pedido->sku_producto = $fila[20];
        $pedido->cantidad_producto = $pAmazon['cantidad_producto'];
        $pedido->precio_producto = $pAmazon["PO_total"];
        //$pedido->peso_producto = $fila[23];
        //$pedido->texto_especial_producto = $fila[24];
        /* extras compra */
        /*$pedido->cupon = $fila[25];
        $pedido->envio = $fila[26];
        $pedido->tasas = $fila[27];*/
        $pedido->total = 0;
        /* Información metodo de pago */
        $pedido->forma_de_pago = 'transferenciabancaria';
        $pedido->pago = 'Paid';
        $pedido->orden_completada = 'fullfilled';
        $pedido->o_csv = 'AM';
        $pedido->bultos = $bultos;
        $pedido->save();

        $totalFinal += $pAmazon["PO_total"];

      }

       $pedidoFinal = Pedidos_wix_importados::where('o_csv', '=', 'AM')
                                            ->where('numero_pedido', '=', $lastID)
                                            ->get();

        $totalFinal = $totalFinal * 1.21;
        foreach ($pedidoFinal as $v => $actualizar) {
          $actualizar->total = $totalFinal;
          $actualizar->save();
        }

    		//plox();
    		$datos = array('pedido' => $pedidoAmazon);
    		// Inicializamos clase Dompdf
    		$nombre_pdf = "pedido_amazon";
    		$view = View::make('herramientas.albaran_amazon', $datos)->render();

    		// Inicializamos DOMPDF
    		$dompdf = new Dompdf();

    		// Renderizamos view::make para poder generar pdf
    		$dompdf->loadHtml($view);
    		$dompdf->set_option('enable_css_float',true);

    		// Renderizamos PDF
    		$dompdf->render();
    		// Enviamos resultado al navegador
    		return $dompdf->stream($nombre_pdf);

	}

  public function subir_actualizacion(Request $request){
      $repetidos = array();
      $all_rep= true;
      $success= array();
      $errors = array();
      $subidos= 0;
      Session::put('amazonSubidos', 0);

      Session::put('amazonRepetidos', $repetidos);
    if(isset($request["inputSeguridad"]) && $request["inputSeguridad"]=='565dsad4874#@3sfasf' && $request['csv']!=NULL){ //Controlamos que no venga vacío el fichero
      // 1. Validar formato documento importado
      if(strtolower($request['csv']->getClientOriginalExtension())=='xls'){ //Controlamos la extensión del fichero.
        // 2. Generar nombre del fichero
        $nombreFichero = 'excel_amazon_'.date('d-m-Y_H-i-s').'.'.$request->csv->getClientOriginalExtension();
        // 3. Subir fichero al directorio de archivos
        $request->csv->move(public_path('documentos/amazon'), $nombreFichero);
        // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
        //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
        //* el cleanup aquí mismo al cargar un nuevo fichero.

        // Paso 5: Bucle donde iremos subiendo los productos uno a uno.

        //  $productosexistentes = Productos::get();

        $contHechos = 0;
         Excel::load('documentos/amazon/'.$nombreFichero, function($archivo) use($repetidos, $all_rep, $subidos){

             $result=$archivo->get();
             foreach($result as $key => $value){

                  $all_rep = false;
                 if($value['asin']==''){ $value['asin']= ''; }
                 DB::table('Productos_amazon')
                    ->where('referencia' , $value['referencia'])
                    ->update([
                       'codigo_ean' => $value['codigo_ean'],
                       'asin' => $value['asin'],
                     ]);
                 $subidos++;


             }

            //dd($repetidos);
            Session::put('amazonRepetidos', $repetidos);
            Session::put('amazonSubidos', $subidos);

            //Session::push('amazonRepetidos',);
            //dd(session('amazonRepetidos'));

         })->get();

         $repetidos = session('amazonRepetidos');
         $subidos = session('amazonSubidos');

         array_push($success,'Fichero subido correctamente');

         if(count($repetidos)>0){
            array_push($success, count($repetidos).' codigo(s) EAN repetido(s), no se han subido');
         }
         if($subidos>0){
            array_push($success, $subidos.' Productos subidos correctamente');
         }
      }else array_push($errors,'Formato de fichero no válido.');
    }else array_push($errors,'No has subido fichero.');

    return View::make('herramientas/importar_excel_amazon', array('errors' => $errors, 'success' => $success));
  }



}
