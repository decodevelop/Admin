<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use View;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Productos;
Use Validator;
use Input;
use DateTime;
use Mail;
use App;
use PDF;
use Dompdf\Dompdf;
use Excel;
use File;
use PHPExcel_Worksheet_Drawing;

class ProductosController extends Controller
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
        $orderByType2 = "asc";
        $product_image = array();
        $aux = array();
        /*-------------- FILTROS --------------*/
        $where = "1=1 ";
        if(isset($getParams["nombre"]) && $getParams["nombre"]!="") $where .= " and nombre like '%".$getParams["nombre"]."%'";
        if(isset($getParams["skuAct"]) && $getParams["skuAct"]!="") $where .= " and skuActual like '%".$getParams["skuAct"]."%'";
        if(isset($getParams["color"]) && $getParams["color"]!="") $where .= " and color like '%".$getParams["color"]."%'";
        if(isset($getParams["material"]) && $getParams["material"]!="") $where .= " and material like '%".$getParams["material"]."%'";

        /*-------------- QUERY MySQL ---------------> */
        $listado_productos = DB::table('productos')->whereRaw($where)->orderBy($orderBy2,$orderByType2)->paginate(30);

        foreach ($listado_productos as $key => $productos) {
          if($key==0){
            $aux = $productos;
            $product_image[$productos->skuActual] = $productos->skuActual;
          }else{
            if(($productos->color == $aux->color)&&($productos->material == $aux->material)&&($productos->nombre == $aux->nombre)){
              $product_image[$productos->skuActual] = $aux->skuActual;
            }else{
              $aux = $productos;
              $product_image[$productos->skuActual] = $productos->skuActual;
            }
          }
        }
        return View::make('productos/inicio', array('listado_productos' => $listado_productos, 'filtros' => array($orderBy2, $orderByType2), 'product_image' => $product_image ));
    }

    /**
     * Carga el detalle del producto seleccionado.
     *
     * @return view
     */
    public function detalle($id){
        $detalles_producto = DB::table('productos')->find($id);
        return View::make('productos/detalle', array('detalles_producto' => $detalles_producto));
    }

    /**
     * Carga la vista para cambiar datos del detalle.
     *
     * @return view
     */
    public function modificar($id){
        $detalles_producto = DB::table('productos')->find($id);
        return View::make('productos/modificar', array('detalles_producto' => $detalles_producto));
    }
    /**
     * Elimina una foto principal si existe y vuelve a la pagina de modificar.
     *
     * @return view
     */
    public function eliminarPrincipal($id,$img){
        $resultado = false;

        if (File::exists(public_path('/imgProductos/'.$img)))
            $resultado = File::delete('imgProductos/'.$img);

        $detalles_producto = DB::table('productos')->find($id);
        return View::make('productos/detalle', array('detalles_producto' => $detalles_producto, 'resultadoBorradoPrincipal' => $resultado));
    }
    /**
     * Elimina una foto secundaria si existe y vuelve a la pagina de modificar.
     *
     * @return view
     */
    public function eliminarSecundaria($id,$skuActual,$img){
        $resultado = false;

        if (File::exists(public_path('/imgProductos/'.$skuActual.'/'.$img)))
            $resultado = unlink(public_path().'/imgProductos/'.$skuActual.'/'.$img);


        $detalles_producto = DB::table('productos')->find($id);
        return View::make('productos/detalle', array('detalles_producto' => $detalles_producto, 'resultadoBorradoSecundaria' => $resultado));
    }
    /**
     * Elimina una foto secundaria si existe y vuelve a la pagina de modificar.
     *
     * @return view
     */
    public function actualizar_detalle(Request $request){
        $resultado = false;
        $post = $request->all();
        $detalles_producto = Productos::find($post["id_detalle"]);

        if(isset($post["id_detalle"]) && $post["id_detalle"]!=''){
            if($post["nombre"]!='') $detalles_producto->nombre = $post["nombre"];
            if($post["descripcion"]!='') $detalles_producto->descripcion = $post["descripcion"];
            if($post["skuAnterior"]!='') $detalles_producto->skuAnterior = $post["skuAnterior"];
            if($post["skuActual"]!='') $detalles_producto->skuActual = $post["skuActual"];
            if($post["ean"]!='') $detalles_producto->ean = $post["ean"];
            if($post["stock"]!='') $detalles_producto->stock = $post["stock"];
            if($post["material"]!='') $detalles_producto->material = $post["material"];
            if($post["color"]!='') $detalles_producto->color = $post["color"];
            if($post["acabado"]!='') $detalles_producto->acabado = $post["acabado"];
            if($post["paisOrigen"]!='') $detalles_producto->paisOrigen = $post["paisOrigen"];
            if($post["largo"]!='') $detalles_producto->largo = $post["largo"];
            if($post["alto"]!='') $detalles_producto->alto = $post["alto"];
            if($post["ancho"]!='') $detalles_producto->ancho = $post["ancho"];
            if($post["diametro"]!='') $detalles_producto->diametro = $post["diametro"];
            if($post["peso"]!='') $detalles_producto->peso = $post["peso"];
            if($post["proveedor"]!='') $detalles_producto->proveedor = $post["proveedor"];
            if($post["tiempoEntrega"]!='') $detalles_producto->tiempoEntrega = $post["tiempoEntrega"];
            if($post["packaging"]!='') $detalles_producto->packaging = $post["packaging"];
            if($post["proveedorPackaging"]!='') $detalles_producto->proveedorPackaging = $post["proveedorPackaging"];
            if($post["nCajas"]!='') $detalles_producto->nCajas = $post["nCajas"];
            if($post["productoxCaja"]!='') $detalles_producto->productoxCaja = $post["productoxCaja"];
            if($post["largoEmpaquetado"]!='') $detalles_producto->largoEmpaquetado = $post["largoEmpaquetado"];
            if($post["altoEmpaquetado"]!='') $detalles_producto->altoEmpaquetado = $post["altoEmpaquetado"];
            if($post["anchoEmpaquetado"]!='') $detalles_producto->anchoEmpaquetado = $post["anchoEmpaquetado"];
            if($post["pesoEmpaquetado"]!='') $detalles_producto->pesoEmpaquetado = $post["pesoEmpaquetado"];
            if($post["montaje"]!='') $detalles_producto->montaje = $post["montaje"];
            if($post["instrucciones"]!='') $detalles_producto->instrucciones = $post["instrucciones"];
            if($post["lavado"]!='') $detalles_producto->lavado = $post["lavado"];
            if($post["pcoste"]!='') $detalles_producto->pcoste = $post["pcoste"];
            if($post["pbase"]!='') $detalles_producto->pbase = $post["pbase"];
            if($post["pbase_5"]!='') $detalles_producto->pbase_5 = $post["pbase_5"];
            if($post["pbase_6"]!='') $detalles_producto->pbase_6 = $post["pbase_6"];
            if($post["pbase_10"]!='') $detalles_producto->pbase_10 = $post["pbase_10"];
            if($post["pvprecomendado"]!='') $detalles_producto->pvprecomendado = $post["pvprecomendado"];
            if($post["pvpweb"]!='') $detalles_producto->pvpweb = $post["pvpweb"];

            try {
                $resultado = $detalles_producto->save();
            }catch(Exception $e){
                $resultado = false;
            }
        }

        return View::make('productos/detalle', array('detalles_producto' => $detalles_producto, 'resultadoActualizarDetalles' => $resultado));
    }
    /**
     * Sube una foto principal o secundaria al servidor.
     *
     * @return view
     */
    public function subirFoto($id,$skuActual,$tipo,Request $request){
        $resultado = false;


        $path_principal = public_path().'/imgProductos/';
        $path_secundaria = public_path().'/imgProductos/'.$skuActual.'/';
        $files = $request->file('file');

        if($tipo=='principal'){
            // Extraemos el nombre original
            $original_name = $files->getClientOriginalName();

            // Extraemos la extensión
            $file_extension = pathinfo($original_name)["extension"];

            $fileName = $skuActual.'.'.$file_extension;

            //Todo esto se hace por tema de permisos. Si solo creamos la foto, luego no podremos verla.
            $resultado = $files->move($path_principal, 'copia_'.$fileName); //Creamos una copia temporal.
            copy($path_principal.'copia_'.$fileName, $path_principal.$fileName) ; //Copiamos el archivo.
            unlink($path_principal.'copia_'.$fileName); //Borramos el temporal.

        }elseif($tipo=='secundaria'){
            //Extraemos los ficheros de img secundarias
            $fotosSecundarias = File::files($path_secundaria);
            $cantidadSecundarias = count($fotosSecundarias);
            var_dump($fotosSecundarias);

            $cantidadSecundarias+=1;
            $original_name = $files->getClientOriginalName();

            // Extraemos la extensión
            $file_extension = pathinfo($original_name)["extension"];

            $fileName = $skuActual.'_'.$cantidadSecundarias.'.'.$file_extension;

            //Todo esto se hace por tema de permisos. Si solo creamos la foto, luego no podremos verla.
            $resultado = $files->move($path_secundaria, 'copia_'.$fileName); //Creamos una copia temporal.
            copy($path_secundaria.'copia_'.$fileName, $path_secundaria.$fileName) ; //Copiamos el archivo.
            unlink($path_secundaria.'copia_'.$fileName); //Borramos el temporal.

        }

        $detalles_producto = DB::table('productos')->find($id);
        return View::make('productos/modificar', array('detalles_producto' => $detalles_producto, 'resultadoBorradoSecundaria' => $resultado));
    }
    /**
     * Sube una foto principal o secundaria al servidor.
     *
      * @return $documento_xsl;
     */
    public function gexcel_productos(Request $request){
        // Rutas de guardado ficheros excel
        $ruta_doc_xls = "documentos/albaranes/agrupados/";
        $nombre_xls = "";
        $fotoPrincipal = false;

        /* Guardar campos del excel */
        $campos_excel = array();
        if(isset($request->all()["E_fotoPrincipal"]) && $request->all()["E_fotoPrincipal"]!='') $fotoPrincipal = true;
        if(isset($request->all()["E_id"]) && $request->all()["E_id"]!='') array_push($campos_excel, $request->all()["E_id"]);
        if(isset($request->all()["E_nombre"]) && $request->all()["E_nombre"]!='') array_push($campos_excel, $request->all()["E_nombre"]);
        if(isset($request->all()["E_descripcion"]) && $request->all()["E_descripcion"]!='') array_push($campos_excel, $request->all()["E_descripcion"]);
        if(isset($request->all()["E_skuAnterior"]) && $request->all()["E_skuAnterior"]!='') array_push($campos_excel, $request->all()["E_skuAnterior"]);
        if(isset($request->all()["E_skuActual"]) && $request->all()["E_skuActual"]!='') array_push($campos_excel, $request->all()["E_skuActual"]);
        if(isset($request->all()["E_ean"]) && $request->all()["E_ean"]!='') array_push($campos_excel, $request->all()["E_ean"]);
        if(isset($request->all()["E_stock"]) && $request->all()["E_stock"]!='') array_push($campos_excel, $request->all()["E_stock"]);
        if(isset($request->all()["E_material"]) && $request->all()["E_material"]!='') array_push($campos_excel, $request->all()["E_material"]);
        if(isset($request->all()["E_color"]) && $request->all()["E_color"]!='') array_push($campos_excel, $request->all()["E_color"]);
        if(isset($request->all()["E_acabado"]) && $request->all()["E_acabado"]!='') array_push($campos_excel, $request->all()["E_acabado"]);
        if(isset($request->all()["E_paisOrigen"]) && $request->all()["E_paisOrigen"]!='') array_push($campos_excel, $request->all()["E_paisOrigen"]);
        if(isset($request->all()["E_largo"]) && $request->all()["E_largo"]!='') array_push($campos_excel, $request->all()["E_largo"]);
        if(isset($request->all()["E_alto"]) && $request->all()["E_alto"]!='') array_push($campos_excel, $request->all()["E_alto"]);
        if(isset($request->all()["E_ancho"]) && $request->all()["E_ancho"]!='') array_push($campos_excel, $request->all()["E_ancho"]);
        if(isset($request->all()["E_diametro"]) && $request->all()["E_diametro"]!='') array_push($campos_excel, $request->all()["E_diametro"]);;
        if(isset($request->all()["E_peso"]) && $request->all()["E_peso"]!='') array_push($campos_excel, $request->all()["E_peso"]);
        if(isset($request->all()["E_proveedor"]) && $request->all()["E_proveedor"]!='') array_push($campos_excel, $request->all()["E_proveedor"]);
        if(isset($request->all()["E_tiempoEntrega"]) && $request->all()["E_tiempoEntrega"]!='') array_push($campos_excel, $request->all()["E_tiempoEntrega"]);
        if(isset($request->all()["E_packaging"]) && $request->all()["E_packaging"]!='') array_push($campos_excel, $request->all()["E_packaging"]);
        if(isset($request->all()["E_proveedorPackaging"]) && $request->all()["E_proveedorPackaging"]!='') array_push($campos_excel, $request->all()["E_proveedorPackaging"]);
        if(isset($request->all()["E_nCajas"]) && $request->all()["E_nCajas"]!='') array_push($campos_excel, $request->all()["E_nCajas"]);
        if(isset($request->all()["E_productoxCaja"]) && $request->all()["E_productoxCaja"]!='') array_push($campos_excel, $request->all()["E_productoxCaja"]);
        if(isset($request->all()["E_largoEmpaquetado"]) && $request->all()["E_largoEmpaquetado"]!='') array_push($campos_excel, $request->all()["E_largoEmpaquetado"]);
        if(isset($request->all()["E_altoEmpaquetado"]) && $request->all()["E_altoEmpaquetado"]!='') array_push($campos_excel, $request->all()["E_altoEmpaquetado"]);
        if(isset($request->all()["E_anchoEmpaquetado"]) && $request->all()["E_anchoEmpaquetado"]!='') array_push($campos_excel, $request->all()["E_anchoEmpaquetado"]);
        if(isset($request->all()["E_pesoEmpaquetado"]) && $request->all()["E_pesoEmpaquetado"]!='') array_push($campos_excel, $request->all()["E_pesoEmpaquetado"]);
        if(isset($request->all()["E_montaje"]) && $request->all()["E_montaje"]!='') array_push($campos_excel, $request->all()["E_montaje"]);
        if(isset($request->all()["E_instrucciones"]) && $request->all()["E_instrucciones"]!='') array_push($campos_excel, $request->all()["E_instrucciones"]);
        if(isset($request->all()["E_lavado"]) && $request->all()["E_lavado"]!='') array_push($campos_excel, $request->all()["E_lavado"]);
        if(isset($request->all()["E_pcoste"]) && $request->all()["E_pcoste"]!='') array_push($campos_excel, $request->all()["E_pcoste"]);
        if(isset($request->all()["E_pbase"]) && $request->all()["E_pbase"]!='') array_push($campos_excel, $request->all()["E_pbase"]);
        if(isset($request->all()["E_pbase_5"]) && $request->all()["E_pbase_5"]!='') array_push($campos_excel, $request->all()["E_pbase_5"]);
        if(isset($request->all()["E_pbase_6"]) && $request->all()["E_pbase_6"]!='') array_push($campos_excel, $request->all()["E_pbase_6"]);
        if(isset($request->all()["E_pbase_10"]) && $request->all()["E_pbase_10"]!='') array_push($campos_excel, $request->all()["E_pbase_10"]);
        if(isset($request->all()["E_pvprecomendado"]) && $request->all()["E_pvprecomendado"]!='') array_push($campos_excel, $request->all()["E_pvprecomendado"]);
        if(isset($request->all()["E_pvpweb"]) && $request->all()["E_pvpweb"]!='') array_push($campos_excel, $request->all()["E_pvpweb"]);

        // Request con ids para rellenar excel
        $ids = json_decode($request->all()["ids"], true);


        // Generamos la estructura del XLS.
        return Excel::create('excel_productos', function($excel) use($ids,$campos_excel,$fotoPrincipal) {
             $excel->getDefaultStyle()
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
             $excel->getDefaultStyle()
                    ->getAlignment()
                    ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->sheet('Sheetname', function($sheet) use($ids,$campos_excel,$fotoPrincipal) {
                // headers del documento xls
                $header = [];
                $row = 1;

                // montamos header del documento
                $header = $campos_excel;
                if($fotoPrincipal) array_unshift($header, 'foto');

                //montamos el select
                $select = '';
                foreach ($campos_excel as $key => $campo) {
                    $select .= $campo;
                    if($key<count($campos_excel)-1) $select .=', ';
                }


                //Agregamos las rows
                foreach($ids as $key2 => $id){

                    //hacemos la consulta
                    $producto_actual = DB::table('productos')
                     ->select(DB::raw($select))
                     ->where('id', '=', $id["value"])
                     ->groupBy('id')
                     ->get();

                    //Recorremos el producto y lo convertimos en array.

                     $productoAmazonFinal = Array();
                     $skuActualGuardado = '';

                     foreach ($producto_actual[0] as $kProducto => $valorProducto) {
                        if(strtolower($kProducto) == 'montaje'){
                            $montado = '';
                            if ($valorProducto==0) $montado = 'No';
                            else $montado = 'Si';
                            $productoAmazonFinal['"'.$kProducto.'"'] = $montado;
                        }else $productoAmazonFinal['"'.$kProducto.'"'] = $valorProducto;

                     }
                     //Miramos Si existe la imagen y miramos el nombre
                     $imgActual = '';

                    $skuActualGuardado = DB::table('productos')
                     ->select(DB::raw('skuactual'))
                     ->where('id', '=', $id["value"])
                     ->groupBy('id')
                     ->get();
                     $skuActualGuardado = $skuActualGuardado[0]->skuactual;

                    if (File::exists(public_path('/imgProductos/'.$skuActualGuardado.'_1.jpg')))
                            $imgActual = $skuActualGuardado.'_1.jpg';
                    elseif(File::exists(public_path('/imgProductos/'.$skuActualGuardado.'_1.jpeg')))
                            $imgActual = $skuActualGuardado.'_1.jpeg';
                    elseif(File::exists(public_path('/imgProductos/'.$skuActualGuardado.'_1.png')))
                            $imgActual = $skuActualGuardado.'_1.png';

                     //Metemos la imagen principal
                    if($fotoPrincipal && $imgActual!=''){
                        $objDrawing = new PHPExcel_Worksheet_Drawing;

                        // Imagen
                        $objDrawing->setPath(public_path('/imgProductos/'.$imgActual)); //your image path
                        $objDrawing->setName('imgproduct');
                        $objDrawing->setWorksheet($sheet);

                        $objDrawing->setCoordinates('A'.($row+1)); //Utilizamos row+1 ya que la primera es el header del fichero
                        $objDrawing->setResizeProportional();
                        $objDrawing->setOffsetX($objDrawing->getWidth() - $objDrawing->getWidth() / 5);
                        $objDrawing->setOffsetY(10);

                        $objDrawing->setWidth(100);
                        $objDrawing->setHeight(100);

                        // Poner auto size para cambiar el tamaño de las celdas que no sean
                        $sheet->setAutoSize(true);
                        // cambiar medida de la celda
                        $sheet->setSize('A'.($row+1), ($objDrawing->getWidth() / 5), $objDrawing->getHeight());

                        //Si tiene imagen tendremos que poner un espacio para desplazar la primera parte.
                        array_unshift($productoAmazonFinal, '');
                    }

                    //Aumentamos la row y metemos el producto
                    $row++;

                    $sheet->row($row, $productoAmazonFinal);

                }

                //Acabamos el excel
                $header = array_map('strtoupper', $header);
                $sheet->fromArray($header, null, 'A1', true);
                $sheet->getStyle("A1:AK1")->getFont()->setBold(true);


            });
        })->export('xls');
    }
    /**
     * Muestra la vista del taller con los pedidos por parte de la empresa.
     *
      * @return view
     */
    public function taller(Request $request){
        $productos_Taller = array();
        $getParams = $request->query();
        $filtros = ' ';

        /* FILTROS */
        if(isset($getParams["fecha_pedido"]) && $getParams["fecha_pedido"]!=NULL) $filtros.='fecha_creacion>="'.$getParams["fecha_pedido"].' 00:00:00"';
        else{
            $fecha = date('Y-m-d');
            $nuevafecha = strtotime ( '-7 day' , strtotime ( $fecha ) ) ;
            $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
            $filtros.='fecha_creacion>="'.$nuevafecha.' 00:00:00"';
        }
        if(isset($getParams["fecha_pedido_fin"]) && $getParams["fecha_pedido_fin"]!=NULL) $filtros.=' and fecha_creacion<="'.$getParams["fecha_pedido_fin"].' 23:59:59"';

        if(isset($getParams["estadoPedido"])) $filtros.=' and estado='.$getParams["estadoPedido"];
        else $filtros.=' and estado=0';


        $resultado_taller = DB::select("select * from taller where ".$filtros);

        if(!empty($resultado_taller) && count($resultado_taller)>0){

            foreach ($resultado_taller as $key => $pr) {
                $productoSelect = DB::select("select * from productos where id=".$pr->id);
                $productoSelect = $productoSelect[0];
                array_push($productos_Taller, array(
                    'id_producto' => $productoSelect->id,
                    'id_producto_taller' => $pr->id,
                    'ref_num_pedido' => $pr->ref_num_pedido,
                    'skuActual' => $productoSelect->skuActual,
                    'nombre' => $productoSelect->nombre,
                    'descripcion' => $productoSelect->descripcion,
                    'acabado' => $productoSelect->acabado,
                    'material' => $productoSelect->material,
                    'largo' => $productoSelect->largo,
                    'ancho' => $productoSelect->ancho,
                    'alto' => $productoSelect->alto,
                    'cantidad' => $pr->cantidad,
                    'fecha_creacion' => $pr->fecha_creacion,
                    'fecha_fin' => $pr->fecha_fin,
                    'cliente' => $pr->cliente,
                    'estado' => $pr->estado

                ));
            }
        }

        return View::make('productos/taller', array('productosTaller' => $productos_Taller));
    }
    /**
     * Actualiza el estado de un pedido del taller.
     *
      * @return view
     */
    public function actEstadoTaller(Request $request,$id_pedido_taller,$clase_Taller){
        $resultadosAjax = 0;

        if($id_pedido_taller){
            $resultadosAjax = DB::table('taller')->where('id', $id_pedido_taller)->update(['estado' => 1]);
        }
        return array("resultado" => $resultadosAjax, "clase_taller" => $clase_Taller);
    }
    /**
     * Reedirección al formulario de subida.
     *
      * @return view
     */
    public function formularioSubidaProductos(Request $request){
        return View::make('herramientas/importar_excel_productos');
    }
    /**
     * Sube los productos de un excel a la base de datos.
     *
      * @return view
     */
    public function subirExcelProductos(Request $request){
        $errors = array();
      /*  $pE = Productos::get();
        dd($pE);
        exit();*/
        if(isset($request["inputSeguridad"]) && $request["inputSeguridad"]=='565dsad4874#@3sfasf' && $request['csv']!=NULL){ //Controlamos que no venga vacío el fichero
            // 1. Validar formato documento importado
            if(strtolower($request['csv']->getClientOriginalExtension())=='xlsx'){ //Controlamos la extensión del fichero.

            // 2. Generar nombre del fichero
            $nombreFichero = 'excel_productes_'.date('d-m-Y_H-i-s').'.'.$request->csv->getClientOriginalExtension();

            // 3. Subir fichero al directorio de archivos
            $request->csv->move(public_path('documentos/productos'), $nombreFichero);

            // Paso 3.1: Eliminar documentos anteriores a 1 semana. (esto está en pedidos también)
            //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
            //* el cleanup aquí mismo al cargar un nuevo fichero.

            // 4. Abrimos el documento para realizar la lectura.
            //$documento_csv = fopen(public_path('documentos/productos').'/'.$nombreFichero,"r");

            // Paso 5: Bucle donde iremos subiendo los productos uno a uno.

          //  $productosexistentes = Productos::get();

            $contHechos = 0;
             Excel::load('documentos/productos/'.$nombreFichero, function($archivo)
              {
               $result=$archivo->get();
            //   dd($result[0]);
               foreach($result[0] as $key => $value){

                 if (Productos::where('skuActual', '=', $value['sku_actual'])->exists()){
                  //Enviar mensaje al finalizar con el numero de repetidos.
                 }else{

                    DB::table('productos')->insert(
                          [
                                'nombre' => $value['nombre'],
                                'descripcion' => $value['descripcion'],
                                'skuAnterior' => $value['sku_anterior'],
                                'skuActual' => $value['sku_actual'],
                                'ean' => $value['ean'],
                                'stock' => $value['stock'],
                                'material' => $value['material'],
                                'color' => $value['color'],
                                'acabado' => $value['acabado'],
                                'paisOrigen' => $value['pais_origen'],
                                'largo' => $value['largo_producto_cm'],
                                'alto' => $value['alto_producto_cm'],
                                'ancho' => $value['ancho_producto_cm'],
                                'diametro' => $value['diametro_producto_y_otras_medidas_cm'],
                                'peso' => $value['peso_producto_gr'],
                                'proveedor' => $value['proveedor'],
                                'tiempoEntrega' => $value['tiempo_de_entrega'],
                                'packaging' => $value['packaging'],
                                'proveedorPackaging' => $value['proveedor'],
                                'nCajas' => $value['no_de_cajas'],
                                'productoxCaja' => 'null',
                                'largoEmpaquetado' => $value['largo_empaquetado_cm_caja_1'],
                                'altoEmpaquetado' => $value['alto_empaquetado_cm_caja_1'],
                                'anchoEmpaquetado' => $value['ancho_empaquetado_cm_caja_1'],
                                'pesoEmpaquetado' => $value['peso_producto_empaquetado_gr_caja_1'],
                                'montaje' => $value['montaje'],
                                'instrucciones' => $value['instrucciones'],
                                'lavado' => $value['lavado'],
                                'pcoste' => $value['p_coste'],
                                'pbase' => $value['p_base'],
                                'pbase_5' => $value['p_base'] * 1.05,
                                'pbase_6' => $value['p_base'] * 1.06,
                                'pbase_10' => $value['p_base'] * 1.10,
                                'pvprecomendado' => $value['pvp_recomendado'],
                                'pvpweb' => $value['pvp_web'],



                        ]);

                    }
                }


              })->get();

            }else array_push($errors,'Formato de fichero no válido.');
        }else array_push($errors,'No has subido fichero.');

        return View::make('herramientas/importar_excel_productos', array('errors' => $errors));
    }

    /**
     * Guarda en el carrito productos a imprimir.
     *
     * @return view
     */
     public function guardarCarrito(Request $request){

        $producto_guardar = json_decode($request->all()["productos_amazon_carrito"],true);

        if (!Session::exists('productosCarrito')) Session::put('productosCarrito', Array());
        Session::push('productosCarrito',$producto_guardar);

        return back();
    }

    public function verStock(){
      $errors= array();
      $success= array();

      return View::make('productos/stock_web', array('errors' => $errors, 'success' => $success));


    }

    public function cambiosStock(Request $request){
      $errors= array();
      $success= array();
      $getParams = $request->query();
      $orderBy2 = "id";
      $orderByType2 = "asc";

      /*-------------- FILTROS --------------*/
      $where = "1=1 and proveedor IS NOT NULL and '' not like ean";
      if(isset($getParams["nombre"]) && $getParams["nombre"]!="") $where .= " and nombre like '%".$getParams["nombre"]."%'";
      if(isset($getParams["skuAct"]) && $getParams["skuAct"]!="") $where .= " and skuActual like '%".$getParams["skuAct"]."%'";
      if(isset($getParams["color"]) && $getParams["color"]!="") $where .= " and color like '%".$getParams["color"]."%'";
      if(isset($getParams["material"]) && $getParams["material"]!="") $where .= " and material like '%".$getParams["material"]."%'";

      /*-------------- QUERY MySQL ---------------> */
      $listado_productos = DB::table('productos')->whereRaw($where)->orderBy($orderBy2,$orderByType2)->paginate(50);

      return View::make('productos/cambiar_stock', array('listado_productos' => $listado_productos, 'filtros' => array($orderBy2, $orderByType2) ));

    }

    public function subirCambioStock(Request $request){
      $errors= array();
      $success= array();
    	// Primero creamos un ID de conexión a nuestro servidor
    	$cid = ftp_connect("ftp.mesitadenoche.com");
    	// Luego creamos un login al mismo con nuestro usuario y contraseña
    	$resultado = ftp_login($cid, "iykwxs","atWXu2BURSb8");
    	// Comprobamos que se creo el Id de conexión y se pudo hacer el login
    	if ((!$cid) || (!$resultado)) {
    		 array_push($errors,'Fallo de conexión');
    	}else{
        // Cambiamos a modo pasivo, esto es importante porque, de esta manera le decimos al
      	//servidor que seremos nosotros quienes comenzaremos la transmisión de datos.
      	ftp_pasv ($cid, true) ;
      	// Nos cambiamos al directorio, donde queremos subir los archivos, si se van a subir a la raíz
      	// esta por demás decir que este paso no es necesario. En mi caso uso un directorio llamado boca
      	ftp_chdir($cid, "/public_html");
      	// Tomamos el nombre del archivo a transmitir, pero en lugar de usar $_POST, usamos $_FILES que le indica a PHP
      	// Que estamos transmitiendo un archivo, esto es en realidad un matriz, el segundo argumento de la matriz, indica
      	// el nombre del archivo
        //return ftp_pwd($cid);
      	$local = $_FILES["archivo"]["name"];
      	// Este es el nombre temporal del archivo mientras dura la transmisión
      	$remoto = $_FILES["archivo"]["tmp_name"];
      	// El tamaño del archivo
      	$tama = $_FILES["archivo"]["size"];
      	// Juntamos la ruta del servidor con el nombre real del archivo
      	$ruta = "/public_html/stocks/" . $local;
      	// Verificamos si no hemos excedido el tamaño del archivo

      		// Verificamos si ya se subio el archivo temporal
      		if (is_uploaded_file($remoto)){
      			// copiamos el archivo temporal, del directorio de temporales de nuestro servidor a la ruta que creamos
      			//copy($remoto, $ruta);

            if (ftp_put($cid, $ruta,$remoto, FTP_ASCII)) {
             //echo "se ha cargado $file con éxito\n";
             array_push($success ,'se ha cargado con exito en mesitasdenoche <script>window.open("https://mesitadenoche.com/act_prod.php?codsecprod=856954123695", "_blank");</script> ');
            } else {
             //echo "Hubo un problema durante la transferencia de $file\n";
             array_push($errors,"MN: error al cargar: ".$local);
            }

      		}
      		// Sino se pudo subir el temporal
      		else {
      			array_push($errors,"MN: no se pudo subir el archivo ".$local);
      		}

      	ftp_close($cid);

      }


      $cid = ftp_connect("31.200.244.43");
    	// Luego creamos un login al mismo con nuestro usuario y contraseña
    	$resultado = ftp_login($cid, "cajftp","ftp(cajas2016)");
    	// Comprobamos que se creo el Id de conexión y se pudo hacer el login
    	if ((!$cid) || (!$resultado)) {
    		 array_push($errors,'Fallo de conexión');
    	}else{
        // Cambiamos a modo pasivo, esto es importante porque, de esta manera le decimos al
      	//servidor que seremos nosotros quienes comenzaremos la transmisión de datos.
      	ftp_pasv ($cid, true) ;
      	// Nos cambiamos al directorio, donde queremos subir los archivos, si se van a subir a la raíz
      	// esta por demás decir que este paso no es necesario. En mi caso uso un directorio llamado boca
      	ftp_chdir($cid, "/httpdocs");
      	// Tomamos el nombre del archivo a transmitir, pero en lugar de usar $_POST, usamos $_FILES que le indica a PHP
      	// Que estamos transmitiendo un archivo, esto es en realidad un matriz, el segundo argumento de la matriz, indica
      	// el nombre del archivo
        //return ftp_pwd($cid);
      	$local = $_FILES["archivo"]["name"];
      	// Este es el nombre temporal del archivo mientras dura la transmisión
      	$remoto = $_FILES["archivo"]["tmp_name"];
      	// El tamaño del archivo
      	$tama = $_FILES["archivo"]["size"];
      	// Juntamos la ruta del servidor con el nombre real del archivo
      	$ruta = "/httpdocs/stocks/" . $local;
      	// Verificamos si no hemos excedido el tamaño del archivo

      		// Verificamos si ya se subio el archivo temporal
      		if (is_uploaded_file($remoto)){
      			// copiamos el archivo temporal, del directorio de temporales de nuestro servidor a la ruta que creamos
      			//copy($remoto, $ruta);

            if (ftp_put($cid, $ruta,$remoto, FTP_ASCII)) {
             //echo "se ha cargado $file con éxito\n";
             array_push($success ,'se ha cargado con exito en Cajasdemadera <script>window.open("https://Cajasdemadera.com/act_prod.php?codsecprod=856954123695", "_blank");</script> ');
            } else {
             //echo "Hubo un problema durante la transferencia de $file\n";
             array_push($errors,"CA: error al cargar: ".$local);
            }

      		}
      		// Sino se pudo subir el temporal
      		else {
      			array_push($errors,"CA: no se pudo subir el archivo ".$local);
      		}

      	ftp_close($cid);

      }
      //CABECEROS:
      $cid = ftp_connect("31.200.244.46");
    	// Luego creamos un login al mismo con nuestro usuario y contraseña
    	$resultado = ftp_login($cid, "cabftp","ftp(cabeceros2017)");
    	// Comprobamos que se creo el Id de conexión y se pudo hacer el login
    	if ((!$cid) || (!$resultado)) {
    		 array_push($errors,'Fallo de conexión');
    	}else{
        // Cambiamos a modo pasivo, esto es importante porque, de esta manera le decimos al
      	//servidor que seremos nosotros quienes comenzaremos la transmisión de datos.
      	ftp_pasv ($cid, true) ;
      	// Nos cambiamos al directorio, donde queremos subir los archivos, si se van a subir a la raíz
      	// esta por demás decir que este paso no es necesario. En mi caso uso un directorio llamado boca
      	ftp_chdir($cid, "/httpdocs");
      	// Tomamos el nombre del archivo a transmitir, pero en lugar de usar $_POST, usamos $_FILES que le indica a PHP
      	// Que estamos transmitiendo un archivo, esto es en realidad un matriz, el segundo argumento de la matriz, indica
      	// el nombre del archivo
        //return ftp_pwd($cid);
      	$local = $_FILES["archivo"]["name"];
      	// Este es el nombre temporal del archivo mientras dura la transmisión
      	$remoto = $_FILES["archivo"]["tmp_name"];
      	// El tamaño del archivo
      	$tama = $_FILES["archivo"]["size"];
      	// Juntamos la ruta del servidor con el nombre real del archivo
      	$ruta = "/httpdocs/stocks/" . $local;
      	// Verificamos si no hemos excedido el tamaño del archivo

      		// Verificamos si ya se subio el archivo temporal
      		if (is_uploaded_file($remoto)){
      			// copiamos el archivo temporal, del directorio de temporales de nuestro servidor a la ruta que creamos
      			//copy($remoto, $ruta);

            if (ftp_put($cid, $ruta,$remoto, FTP_ASCII)) {
             //echo "se ha cargado $file con éxito\n";
             array_push($success ,'se ha cargado con exito en Cabeceros <script>window.open("https://cabeceros.com/act_prod.php?codsecprod=856954123695", "_blank");</script> ');
            } else {
             //echo "Hubo un problema durante la transferencia de $file\n";
             array_push($errors,"CB: error al cargar: ".$local);
            }

      		}
      		// Sino se pudo subir el temporal
      		else {
      			array_push($errors,"CB: no se pudo subir el archivo ".$local);
      		}

      	ftp_close($cid);

      }

      //LATETEDELIT:
      $cid = ftp_connect("ftp.latetedelit.fr");
    	// Luego creamos un login al mismo con nuestro usuario y contraseña
    	$resultado = ftp_login($cid, "teteftp@latetedelit.fr","tetedelit2017");
    	// Comprobamos que se creo el Id de conexión y se pudo hacer el login
    	if ((!$cid) || (!$resultado)) {
    		 array_push($errors,'Fallo de conexión');
    	}else{
        // Cambiamos a modo pasivo, esto es importante porque, de esta manera le decimos al
      	//servidor que seremos nosotros quienes comenzaremos la transmisión de datos.
      	ftp_pasv ($cid, true) ;
      	// Nos cambiamos al directorio, donde queremos subir los archivos, si se van a subir a la raíz
      	// esta por demás decir que este paso no es necesario. En mi caso uso un directorio llamado boca
      	ftp_chdir($cid, "/public_html");
      	// Tomamos el nombre del archivo a transmitir, pero en lugar de usar $_POST, usamos $_FILES que le indica a PHP
      	// Que estamos transmitiendo un archivo, esto es en realidad un matriz, el segundo argumento de la matriz, indica
      	// el nombre del archivo
        //return ftp_pwd($cid);
      	$local = $_FILES["archivo"]["name"];
      	// Este es el nombre temporal del archivo mientras dura la transmisión
      	$remoto = $_FILES["archivo"]["tmp_name"];
      	// El tamaño del archivo
      	$tama = $_FILES["archivo"]["size"];
      	// Juntamos la ruta del servidor con el nombre real del archivo
      	$ruta = "/public_html/stocks/" . $local;
      	// Verificamos si no hemos excedido el tamaño del archivo

      		// Verificamos si ya se subio el archivo temporal
      		if (is_uploaded_file($remoto)){
      			// copiamos el archivo temporal, del directorio de temporales de nuestro servidor a la ruta que creamos
      			//copy($remoto, $ruta);

            if (ftp_put($cid, $ruta,$remoto, FTP_ASCII)) {
             //echo "se ha cargado $file con éxito\n";
             array_push($success ,'se ha cargado con exito en Tetedelit <script>window.open("https://latetedelit.fr/act_prod.php?codsecprod=856954123695", "_blank");</script> ');
            } else {
             //echo "Hubo un problema durante la transferencia de $file\n";
             array_push($errors,"TT: error al cargar: ".$local);
            }

      		}
      		// Sino se pudo subir el temporal
      		else {
      			array_push($errors,"TT: no se pudo subir el archivo ".$local);
      		}

      	ftp_close($cid);

      }

      return View::make('productos/stock_web', array('errors' => $errors, 'success' => $success));

    }

    public function guardarStock(Request $request){
      $producto = json_decode($request->all()["producto"],true);
      //dd($producto);

      if(isset($producto[0]["codigo_ean"]) && $producto[0]["codigo_ean"]!=''){
        /*-------------- FILTROS --------------*/
        $where = "1=1 and ean = ".$producto[0]["codigo_ean"];
        /*-------------- QUERY MySQL ---------------> */
        $p_comanda = DB::table('productos')->whereRaw($where)->get();
        $p_comanda = $p_comanda[0];
        if(($producto[0]["stock"] < $producto[0]["stockControl"]) && ($p_comanda->esperaStock==0)){
          $productos = DB::table('productos')
                              ->whereRaw('ean = "'.$producto[0]["codigo_ean"].'"')
                              ->update(['ean' => $producto[0]["codigo_ean"],
                                        'stock' => $producto[0]["stock"],
                                        'stockControl' => $producto[0]["stockControl"],
                                        'pedidoMinim' => $producto[0]["pedidoMinim"],
                                        'esperaStock' => 1]);
          $p_comanda = DB::table('productos')->whereRaw($where)->get();
          $p_comanda = $p_comanda[0];
          // Mailing
    			//$correo_comercial = Auth::user()->email;
          $correo_comercial = 'developer@decowood.es';
    			$titulo = "Nueva comanda de ".$p_comanda->nombre." x ".$p_comanda->pedidoMinim;
    			$email_cliente = 'developer@decowood.es';
          //  dd($correo_comercial);
    			// Parametros para el mailing
    			//$parametros = array("pedido" => $pedido->toArray(), 'productos' => $productos_pedido->toArray());
          $parametros = array("producto" => $p_comanda);
    			// Se envia mensaje al cliente
    			/*Mail::send('mail.comanda_stock', $parametros, function($message) use($email_cliente,$titulo)
    			{
    				$message->from('info@decowood.es', 'Nueva comanda');
    				$message->to($email_cliente, 'Información')->subject($titulo);
    			});*/

    			// Se envia copia del mensaje al administrador y al usuario que envia.
    			/*Mail::send('mail.informar_envio_plantilla', $parametros, function($message) use($correo_comercial)
    			{
    				$message->from('info@decowood.es', 'Información de su PEDIDO');
    				$message->to($correo_comercial, 'Información')->subject('Información (COPIA)');
    			});*/

        }else{
          $productos = DB::table('productos')
                              ->whereRaw('ean = "'.$producto[0]["codigo_ean"].'"')
                              ->update(['ean' => $producto[0]["codigo_ean"],
                                        'stock' => $producto[0]["stock"],
                                        'stockControl' => $producto[0]["stockControl"],
                                        'pedidoMinim' => $producto[0]["pedidoMinim"]]);
        }






      }


    }

    public function generarCsvStock(){

      /*-------------- FILTROS --------------*/
      $where = "1=1 and proveedor IS NOT NULL and '' not like ean";
      /*-------------- QUERY MySQL ---------------> */
      $listado_productos = DB::table('productos')->whereRaw($where)->get();
      //foreach listado productos
      foreach ($listado_productos as $num => $prd) {
      $csv[$num] = array($prd->stock,$prd->ean);
      }

      //  dd($csv);

            return Excel::create('stock_csv', function($excel) use($csv) {
              $excel->getDefaultStyle()
                     ->getAlignment()
                     ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $excel->getDefaultStyle()
                     ->getAlignment()
                     ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

              $excel->sheet('pedido', function($sheet) use($csv) {
                // headers del documento xls
                $header = [];
                $row = 1;

                //Crear headers
                //añadimos las rows
                //dd($productos_amazon);
                //$csv2= implode(';', $csv);
                //$csv3= array($csv2);
                //dd($csv3);

                foreach ($csv as $csv_valor) {
                  $sheet->row($row , $csv_valor );
                  $row++;
                }



                //$header = array_map('strtoupper', $header_valor);
                $sheet->fromArray('', null, 'A1', true);
                //$sheet->getStyle("A1:D1")->getFont()->setBold(true);

              });


            })->export('csv');




      }

    public function enviarComanda(){
      // Mailing
			//$correo_comercial = Auth::user()->email;
      $correo_comercial = 'developer@decowood.es';
			$titulo = "Hola, tu pedido ha salido enviado.";
			$email_cliente = 'support@decowood.es';
      //  dd($correo_comercial);
			// Parametros para el mailing
			//$parametros = array("pedido" => $pedido->toArray(), 'productos' => $productos_pedido->toArray());
      //$parametros = array('');
      $where = "1=1 and ean = '0638097209701'";
      $p_comanda = DB::table('productos')->whereRaw($where)->get();
      $p_comanda = $p_comanda[0];
      $parametros = array("producto" => $p_comanda);
      // Se envia mensaje al cliente
			/*Mail::send('mail.informar_envio_plantilla', $parametros, function($message) use($email_cliente)
			{
				$message->from('info@decowood.es', 'Información de su PEDIDO');
				$message->to($email_cliente, 'Información')->subject('Información');
			});*/

			// Se envia copia del mensaje al administrador y al usuario que envia.
			/*Mail::send('mail.informar_envio_plantilla', $parametros, function($message) use($correo_comercial)
			{
				$message->from('info@decowood.es', 'Información de su PEDIDO');
				$message->to($correo_comercial, 'Información')->subject('Información (COPIA)');
			});*/


      /*-------------- FILTROS --------------*/
      $where = "1=1 and proveedor IS NOT NULL and '' not like ean";
      /*-------------- QUERY MySQL ---------------> */
      $listado_productos = DB::table('productos')->whereRaw($where)->get();
      //foreach listado productos
      foreach ($listado_productos as $num => $prd) {
        $csv[$num] = array($prd->stock,$prd->ean);
      }

      //  dd($csv);

      $file = Excel::create('stock_csv', function($excel) use($csv) {
              $excel->getDefaultStyle()
                     ->getAlignment()
                     ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $excel->getDefaultStyle()
                     ->getAlignment()
                     ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

              $excel->sheet('pedido', function($sheet) use($csv) {
                // headers del documento xls
                $header = [];
                $row = 1;

                //Crear headers
                //añadimos las rows
                //dd($productos_amazon);
                //$csv2= implode(';', $csv);
                //$csv3= array($csv2);
                //dd($csv3);

                foreach ($csv as $csv_valor) {
                  $sheet->row($row , $csv_valor );
                  $row++;
                }



                //$header = array_map('strtoupper', $header_valor);
                $sheet->fromArray('', null, 'A1', true);
                //$sheet->getStyle("A1:D1")->getFont()->setBold(true);

              });


            });




      Mail::send('mail.stock_agotado', $parametros, function($message) use($file)
      {
        $message->from('info@decowood.es', 'Info Stock');
        $message->to('info@decowood.es', 'Información')->subject('Alerta Stock agotado(PRUEBA)');
        $message->cc('sandra@decowood.es', 'Sandra');
        $message->bcc('developer@decowood.es', 'Developer');
        $message->attach($file->store("csv",false,true)['full']);
      });
			$mensaje = "El pedido se ha actualizado, y se ha enviado una notificación al correo del cliente.";
    }

    public function comandaRecibida(Request $request){
        $producto = json_decode($request->all()["producto"],true);
        $productos = DB::table('productos')
                            ->whereRaw('ean = "'.$producto[0]["codigo_ean"].'"')
                            ->update(['ean' => $producto[0]["codigo_ean"],
                                      'stock' => $producto[0]["stock"]+$producto[0]["pedidoMinim"],
                                      'stockControl' => $producto[0]["stockControl"],
                                      'pedidoMinim' => $producto[0]["pedidoMinim"],
                                      'esperaStock' => 0]);
    }

}
