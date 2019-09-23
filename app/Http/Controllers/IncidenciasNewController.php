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

class IncidenciasNewController extends Controller
{
    /**
    * Constructor y middleware
    * @return void(true/false auth)
    */
    public function __construct()
    {
      $this->middleware('auth');
    }
    public function index(Request $request){
      /* FLITRO INCIDENCIAS*/
      $filtro = $request->query();
      $origenes = Origen_pedidos::get();
      $motivos = Motivos_incidencias::get();
      $gestiones = Gestiones_incidencias::get();
      if(!$filtro){
        /*GET INCIDENCIAS*/
        $listado_incidencias = Incidencias::orderBy('id','DESC')
                                    ->paginate(50);

      }else{
        //Preparamos array para origenes.
        if(isset($filtro["origen_referencia"])&& $filtro["origen_referencia"]!=""){
          $filtro_origenes = explode(',', $filtro["origen_referencia"] );
        }else{
          $filtro_origenes = array();
        }
        //Creacion de la query para Pedidos.
        $queryRaw = '1';

        if(!isset($filtro["numero_pedido"])){ $filtro["numero_pedido"] = ''; }
        if(!isset($filtro["cliente"])){ $filtro["cliente"] = ''; }
        if(!isset($filtro["nombre_producto"])){ $filtro["nombre_producto"] = ''; }
        if(!isset($filtro["correo_comprador"])){ $filtro["correo_comprador"] = ''; }
        if(!isset($filtro["telefono_comprador"])){ $filtro["telefono_comprador"] = ''; }


        if(isset($filtro["estado"])&& $filtro["estado"]!="") $queryRaw .= " and estado = '".$filtro["estado"]."'";
        if(isset($filtro["motivo"])&& $filtro["motivo"]!="") $queryRaw .= " and id_motivo = '".$filtro["motivo"]."'";
        if(isset($filtro["gestion"])&& $filtro["gestion"]!="") $queryRaw .= " and id_gestion = '".$filtro["gestion"]."'";
        if(isset($filtro["fecha_incidencia"])&& $filtro["fecha_incidencia"]!="") $queryRaw .= " and fecha_incidencia >= '".$filtro["fecha_incidencia"]."'";
        if(isset($filtro["fecha_incidencia_fin"])&& $filtro["fecha_incidencia_fin"]!="") $queryRaw .= " and fecha_incidencia <= '".$filtro["fecha_incidencia_fin"]."'";


        $listado_incidencias = Incidencias::whereRaw($queryRaw)
                                          ->whereHas('productos_incidencias', function($query) use($filtro) {
                                            $query->whereHas('producto',  function ($query) use($filtro){
                                              $query->where('nombre', 'like', "%".$filtro["nombre_producto"]."%")
                                                    ->whereHas('pedido', function($query) use($filtro){
                                                      if($filtro["numero_pedido"] != ''){$query->where('numero_pedido', '=', $filtro["numero_pedido"]);}
                                                      $query->whereHas('cliente', function($query) use($filtro){
                                                        $query->where('nombre_apellidos', 'like', "%".$filtro["cliente"]."%")
                                                              ->where('email', 'like', "%".$filtro["correo_comprador"]."%")
                                                              ->where('telefono', 'like', "%".$filtro["telefono_comprador"]."%");
                                                      });
                                                    });
                                            });
                                          })
                                          ->orderBy('id','DESC')
                                          ->paginate(50);
        }

      /*origenes
        numero pedidos
        cliente
        Producto afectado
        email
        fecha pedido
        fecha incidencia
        motivo
        gestion
        telefono
        */


      return View::make('incidenciasnew/inicio', array('listado_incidencias' => $listado_incidencias,
                                                        'origenes' => $origenes,
                                                        'gestiones' => $gestiones,
                                                        'motivos' => $motivos));
    }

    public function actualizar_incidencia($id, Request $request){
      $post = $request->all();

      $descontado = false;
      try{
        $incidencia = Incidencias::find($id);

        $incidencia->estado = $post['estado_incidencia'];
        $incidencia->id_motivo = $post['mensaje_incidencia'];
        $incidencia->motivo_info = $post['motivo_info'];
        $incidencia->id_gestion = $post['gestion_incidencia'];
        $incidencia->gestion_info = $post['gestion_info'];
        if(isset( $post['cantidad_descontar'])){
          $incidencia->cantidad_descontar = $post['cantidad_descontar'];
        }
        $incidencia->save();
        return "Se ha actualizado correctamente el estado de la incidencia.";

      } catch(Exception $e){
  			return "Ha habido un error durante la actualización, si el error persiste contacte con developer@decowood.es";
  		}


    }

    public function detalle($id){

      $incidencia = Incidencias::find($id);
      $motivos = Motivos_incidencias::get();
      $gestiones = Gestiones_incidencias::get();

      return View::make('incidenciasnew/detalle', array('incidencia' => $incidencia,
                                                        'motivos' => $motivos,
                                                        'gestiones' => $gestiones,));
    }

