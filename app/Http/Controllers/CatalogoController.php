<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use View;
use Illuminate\Support\Facades\DB;
use App\Pedidos_wix_importados;
use App\Seguimiento_pedidos;
use App\User;
use App\PrestaShopWebservice;

use App\Colores;
use App\Colores_productos_b;
use App\Acabados;
use App\Acabados_productos_b;
use App\Fabricantes;
use App\Proveedores;
use App\Materiales;
use App\Materiales_productos_b;
use App\Categorias;
use App\Categorias_productos_b;
use App\Imagenes;
use App\Productos_base;
use App\Medidas;
use App\Webs;
use App\Productos;
use App\Stocks;
use App\Precios;

Use Validator;
use Input;
use DateTime;
use Mail;
use App;
use PDF;
use Dompdf\Dompdf;
use Excel;

class CatalogoController extends Controller
{
    /**
     * Constructor y middleware
     * @return void(true/false auth)
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Reedirección al formulario de subida.
     *
      * @return view
     */
    public function formularioSubidaProductos(Request $request){
        return View::make('herramientas/importar_excel_productos');
    }

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
               foreach($result as $key => $value){
                 //dd($value['sku_base']);
                 //Se busca si el producto base existe, si no se creará y se buscarán sus colores, medidas, fabricante... en caso de no existir se tendrán que crear también
                 if (!Productos_base::where('sku_base', '=', $value['sku_base'])->exists()){
                   $base = new Productos_base;
                   $base->nombre = $value['nombre'];
                   $base->descripcion_general = $value['descripcion_general'];
                   $base->sku_base = $value['sku_base'];
                   $base->save();
                   //...
                   $colores_a = explode(",", $value["colores"]);
                   $acabados_a = explode(",", $value["acabados"]);
                   $materiales_a = explode(",", $value["materiales"]);
                   $categorias_a = explode(",", $value["categorias"]);
                   $webs_a = explode(",", $value["webs"]);

                   foreach ($colores_a as $valor_color) {

                     if(!Colores::where('nombre', '=', $valor_color)->exists()){
                       $color = new Colores;
                       $color->nombre = $valor_color;
                       $color->save();
                     }else{
                       $color = Colores::where('nombre', '=', $valor_color)->first();
                     }

                     $color_base = new Colores_productos_b;
                     $color_base->id_base = $base->id;
                     $color_base->id_color = $color->id;

                     $color_base->save();


                   }


                   foreach ($acabados_a as $valor_acabado) {

                     if(!Acabados::where('nombre', '=', $valor_acabado)->exists()){
                       $acabado = new Acabados;
                       $acabado->nombre = $valor_acabado;
                       $acabado->save();
                     }else{
                      $acabado = Acabados::where('nombre', '=', $valor_acabado)->first();
                     }
                     $acabado_base = new Acabados_productos_b;
                     $acabado_base->id_base = $base->id;
                     $acabado_base->id_acabado = $acabado->id;

                     $acabado_base->save();
                   }

                   foreach ($materiales_a as $valor_material) {

                     if(!Materiales::where('nombre', '=', $valor_material)->exists()){
                       $material = new Materiales;
                       $material->nombre = $valor_color;
                       $material->save();
                     }else{
                      $material = Materiales::where('nombre', '=', $valor_material)->first();
                     }

                     $material_base = new Materiales_productos_b;
                     $material_base->id_base = $base->id;
                     $material_base->id_material = $material->id;

                     $material_base->save();

                   }

                   $count = 0;
                   foreach ($categorias_a as $valor_categoria) {

                     if(!Categorias::where('nombre', '=', $valor_categoria)->exists()){
                       $categoria = new Categorias;
                       $categoria->nombre = $valor_categoria;
                       $categoria->save();
                     }else{
                      $categoria = Categorias::where('nombre', '=', $valor_categoria)->first();
                     }

                     $categoria_base = new Categorias_productos_b;
                     $categoria_base->id_base = $base->id;
                     $categoria_base->id_categoria = $categoria->id;
                     if($count == 0){
                       $categoria_base->principal = 1;
                     }
                     $categoria_base->save();
                     $count++;
                   }



                 }else{
                   $base = Productos_base::where('sku_base', '=', $value['sku_base'])->first();

                 }

                 if (!Productos::where('sku', '=', $value['sku'])->exists()){

                    $producto = new Productos;

                    $producto->nombre_final = $value['nombre_final'];
                    $producto->sku = $value['sku'];
                    $producto->descripcion = $value['descripcion'];
                    $producto->ean = $value['ean'];
                    $producto->asin = $value['asin'];

                    $producto->id_base = $base->id;

                    $producto->save();

                    $medida = new Medidas;

                    $medida->largo = $value['largo'];
                    $medida->alto = $value['alto'];
                    $medida->ancho = $value['ancho'];
                    $medida->peso = $value['peso'];
                    $medida->diametro = $value['diametro'];
                    $medida->largo_packagin = $value['largo_packagin'];
                    $medida->alto_packagin = $value['alto_packagin'];
                    $medida->ancho_packagin = $value['ancho_packagin'];
                    $medida->peso_packagin = $value['peso_packagin'];
                    $medida->diametro_packagin = $value['diametro_packagin'];
                    $medida->id_producto = $producto->id;

                    $medida->save();

                    $precio = new Precios;

                    $precio->precio_coste = $value['precio_coste'];
                    $precio->precio_transporte = $value['precio_transporte'];
                    $precio->precio_b2b = $value['precio_b2'];
                    $precio->pvp = $value['pvp'];
                    $precio->descuento = $value['descuento'];
                    $precio->id_producto = $producto->id;

                    $precio->save();

                    $stock = new Stocks;

                    $stock->stock = $value['stock'];
                    $stock->stock_control = $value['stock_control'];
                    $stock->stock_virtual = $value['stock_virtual'];
                    $stock->id_producto = $producto->id;

                    $stock->save();


                    foreach ($webs_a as $valor_web) {

                      if(!Webs::where('nombre', '=', $valor_web)->exists()){
                        $web = new Webs;
                        $web->nombre = $valor_web;
                        $web->save();
                      }else{
                        $web = Webs::where('nombre', '=', $valor_web)->first();
                      }

                      $web_producto = new Colores_productos_b;
                      $web_producto->id_producto = $producto->id;
                      $web_producto->id_web = $web->id;

                      $web_producto->save();


                    }

                  }
                }


              })->get();

            }else array_push($errors,'Formato de fichero no válido.');
        }else array_push($errors,'No has subido fichero.');

        return View::make('herramientas/importar_excel_productos', array('errors' => $errors));
    }


}