    public function nueva($id){

      $pedido = Pedidos::find($id);
      $motivos = Motivos_incidencias::get();
      $gestiones = Gestiones_incidencias::get();

      return View::make('incidenciasnew/nueva', array('pedido' => $pedido,
                                                        'motivos' => $motivos,
                                                        'gestiones' => $gestiones,));
    }

    public function guardar($id,Request $request){
      $post = $request->all();
      //$id_productos = explode(',',$post['productos_incidencia']);

      /*Crear incidencia*/
      $incidencia = new Incidencias;
      $motivos = Motivos_incidencias::get();
      $gestiones = Gestiones_incidencias::get();
      if(isset($post['productos_incidencia'])){

        $incidencia->numero_incidencia = (Incidencias::max('numero_incidencia')+1);
        $incidencia->fecha_incidencia = (new DateTime())->format('Y-m-d');
        $incidencia->estado = $post['estado_incidencia'];
        $incidencia->id_motivo = $post['id_motivo'];
        $incidencia->motivo_info = $post['motivo_info'];
        $incidencia->id_gestion = $post['id_gestion'];
        $incidencia->gestion_info = $post['gestion_info'];
        if(isset( $post['cantidad_descontar'])){
          $incidencia->cantidad_descontar = $post['cantidad_descontar'];
        }
        $incidencia->save();

        /*crear filas en la tabla relacional*/
        foreach ($post['productos_incidencia'] as $producto_id) {
          $productos_incidencias = new Productos_incidencias;
          $productos_incidencias->id_producto_pedido = $producto_id;
          $productos_incidencias->id_incidencia = $incidencia->id;
          $productos_incidencias->save();
        }

        if($incidencia->id_gestion == 4){
          $this->guardar_reposicion($id, $post['productos_incidencia']);
        }else{
          return redirect('/incidencias/detalle/'.$incidencia->id);
        }
      }else{
        return back()
        ->with("danger" , "Error: selecciona mínimo un producto afectado.");
      }
    }


    private function guardar_reposicion($id,$productos_incidencia){
      //obtenemos el pedido de la base de datos y los inputs del request

      $pedido_base = pedidos::find($id);
      $pedido = new Pedidos;
      $productos_form = $productos_incidencia;
      $origen_rep = Origen_pedidos::find(37);
      //separamos los objetos por las tablas de la base de datos
      $cliente = new Clientes_pedidos;
      $direccion = new Direcciones;

      $atributos_pedido = $pedido->getFillable();
      $atributos_cliente = $cliente->getFillable();
      $atributos_direccion = $direccion->getFillable();


      /* Guardamos los detalles del pedido */
      foreach ($atributos_cliente as $attr) {
        $cliente->$attr = $pedido_base->cliente->$attr;
      }
      $cliente->save();


      foreach ($atributos_pedido as $attr) {
        $pedido->$attr = $pedido_base->$attr;
      }

      /* Guardamos los detalles del pedido */
      $pedido->origen_id = 37;
      $pedido->numero_pedido = $this->ultimo_numero_pedido(37);
      $pedido->numero_albaran = $origen_rep->referencia.str_pad($this->ultimo_numero_pedido(37), 5, "0", STR_PAD_LEFT);
      $pedido->id_metodo_pago = 12;
      $pedido->id_cliente = $cliente->id;
      $pedido->fecha_pedido = (new \DateTime())->format('Y-m-d');
      $pedido->total = 0;

      $pedido->save();

      foreach ($atributos_direccion as $attr) {
        if(is_null($pedido_base->direccion)){
          $direccion->$attr = $pedido_base->cliente->direcciones[0]->$attr;
        } else {
          $direccion->$attr = $pedido_base->direccion->$attr;
        }
      }
      /* Guardamos los detalles del pedido */
      $direccion->id_cliente = $cliente->id;
      $direccion->save();

      /* Procesamos los productos del pedido y añadimos o eliminamos en funcion del resultado */
      foreach ($productos_incidencia as $num) {
      	// Si es nuevo creamos un producto relacionado
          $producto_base = Productos_pedidos::find($num);
          $nuevo_producto = new Productos_pedidos;
          $atributos_producto = $nuevo_producto->getFillable();

          foreach ($atributos_producto as $attr) {
            $nuevo_producto->$attr = $producto_base->$attr;
          }
          $nuevo_producto->id_pedido = $pedido->id;
          $nuevo_producto->estado_envio = 0;
          $nuevo_producto->estado_proveedor = 0;
          $nuevo_producto->albaran_generado = null;
          $nuevo_producto->precio_final = 0;

          $nuevo_producto->id_transportista = $producto_base->id_transportista;
          $nuevo_producto->id_proveedor = $producto_base->id_proveedor;
          //dd($nuevo_producto);
          $nuevo_producto->save();
      }

      return redirect('pedidos/detalle/'.$pedido->id)->with('mensaje', 'El pedido se ha actualizado correctamente.');

    }

    private function ultimo_numero_pedido($origen_id){
      return (Pedidos::where('origen_id', '=', $origen_id)->max('numero_pedido')+1);
    }
}
