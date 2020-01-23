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

class PedidosNewController extends Controller
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
      //$this->marcar_enviados();
      $origenes = Origen_pedidos::get();
      $proveedores = Proveedores::get();
      $filtro_origenes = array();
      $filtro_proveedores = array();
      //$incidencias = Incidencias::get();
      $filtro = $request->query();
      if(!$filtro){
        $listado_pedidos =  Pedidos::orderBy('id','DESC')
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

        if(isset($filtro["numero_pedido"])&& $filtro["numero_pedido"]!="") $queryRaw .= " and numero_pedido = ".$filtro["numero_pedido"]."";
        if(isset($filtro["fecha_pedido"])&& $filtro["fecha_pedido"]!="") $queryRaw .= " and fecha_pedido >= '".$filtro["fecha_pedido"]."'";
        if(isset($filtro["fecha_pedido_fin"])&& $filtro["fecha_pedido_fin"]!="") $queryRaw .= " and fecha_pedido <= '".$filtro["fecha_pedido_fin"]."'";

        if(!isset($filtro["cliente"])){ $filtro["cliente"] = ''; }
        if(!isset($filtro["correo_comprador"])){ $filtro["correo_comprador"] = ''; }
        if(!isset($filtro["telefono_comprador"])){ $filtro["telefono_comprador"] = ''; }
        if(!isset($filtro["estado_incidencia"])){ $filtro["estado_incidencia"] = ''; }

        if(!isset($filtro["nombre_producto"])){ $filtro["nombre_producto"] = ''; }

        if(isset($filtro["proveedor"])&& $filtro["proveedor"]!=""){
          $filtro_proveedores = explode(',', $filtro["proveedor"] );
        }else{
          $filtro_proveedores = array();
        }

        if(!isset($filtro["estado_envio"])){ $filtro["estado_envio"] = ''; }

        //dd($filtro);
        //dd($queryRaw);
        //Obtenemos los pedidos con paginación
        $listado_pedidos =  Pedidos::whereRaw($queryRaw)
          ->whereHas('cliente',  function ($query) use($filtro) {
            $query->where('nombre_apellidos', 'like', "%".$filtro["cliente"]."%")
                  ->where('email', 'like', "%".$filtro["correo_comprador"]."%")
                  ->where('telefono', 'like', "%".$filtro["telefono_comprador"]."%");
          })
          ->whereHas('productos',  function ($query) use($filtro){
            $query->where('nombre_esp', 'like', "%".$filtro["nombre_producto"]."%");
            if($filtro["estado_envio"] != ''){
              $query->where('estado_envio', '=', $filtro["estado_envio"]);
            }
            if($filtro["estado_incidencia"] != ''){
              $query->whereHas('productos_incidencias', function($query) use($filtro) {
                $query->whereHas('incidencia', function($query) use($filtro){
                  $query->where('estado', '=', $filtro["estado_incidencia"]);
                });
              });
            }
          })
          ->whereHas('origen',  function ($query) use($filtro_origenes) {
            $query->Where( function ($query) use($filtro_origenes) {
                foreach ($filtro_origenes as $origen) {
                  $query->orWhere('referencia', 'like', $origen);
                }
            });
          })
          ->whereHas('productos',  function ($query) use($filtro,$filtro_proveedores){
            foreach ($filtro_proveedores as $proveedor) {
              $query->where('id_proveedor', '=', $proveedor);
              if($filtro["estado_envio"] != ''){
                $query->where('estado_envio', '=', $filtro["estado_envio"]);
              }
              if($filtro["estado_incidencia"] != ''){
                $query->whereHas('productos_incidencias', function($query) use($filtro) {
                  $query->whereHas('incidencia', function($query) use($filtro){
                    $query->where('estado', '=', $filtro["estado_incidencia"]);
                  });
                });
              }
            }
          })
          ->orderBy('id','DESC')
          ->paginate(50);
      }

      $paginaTransportista = NULL;

      return View::make('pedidosnew/inicio', array('listado_pedidos' => $listado_pedidos,
                                                    'origenes' => $origenes,
                                                    'filtro_origenes' => $filtro_origenes,
                                                    'proveedores' => $proveedores,
                                                    'filtro_proveedores' => $filtro_proveedores,
                                                    'paginaTransportista' => $paginaTransportista));



    }

    public function adaptar(){

      $pedidos_wix = Pedidos_wix_importados::where('entrada_principal', '=','1')->get();

      foreach ($pedidos_wix as $pedido_wix) {
        $origen = Origen_pedidos::where('referencia','=',$pedido_wix->o_csv)->first();
        if(is_null($origen)){
          dd($pedido_wix);
          return 'null';
        }

        $direccion = Direcciones::where('direccion_envio','=',$pedido_wix->direccion_envio)
                                ->where('ciudad_envio','=',$pedido_wix->ciudad_envio)
                                ->where('estado_envio','=',$pedido_wix->estado_envio)
                                ->where('cp_envio','=',$pedido_wix->cp_envio)
                                ->where('direccion_facturacion','=',$pedido_wix->direccion_facturacion)
                                ->where('estado_facturacion','=',$pedido_wix->estado_facturacion)
                                ->where('cp_facturacion','=',$pedido_wix->cp_facturacion)
                                ->first();

        if(is_null($direccion)){
          $cliente = new Clientes_pedidos;
          $cliente->nombre_apellidos = $pedido_wix->cliente_facturacion;
          $cliente->email = $pedido_wix->correo_comprador;
          $cliente->telefono = $pedido_wix->telefono_comprador;
          $cliente->email_facturacion = $pedido_wix->correo_comprador;
          $cliente->telefono_facturacion = $pedido_wix->telefono_comprador;
          $cliente->nombre_envio = $pedido_wix->cliente_envio;
          $cliente->save();

          $direccion = new Direcciones;
          $direccion->direccion_envio = $pedido_wix->direccion_envio;
          $direccion->ciudad_envio = $pedido_wix->ciudad_envio;
          $direccion->estado_envio = $pedido_wix->estado_envio;
          $direccion->pais_envio = $pedido_wix->pais_envio;
          $direccion->cp_envio = $pedido_wix->cp_envio;
          $direccion->direccion_facturacion = $pedido_wix->direccion_facturacion;
          $direccion->ciudad_facturacion = $pedido_wix->ciudad_facturacion;
          $direccion->estado_facturacion = $pedido_wix->estado_facturacion;
          $direccion->pais_facturacion = $pedido_wix->pais_facturacion;
          $direccion->cp_facturacion = $pedido_wix->cp_facturacion;
          $direccion->id_cliente = $cliente->id;
          $direccion->save();
        }else{
          $cliente = Clientes_pedidos::find($direccion->id_cliente);
        }

        if(is_null($pedido_wix->forma_de_pago)){
          $pedido_wix->forma_de_pago = 'otro';
        }
        $metodo_pago = Metodos_pago::where('nombre','=',$pedido_wix->forma_de_pago)->first();
        if(is_null($metodo_pago)){
          $metodo_pago = new Metodos_pago;
          $metodo_pago->nombre = $pedido_wix->forma_de_pago;
          $metodo_pago->save();
        }

        $pedido = Pedidos::where('origen_id','=',$origen->id)
                          ->where('numero_pedido','=',$pedido_wix->numero_pedido)
                          ->first();

        if(is_null($pedido)){
          $pedido = new Pedidos;

          $pedido->origen_id = $origen->id;

          $pedido->numero_pedido = $pedido_wix->numero_pedido;
          $pedido->estado_pago = $pedido_wix->pago;
          $pedido->fecha_pedido = $pedido_wix->fecha_pedido;
          $pedido->hora = $pedido_wix->hora_pedido;
          $pedido->total = $pedido_wix->total;
          $pedido->observaciones = $pedido_wix->observaciones;
          $pedido->precio_envio = $pedido_wix->envio;
          if(is_null($pedido_wix->bultos)){
            $pedido->bultos = 1;
          }else{
            $pedido->bultos = $pedido_wix->bultos;
          }
          $pedido->numero_pedido_ps = $pedido_wix->numero_pedido_ps;
          $pedido->tasas = $pedido_wix->tasas;
          $pedido->cupon = $pedido_wix->cupon;
          $pedido->codigo_factura = $pedido_wix->codigo_factura;
          $pedido->numero_albaran = $origen->referencia.$pedido_wix->numero_pedido;
          $pedido->id_cliente= $cliente->id;
          $pedido->id_metodo_pago = $metodo_pago->id;
          $pedido->save();
        }





      }


    }

    public function adaptar_productos(){

      $pedidos_wix = Pedidos_wix_importados::get();

      foreach ($pedidos_wix as $pedido_wix) {
        $origen = Origen_pedidos::where('referencia','=',$pedido_wix->o_csv)->first();
        if(is_null($origen)){
          return 'null';
        }

        $pedido= Pedidos::where('origen_id','=',$origen->id)
                        ->where('numero_pedido','=',$pedido_wix->numero_pedido)
                        ->first();
        if(is_null($pedido)){
          dd($pedido_wix);
        }
        if(is_null($pedido_wix->metodo_entrega)){
          $pedido_wix->metodo_entrega = 'default';
        }
        $transportista = Transportistas::where('nombre','=',$pedido_wix->metodo_entrega)
                                        ->first();

        $producto_pedido = Productos_pedidos::where('id_pedido','=',$pedido->id)
                                            ->where('antigua_id','=',$pedido_wix->id)
                                            ->first();

        if(is_null($transportista)){
          $transportista = new Transportistas;
          $transportista->nombre = $pedido_wix->metodo_entrega;
          $transportista->save();

        }
        if(is_null($producto_pedido)){
          $producto_pedido = new Productos_pedidos;

          $producto_pedido->SKU = $pedido_wix->sku_producto;
          $producto_pedido->nombre = $pedido_wix->nombre_producto;
          $producto_pedido->nombre_esp = $pedido_wix->nombre_producto;
          $producto_pedido->variante = $pedido_wix->variante_producto;
          $producto_pedido->cantidad = $pedido_wix->cantidad_producto;
          $producto_pedido->peso = $pedido_wix->peso_producto;
          $producto_pedido->cantidad = $pedido_wix->cantidad_producto;
          $producto_pedido->precio_final = $pedido_wix->precio_producto;
          $producto_pedido->texto_especial_producto = $pedido_wix->texto_especial_producto;
          $producto_pedido->ean = $pedido_wix->ean;
          $producto_pedido->fecha_envio = $pedido_wix->fecha_envio;
          $producto_pedido->antigua_id = $pedido_wix->id;

          switch ($pedido_wix->enviado) {
            case 1:
                $producto_pedido->estado_envio = 1;
              break;
            case 2:
                $producto_pedido->estado_envio = 10;
              break;
            case 3:
                $producto_pedido->estado_envio = 20;
              break;
            case 4:
                $producto_pedido->estado_envio = 30;
              break;
            default:
                $producto_pedido->estado_envio = 0;
              break;
          }


          if (preg_match("/dups/i", $pedido_wix->texto_especial_producto)){
            $producto_pedido->id_proveedor = 2;
          }else{
            $producto_pedido->id_proveedor = 1;
          }

          if (preg_match("/enviado/i", $pedido_wix->texto_especial_producto)){
            $producto_pedido->estado_proveedor = 1;
          }

          $producto_pedido->id_transportista = $transportista->id;
          $producto_pedido->id_pedido = $pedido->id;

          $producto_pedido->save();



        }








      }

    }

    public function adaptar_incidencias(){
      $productos_wix = Pedidos_wix_importados::where('estado_incidencia','!=','0')->get();

      $numero_incidencia = 1;
      $producto_anterior = null;
      foreach ($productos_wix as $producto_wix) {
        if($producto_anterior != null){
          if($producto_anterior->numero_pedido != $producto_wix->numero_pedido){
            $numero_incidencia++;
          }
        }
        $producto = Productos_pedidos::where('antigua_id', '=', $producto_wix->id)->first();
        if(!is_null($producto)){
          $productos_incidencias = Productos_incidencias::where('id_producto_pedido','=',$producto->id)->first();

          if(is_null($productos_incidencias)){
            $productos_incidencias = new Productos_incidencias;

            $incidencia = incidencias::whereHas('productos_incidencias',  function ($query) use($producto) {
                                                $query->whereHas('producto',  function ($query) use($producto) {
                                                  $query->where('id_pedido', '=', $producto->id_pedido);
                                                });
                                            })->first();

            if(is_null($incidencia)){
              $incidencia = new Incidencias;


              $incidencia->numero_incidencia = $numero_incidencia;
              $incidencia->estado = $producto_wix->estado_incidencia;
              $incidencia->cantidad_descontar = $producto_wix->historial_incidencia;
              //$incidencia->id_producto_pedido = $producto->id;
              $incidencia->fecha_incidencia = $producto_wix->fecha_pedido;
              $incidencia->id_user = $producto_wix->creador_incidencia;

              //--------- MOTIVO ----------
              $a_motivo =  explode(": ",$producto_wix->mensaje_incidencia);

              $motivo = Motivos_incidencias::where('antigua_id', '=', $a_motivo[0])->first();

              if(!is_null($motivo)){
                $incidencia->id_motivo = $motivo->id;
              }else{
                $incidencia->id_motivo = 1;
              }
              if(sizeof($a_motivo) > 1){
                $incidencia->motivo_info = $a_motivo[1];
              }

              //--------- GESTION ----------
              $a_gestion =  explode(": ",$producto_wix->gestion_incidencia);

              $gestion = Gestiones_incidencias::where('antigua_id','=', $a_gestion[0])->first();

              if(!is_null($gestion)){
                $incidencia->id_gestion = $gestion->id;
              }else{
                $incidencia->id_gestion = 1;
              }
              if(sizeof($a_gestion) > 1){
                  $incidencia->gestion_info = $a_gestion[1];
              }

              $incidencia->save();
                //dd($incidencia);

            }

            $productos_incidencias->id_producto_pedido = $producto->id;
            $productos_incidencias->id_incidencia = $incidencia->id;
            $productos_incidencias->save();
            //
          }
        }
        $producto_anterior = $producto_wix;

      }
    }

    // Importar productos de prestashop
    public function importar_csv()
    {
        return view('herramientas/importar_csv_new');
    }

    public function importar_csv_post(Request $request){
      // Control de registros
      $registros = 0;
      $repetidos = 0;
      $ped_anterior = "DFAULT00000";
      $last_num_PS = 	"NFAULT00000";
      $created = date("Y-m-d H:i:s");

      //validamos documento y lo abrimos.
      $documento_csv = $this->abrir_csv($request);

      // Paso 5: Bucle donde validaremos los registros del documento
      // y en caso de no estar repetidos los guardaremos en DB.
      while(!feof($documento_csv)){

        // Leemos la primera fila del documento
        $fila = fgetcsv($documento_csv,$length=0,$delimiter=';',$enclosure='"');
        // Saltamos la primera fila con los atributos del documento csv.
        if($registros==0){$registros++;continue;}

        // Para evitar uploads de csv erroneos, comprobamos si en la casilla 32 el origen existe y obtenemos el objeto origen
        $origen = $this->get_origen_by_reference($fila[29],$fila[32],$fila[34],$fila[4]);

        if(is_null($origen)){
          return back()
          ->with(array("danger" => "Error: Fichero con formato no valido, no se ha podido cargar."))
          ->with('ptime','Fecha y hora de error: '.date('d-m-Y H:i:s'))
          ->with('user','Cargado por: '.Auth::user()->apodo)
          ->with('path',$nombreFichero);
        }

        // Comprobamos el primer registro del documento,
        // si existe, nos lo saltamos y lo marcamos como repetido.
        $pedido_exists =  Pedidos::where('numero_pedido_ps','=',$fila[0])
          ->where('origen_id','=', $origen->id)
          ->exists();

          if($pedido_exists){
            $pedido_encontrado = Pedidos::where('numero_pedido_ps','=',$fila[0])
              ->where('origen_id','=', $origen->id)
              ->first();

          /*  $producto_pedido_exists = Productos_pedidos::where('id_pedido','=',$pedido_encontrado->id)
              ->where('SKU','=', $fila[20])
              ->where('nombre','like', "%".$fila[18]."%")
              ->exists();*/

              $producto_pedido_exists = Productos_pedidos::where('id_pedido','=',$pedido_encontrado->id)
                  ->where('id_order_product','=', $fila[37])
                  ->exists();

          }else{
            /*$producto_pedido_exists = Pedidos::where('numero_pedido_ps','=',$fila[0])
              ->where('origen_id','=', $origen->id)
              ->whereHas('productos',  function ($query) use($fila){
                $query->where('SKU','=', $fila[20])
                      ->where('nombre','like', "%".$fila[18]."%");
              })
              ->exists();*/

              $producto_pedido_exists = Pedidos::where('numero_pedido_ps','=',$fila[0])
                ->where('origen_id','=', $origen->id)
                ->whereHas('productos',  function ($query) use($fila){
                  $query->where('id_order_product','=', $fila[37]);
                })
                ->exists();
          }


          if($pedido_exists && $producto_pedido_exists){ $repetidos++; continue;}

          if(!$pedido_exists){
            //Sacamos el ultimo numero de pedido y le sumamos 1, solo si es un numero de pedido PS diferente.
            if($last_num_PS != $fila[0]){
              $last_num_PS = $fila[0];
              $numeroPedido = $this->ultimo_numero_pedido($origen->id);
            }

            //Buscamos dirección
            $direccion = Direcciones::where('direccion_envio','=', $fila[13])
                                    ->where('ciudad_envio','=', $fila[12])
                                    ->where('estado_envio','=', $fila[11])
                                    ->where('cp_envio','=', $fila[14])
                                    ->where('direccion_facturacion','=', $fila[7])
                                    ->where('estado_facturacion','=', $fila[5])
                                    ->where('cp_facturacion','=', $fila[8])
                                    ->first();

            //AÑADIMOS DIRECCIÓN SI NO EXISTE SI EXISTE BUSCAMOS EL CLIENTE
            if(is_null($direccion)){
              $cliente = Clientes_pedidos::where('nombre_apellidos','=',$fila[3])
                                        ->where('email','=',$fila[16])
                                        ->where('telefono','=',$fila[15])
                                        ->where('nombre_envio','=',$fila[9])
                                        ->first();

              if(is_null($cliente)){
                $cliente = new Clientes_pedidos;
                $cliente->nombre_apellidos =  $fila[3];
                $cliente->email =  $fila[16];
                $cliente->telefono = $fila[15];
                $cliente->email_facturacion =  $fila[16];
                $cliente->telefono_facturacion = $fila[15];
                $cliente->nombre_envio =  $fila[9];
                $cliente->save();
              }

              $direccion = new Direcciones;
              $direccion->direccion_envio = $fila[13];
              $direccion->ciudad_envio = $fila[12];
              $direccion->estado_envio = $fila[11];
              if($fila[10]==""){ $direccion->pais_envio = 'ES'; }else{$direccion->pais_envio = $fila[10];}
              $direccion->cp_envio = $fila[14];
              $direccion->direccion_facturacion = $fila[7];
              $direccion->ciudad_facturacion = $fila[6];
              $direccion->estado_facturacion =$fila[5];
              $direccion->pais_facturacion = $fila[4];
              $direccion->cp_facturacion = $fila[8];
              $direccion->id_cliente = $cliente->id;
              $direccion->save();
            }else{
              $cliente = Clientes_pedidos::find($direccion->id_cliente);
            }
            //END Direcciones


            //ASIGNAMOS METODO DE PAGO, SI ES NUEVO LO CREAMOS EN LA BASE DE DATOS.
            if($fila[29] == ''){
              $fila[29] = 'otro';
            }

            $metodo_pago = Metodos_pago::where('nombre','=',$fila[29])->first();
            if(is_null($metodo_pago)){
              $metodo_pago = new Metodos_pago;
              $metodo_pago->nombre = $fila[29];
              $metodo_pago->save();
            }
            /*
              FALTA ASIGNAR TRANSPORTISTA SEGÚN WEB
            */
            //END METODO DE Pago

            //BUSCAMOS O CREAMOS EL PEDIDO

            $pedido = new Pedidos;

            $pedido->origen_id = $origen->id;

            $pedido->numero_pedido = $numeroPedido;
            //referencia prestashop falta
            $pedido->estado_pago = $fila[30];
            $pedido->fecha_pedido = date_format(date_create($fila[1]), 'Y-m-d');
            $pedido->hora = $fila[2];
            $pedido->total = $fila[28];
            $pedido->precio_envio = $fila[26];
            $pedido->observaciones = $fila[35];

            $pedido->bultos = 1;

            $pedido->numero_pedido_ps = $fila[0];
            $pedido->tasas = $fila[27];
            $pedido->cupon = $fila[25];
            $pedido->codigo_factura = null;
            $pedido->numero_albaran = $origen->referencia.str_pad($numeroPedido, 5, "0", STR_PAD_LEFT);
            $pedido->id_cliente= $cliente->id;
            $pedido->id_metodo_pago = $metodo_pago->id;
            $pedido->save();

          }
          //END PEDIDOS

          //AÑADIR PRODUCTO:

          $transportista = $this->obtener_transportista($origen,$fila);

          $proveedor = Proveedores::where('nombre','=',$fila[24])
                                  ->first();
          if(!isset($pedido)){
            dd($fila, $pedido_exists,$producto_pedido_exists,$pedido_encontrado->cliente);
          }
          if(is_null($proveedor)){
            $proveedor = new Proveedores;
            $proveedor->nombre = $fila[24];
            $proveedor->save();
          }
          //if(!$producto_pedido_exists){ dd($fila); }
          $producto_pedido = new Productos_pedidos;

          $producto_pedido->SKU = $fila[20];
          $producto_pedido->nombre = $fila[18];
          if($fila[32] == 'CN'){
            $producto_pedido->nombre_esp = $fila[36];
          }else{
            $producto_pedido->nombre_esp = $fila[18];
          }
          $producto_pedido->variante = $fila[19];
          $producto_pedido->cantidad = $fila[21];
          $producto_pedido->peso =$fila[23];
          $producto_pedido->cantidad = $fila[21];
          $producto_pedido->precio_final = $fila[22];
          $producto_pedido->ean = $fila[33];
          //precio base falta
          $producto_pedido->id_transportista = $transportista->id;
          $producto_pedido->id_proveedor = $proveedor->id;
          $producto_pedido->id_pedido = $pedido->id;
          $producto_pedido->id_order_product = $fila[37];

          $producto_pedido->fecha_max_salida= $pedido->fecha_de_salida_producto($producto_pedido);
          $producto_pedido->save();

          //end productos


          //CONTROL STOCK
          $this->control_stock($fila[33],$fila[21]);
          // Si se inserta registro, se suma +1 al contador de registros insertados.
      		$registros++;

      }
      // Restamos -1 a registros para eliminar el conteo de la fila de atributos.
      $registros--;

      // Preparamos mensaje y retornamos el resultado a la vista origen.
      if($registros==0) {
        $mensaje['info'] = 'CSV se ha subido con exito. No se han cargado los registros, ya existen.';
      } else if($registros > 0 && $repetidos > 0){
        $mensaje['success'] = 'CSV se han subido con exito ('.$registros.') registros. No subidos ('.$repetidos.') ya existen.';
      } else {
        $mensaje['success'] = 'CSV cargado con exito con un total de ( '.$registros.' ) registros insertados.';
      }
      if($_SERVER['HTTP_HOST'] == "admin.decowood.es" ){
        $this->aviso_retraso();
      }
      return back()
      ->with($mensaje)
      ->with('ptime','Fecha y hora de carga: '.date('d-m-Y H:i:s'))
      ->with('user','Cargado por: '.Auth::user()->apodo);

    }

    private function get_origen_by_reference($metodo_pago,$referencia,$id_shop,$pais){
      if($referencia == 'CN'){
        switch ($id_shop) {
          case 2:
            $referencia = 'AC';
            break;
          case 3:
            $referencia = 'LT';
            break;
          case 4:
            $referencia = 'TT';
            break;
          case 5:
            $referencia = 'BK';
            break;
          default:
            $referencia = 'CN';
            break;
        }
      }
      if(preg_match("/privalia/i",$metodo_pago)){

        $referencia = 'PM';


      }elseif ($referencia == 'DW' && $pais == 'FR' ) {
        $referencia = 'DF';
      }

      $origen = Origen_pedidos::where('referencia','=',$referencia)->first();

      return $origen;

    }

    private function control_stock($ean,$cantidad){

      /*Stock automatico*/
      if(isset($ean) && $ean != ''){

        if($ean == "8435550445995" || $ean == "8435550446008"){
          $ean = "8435550429261";
        }elseif($ean == "8435550446015" || $ean == "8435550446022"){
          $ean = "8435550429483";
        }elseif($ean == "8435550446039" || $ean == "8435550446046"){
          $ean = "8435550429292";
        }elseif($ean == "8435550446053" || $ean == "8435550446060"){
          $ean = "8435550429490";
        }
        elseif($ean == "8435550446077" || $ean == "8435550446084"){
          $ean = "8435550429308";
        }
        elseif($ean == "8435550446091" || $ean == "8435550446107"){
          $ean = "8435550429506";
        }

        elseif($ean == "8435550446114" || $ean == "8435550446121"){
          $ean = "8435550429315";
        }
        elseif($ean == "8435550446138" || $ean == "8435550446145"){
          $ean = "8435550429513";
        }

        elseif($ean == "8435550446152" || $ean == "8435550446169"){
          $ean = "8435550429278";
        }
        elseif($ean == "8435550446176" || $ean == "8435550446183"){
          $ean = "8435550429285";
        }

        elseif($ean == "8435550446190" || $ean == "8435550446206"){
          $ean = "8435550437402";
        }
        elseif($ean == "8435550446213" || $ean == "8435550446220"){
          $ean = "8435550437419";
        }


        $where = "1=1 and ean = ".$ean;
        $p_comanda = DB::table('productos')->whereRaw($where)->get();
        if(isset($p_comanda[0])){
            $p_comanda = $p_comanda[0];
            $parametros = array("producto" => $p_comanda);//parametros para correo.
            $email_cliente = "developer@decowood.es";//
            $new_stock = $p_comanda->stock - $cantidad;
            //dd($new_stock);
            //falta implementar envio correo proovedor.
            if($new_stock < 0){
              //enviar correo stock agotado
            /*  Mail::send('mail.stock_agotado', $parametros, function($message)
              {
                $message->from('info@decowood.es', 'Info Stock');
                $message->to('info@decowood.es', 'Información')->subject('Alerta Stock agotado');
                $message->cc('sandra@decowood.es', 'Sandra');
                $message->bcc('developer@decowood.es', 'Developer');
              });*/
              $producto_actualizado = DB::table('productos')
                                  ->whereRaw('ean = "'.$ean.'"')
                                  ->update(['stock' => $new_stock,
                                            'esperaStock' => 1]);
            }elseif ($new_stock < $p_comanda->stockControl) {

              if($p_comanda->esperaStock != 1){
                //Correo informativo stock de control

                //Enviar correo comanda

              }
              $producto_actualizado = DB::table('productos')
                                    ->whereRaw('ean = "'.$ean.'"')
                                    ->update(['stock' => $new_stock,
                                              'esperaStock' => 1]);
            }else{
              $producto_actualizado = DB::table('productos')
                                  ->whereRaw('ean = "'.$ean.'"')
                                  ->update(['stock' => $new_stock]);
            }
        }

      }

    }

    private function abrir_csv($request){
      // 1. Validar formato documento importado
      $this->validate($request, [
          'file'      => $request['csv'],
          'extension' => strtolower($request['csv']->getClientOriginalExtension()),
        ],
        [
          'file'          => 'required',
          'extension'      => 'required|in:csv',

      ]);

      // 2. Generar nombre del fichero
      $nombreFichero = 'CSV_PS_'.date('d-m-Y_H-i-s').'.'.$request->csv->getClientOriginalExtension();
      // 3. Subir fichero al directorio de archivos
      $request->csv->move(public_path('documentos'), $nombreFichero);

      // Paso 3.1: Eliminar documentos anteriores a 1 semana.
      //* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
      //* el cleanup aquí mismo al cargar un nuevo fichero.


      // 4. Abrimos el documento para realizar la lectura.
      $documento_csv = fopen(public_path('documentos').'/'.$nombreFichero,"r");

      return $documento_csv;
    }

    private function obtener_transportista($origen,$fila){
      if( $fila[17]== ''){
        $fila[17] = 'default';
      }
      $transportista = Transportistas::where('nombre','=',$fila[17])
                                      ->first();

      if(is_null($transportista)){
        $transportista = new Transportistas;
        $transportista->nombre = $fila[17];
        $transportista->save();
      }

      if($origen->referencia == "CA"){
        if($fila[28] <= 350 ){
          $transportista = Transportistas::where('nombre','=',$origen->transportista_principal)
                                          ->first();
        }else{
          $transportista = Transportistas::where('nombre','=','transparets')
                                          ->first();
        }
      }else if($origen->referencia == "DW"){
        if(preg_match("/corcho/i", $fila[18])){
          $transportista = Transportistas::where('nombre','=','MRW')
                                          ->first();
        }else{
          $transportista = Transportistas::where('nombre','=',$origen->transportista_principal)
                                          ->first();
        }
      }else if ($origen->referencia == "PM"){
        if(preg_match("/corcho/i", $fila[18])){
          $transportista = Transportistas::where('nombre','=','MRW')
                                          ->first();
        }else{
          $transportista = Transportistas::where('nombre','=',"tipsa")
                                          ->first();
        }
      }else{
        $transportista = Transportistas::where('nombre','=',$origen->transportista_principal)
                                        ->first();
      }

      return $transportista;
    }

    //End importar productos
    public function detalle($id){

      $pedido = Pedidos::find($id);

      $seguimiento = Seguimiento_pedidos::where('numero_pedido','=',$pedido->numero_pedido)
                                          ->where('origen','=',$pedido->origen->referencia)
                                          ->get();

      $incidencias = Incidencias::whereHas('productos_incidencias', function($query) use($id){
                                    $query->whereHas('producto', function($query) use($id){
                                      $query->where('id_pedido', '=', $id);
                                    });
                                  })
                                  ->get();
      $usuarios = User::get();
      return View::make('pedidosnew/detalle', array('pedido' => $pedido,
                                                    'seguimiento' => $seguimiento,
                                                    'incidencias' => $incidencias,
                                                    'usuarios' => $usuarios));


    }

    public function modificar($id){

      $pedido = Pedidos::find($id);
      $transportistas = Transportistas::get();
      $proveedores = Proveedores::get();
      return View::make('pedidosnew.modificar', array("pedido" => $pedido,
                                                      "transportistas" => $transportistas,
                                                      "proveedores" => $proveedores));



    }

    public function actualizar($id, Request $request){
      //obtenemos el pedido de la base de datos y los inputs del request
      $inputs = $request->all();
      $pedido = Pedidos::find($id);
      $productos_form = json_decode($inputs["productos_serializados"], true);

      //separamos los objetos por las tablas de la base de datos
      $cliente = $pedido->cliente;
      $direccion = $pedido->cliente->direccion;

      //obtención de los atributos;
      $atributos_pedido = $pedido->getAttributes();
    	$atributos_cliente = $cliente->getAttributes();
    	$atributos_direccion = $direccion->getAttributes();

      // Comparamos los inputs, y si el atributo existe, asignamos el valor a las tablas
      foreach($inputs as $key => $input){
        if($key=="_token" || $key=="productos_serializados") continue;
        if(array_key_exists($key ,$atributos_pedido)){
          $pedido->$key = $inputs[$key];
        }
      }

      /* Guardamos los detalles del pedido */
      $pedido->save();

      // Comparamos los inputs, y si el atributo existe, asignamos el valor a las tablas
      foreach($inputs as $key => $input){
        if($key=="_token" || $key=="productos_serializados") continue;
        if(array_key_exists($key ,$atributos_cliente)){
          $cliente->$key = $inputs[$key];
        }
      }

      /* Guardamos los detalles del pedido */
      $cliente->save();

      // Comparamos los inputs, y si el atributo existe, asignamos el valor a las tablas
      foreach($inputs as $key => $input){
        if($key=="_token" || $key=="productos_serializados") continue;
        if(array_key_exists($key ,$atributos_direccion)){
          $direccion->$key = $inputs[$key];
        }
      }

      /* Guardamos los detalles del pedido */
      $direccion->save();
      /* Procesamos los productos del pedido y añadimos o eliminamos en funcion del resultado */
      foreach ($productos_form["id"] as $num => $att) {
        	// Si es nuevo creamos un producto relacionado
          if(($productos_form["id"][$num]["value"]==0) && ($productos_form["eliminar"][$num]["value"]=='NO')){
            $nuevo_producto = new Productos_pedidos;

            $nuevo_producto = $this->guardar_producto_modificar($productos_form, $id, $num, $nuevo_producto);

          }else{
            if($productos_form["eliminar"][$num]["value"]=='SI'){
              $producto_eliminar = Productos_pedidos::find($productos_form["id"][$num]["value"]);
              $producto_eliminar->delete();
            }else{
              $producto_modificar = Productos_pedidos::find($productos_form["id"][$num]["value"]);
              $producto_modificar = $this->guardar_producto_modificar($productos_form, $id, $num, $producto_modificar);
            }

          }
      }

      return redirect('pedidos/detalle/'.$id)->with('mensaje', 'El pedido se ha actualizado correctamente.');

    }

    private function guardar_producto_modificar($productos_form, $id, $num, $producto){

      $producto->id_pedido = $id;

      $producto->nombre_esp = $productos_form["nombre_esp"][$num]["value"];
      $producto->nombre = $productos_form["nombre"][$num]["value"];
      $producto->variante = $productos_form["variante"][$num]["value"];
      $producto->SKU = $productos_form["SKU"][$num]["value"];
      $producto->ean = $productos_form["ean"][$num]["value"];
      $producto->cantidad = $productos_form["cantidad"][$num]["value"];
      $producto->id_transportista = $productos_form["id_transportista"][$num]["value"];
      $producto->id_proveedor = $productos_form["id_proveedor"][$num]["value"];
      $producto->precio_final = $productos_form["precio_final"][$num]["value"];
      $producto->fecha_max_salida = $productos_form["fecha_max_salida"][$num]["value"];

      $producto->save();
    }

    public function eliminar(Request $request){

      try {
        // Asignamos variables
        $id = $request->all()["id"];
        $eliminados = "(".$id.") reg [ ";

        $pedido = Pedidos::find($id);
        $seguimientos = Seguimiento_pedidos::where('origen', '=', $pedido->origen->referencia)
                                            ->where('numero_pedido', '=', $pedido->numero_pedido)
                                            ->get();

        foreach ($seguimientos as $seguimiento) {
          $seguimiento->delete();
        }

        $pedido->cliente->direccion->delete();
        $pedido->cliente->delete();

        foreach ($pedido->productos as $producto) {
          foreach ($producto->productos_incidencias as $p_incidencia) {
            //  dd($p_incidencia->incidencia);
            $p_incidencia->incidencia->delete();

            $p_incidencia->delete();
          }
          $eliminados .= "{".$producto->id.":".$producto->nombre_esp."} ";
          $producto->delete();
        }

        $pedido->delete();


        $eliminados .= "]";
        return "Eliminado correctamente: ".$eliminados;
      } catch(\Exception $err){
        return "No se ha podido eliminar, intentelo de nuevo o contacte con el administrador.".var_dump();
      }

    }

    public function nuevo(){
      $origenes = Origen_pedidos::orderBy('id','asc')->groupBy('grupo')->get();
      $transportistas = Transportistas::get();
      $proveedores = Proveedores::get();
      $metodos_pago = Metodos_pago::groupBy('grupo')->get();

		  return View::make('pedidosnew/nuevo', array('origenes' => $origenes,
                                                  'transportistas' => $transportistas,
                                                  'proveedores' => $proveedores,
                                                  'metodos_pago' => $metodos_pago));
    }

    private function origen_manual($origen){
      switch ($origen) {
        case 'CA':
          $origen_csv = 'CM';
          break;
        case 'CB':
          $origen_csv = 'CC';
          break;
        case 'CN':
          $origen_csv = 'CC';
          break;
        case 'CJ':
          $origen_csv = 'CO';
          break;
        case 'TT':
          $origen_csv = 'TL';
          break;
        case 'FS':
          $origen_csv = 'FX';
          break;
        case 'AC':
          $origen_csv = 'AB';
          break;
        case 'LT':
          $origen_csv = 'LE';
          break;
        case 'BK':
          $origen_csv = 'BT';
          break;
        case 'FE':
          $origen_csv = 'FU';
          break;
        case 'DW':
          $origen_csv = 'DD';
          break;
        default:
          $origen_csv = $origen;
          break;
      }

      return $origen_csv;
    }

    private function ultimo_numero_pedido($origen_id){
      return (Pedidos::where('origen_id', '=', $origen_id)->max('numero_pedido')+1);
    }

    public function guardar(Request $request){

      $referencia_origen= $this->origen_manual($request['o_csv']);

      $origen = Origen_pedidos::where('referencia', '=', $referencia_origen)->first();

      $numeroPedido = $this->ultimo_numero_pedido($origen->id);

      $cliente = new Clientes_pedidos;

      $cliente->nombre_apellidos = $request['nombre_apellidos'];
      $cliente->email_facturacion = $request['email_facturacion'];
      $cliente->telefono_facturacion = $request['telefono_facturacion'];
      $cliente->nombre_envio = $request['nombre_envio'];
      $cliente->email = $request['email'];
      $cliente->telefono = $request['telefono'];

      $cliente->save();

      //crear pedido:
      $pedido = new Pedidos;

      $pedido->origen_id = $origen->id;
      $pedido->id_metodo_pago = $request['metodo_pago'];

      $pedido->numero_pedido = $numeroPedido;
      $pedido->numero_albaran = $origen->referencia.str_pad($numeroPedido, 5, "0", STR_PAD_LEFT);

      $pedido->codigo_factura = $request['codigo_factura'];
      $pedido->fecha_pedido = date_format(date_create($request['fecha_pedido']), 'Y-m-d');
      $pedido->hora = $request['hora'];
      $pedido->observaciones = $request['observaciones'];
      $pedido->cupon = $request['cupon'];
      $pedido->total = $request['total'];
      $pedido->estado_pago = $request['estado_pago'];

      $pedido->id_cliente = $cliente->id;

      $pedido->save();
      //End pedido_

      $direccion = new Direcciones;

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

      $direccion->id_cliente = $cliente->id;

      $direccion->save();

      foreach($request['nombre_esp'] as $key => $nombre_esp){
        $producto = new Productos_pedidos;

        $producto->id_pedido = $pedido->id;

        $producto->nombre_esp = $request['nombre_esp'][$key];
        $producto->nombre = $request['nombre'][$key];
        $producto->variante = $request['variante'][$key];
        $producto->ean = $request['ean'][$key];
        $producto->SKU = $request['SKU'][$key];
        $producto->cantidad = $request['cantidad'][$key];

        $producto->id_transportista = $request['id_transportista'][$key];
        $producto->id_proveedor = $request['id_proveedor'][$key];

        $producto->save();

      }

      return redirect('pedidos/detalle/'.$pedido->id)->with('mensaje', 'El pedido se ha creado correctamente.');

    }

    public function duplicar($id){

      $pedido = Pedidos::find($id);
      $pedido->fecha_pedido = (new \DateTime())->format('Y-m-d');
      $transportistas = Transportistas::get();
      $proveedores = Proveedores::get();
      return View::make('pedidosnew.modificar', array("pedido" => $pedido,
                                                      "transportistas" => $transportistas,
                                                      "proveedores" => $proveedores));
    }

    public function guardar_duplicado($id, Request $request){
      //obtenemos el pedido de la base de datos y los inputs del request
      $inputs = $request->all();
      $pedido_base = pedidos::find($id);
      $pedido = new Pedidos;
      $productos_form = json_decode($inputs["productos_serializados"], true);

      //separamos los objetos por las tablas de la base de datos
      $cliente = new Clientes_pedidos;
      $direccion = new Direcciones;

      //obtención de los atributos;
      $atributos_pedido = $pedido->getFillable();
    	$atributos_cliente = $cliente->getFillable();
    	$atributos_direccion = $direccion->getFillable();


      // Comparamos los inputs, y si el atributo existe, asignamos el valor a las tablas
      foreach($inputs as $key => $input){
        if($key=="_token" || $key=="productos_serializados") continue;
        if(in_array($key ,$atributos_cliente)){
          $cliente->$key = $inputs[$key];
        }
      }
      /* Guardamos los detalles del pedido */

      $cliente->save();

      // Comparamos los inputs, y si el atributo existe, asignamos el valor a las tablas
      foreach($inputs as $key => $input){
        if($key=="_token" || $key=="productos_serializados") continue;
        if(in_array($key ,$atributos_pedido)){
          $pedido->$key = $inputs[$key];
        }
      }

      /* Guardamos los detalles del pedido */
      $pedido->origen_id = $pedido_base->origen->id;
      $pedido->numero_pedido = $this->ultimo_numero_pedido($pedido_base->origen->id);
      $pedido->numero_albaran = $pedido_base->origen->referencia.str_pad($this->ultimo_numero_pedido($pedido_base->origen->id), 5, "0", STR_PAD_LEFT);
      $pedido->id_cliente = $cliente->id;
      $pedido->estado_pago = $pedido_base->estado_pago;
      $pedido->id_metodo_pago = $pedido_base->id_metodo_pago;

      $pedido->save();

      // Comparamos los inputs, y si el atributo existe, asignamos el valor a las tablas
      foreach($inputs as $key => $input){
        if($key=="_token" || $key=="productos_serializados") continue;
        if(in_array($key ,$atributos_direccion)){
          $direccion->$key = $inputs[$key];
        }
      }

      /* Guardamos los detalles del pedido */
      $direccion->id_cliente = $cliente->id;
      $direccion->save();

      /* Procesamos los productos del pedido y añadimos o eliminamos en funcion del resultado */
      foreach ($productos_form["id"] as $num => $att) {
      	// Si es nuevo creamos un producto relacionado
        if($productos_form["eliminar"][$num]["value"]=='NO'){
          $nuevo_producto = new Productos_pedidos;

          $nuevo_producto = $this->guardar_producto_modificar($productos_form, $pedido->id, $num, $nuevo_producto);

        }
      }

      return redirect('pedidos/detalle/'.$pedido->id)->with('mensaje', 'El pedido se ha actualizado correctamente.');

    }

    //enviOS
    public function marcar_enviados(){
      $pedidos_no_enviados = Pedidos::whereHas('productos', function ($query) {
          $query->where('estado_envio', '=', '0');
      })->get();

      foreach ($pedidos_no_enviados as $pedido_no_enviado) {
        $enviado = false;
        foreach ($pedido_no_enviado->productos as $producto) {
          if($producto->estado_envio == 1){
            $enviado = true;
          }
        }
        if($enviado){
          foreach ($pedido_no_enviado->productos as $producto_enviado) {
            $producto_enviado->estado_envio = 1;
            $producto_enviado->save();
          }
        }

      }


    }

    public function enviar_pedido($id){

      $pedido = Pedidos::find($id);

      $fecha = new DateTime();
      $fecha = $fecha->format('Y-m-d');

      if($_GET["notificar"]=="si"){
        // Mailing
        //$correo_comercial = Auth::user()->email;
        $correo_comercial = 'support@decowood.es';
        $titulo = "Hola, ".$pedido->cliente->nombre_esp." su pedido ha sido enviado.";
        $email_cliente = ($pedido->cliente->email_facturacion) ? $pedido->cliente->email_facturacion : "Cliente";
        //  dd($correo_comercial);
        // Parametros para el mailing
        $parametros = array("pedido" => $pedido->toArray(), 'productos' => $pedido->productos->toArray());

        // Se envia mensaje al cliente
        Mail::send('mail.informar_envio', $parametros, function($message) use($email_cliente)
        {
          $message->from('info@decowood.es', 'Información de su PEDIDO');
          $message->to($email_cliente, 'Información')->subject('Información');
        });

        // Se envia copia del mensaje al administrador y al usuario que envia.
        Mail::send('mail.informar_envio', $parametros, function($message) use($correo_comercial)
        {
          $message->from('info@decowood.es', 'Información de su PEDIDO');
          $message->to($correo_comercial, 'Información')->subject('Información (COPIA)');
        });
        $mensaje = "El pedido se ha actualizado, y se ha enviado una notificación al correo del cliente.";
      } else {
        $mensaje = "El pedido se ha actualizado, pero no se ha notificado al cliente.";
      }

      $resultado = array('0' => "", '1' => "", '2' => "");
      try {
        $id_ps = $pedido->numero_pedido_ps;

        foreach ($pedido->productos as $productos) {
          $productos->fecha_envio = $fecha;
          $productos->estado_envio = 1;
          $productos->save();
        }

        // Llenamos array a retornar.
        $resultado[0] = $mensaje;
        $resultado[1] = $fecha;

        if(($pedido->origen->api_key != null)&&($pedido->numero_pedido_ps != '99999')&&($pedido->numero_pedido_ps = '')){
          /*if($this->actualizar_pedidos_ps($pedido->origen->referencia,$id_ps)){
            $resultado[2] = "true";
          }*/
          $resultado[0] .= " \n || Falta por activar el webservice!!!!";
        }
      } catch(Exception $e){
        $resultado[0] = "Ha habido un error durante la actualización, si el error persiste contacte con developer@decowood.es";
        $resultado[1] = "";
        $resultado[2] = "false";
      }
      // Retornamos array en formato json_decode

      return json_encode($resultado);


    }

    public function enviar_producto($id){

      $producto = Productos_pedidos::find($id) ;

      $pedido = Pedidos::where('id', '=', $producto->id_pedido)->first() ;

      $fecha = new DateTime();
      $fecha = $fecha->format('Y-m-d');

      if($_GET["notificar"]=="si"){
        // Mailing
        //$correo_comercial = Auth::user()->email;
        $correo_comercial = 'support@decowood.es';
        $titulo = "Hola, ".$pedido->cliente->nombre_esp." un producto de tu pedido ha sido enviado.";
        $email_cliente = ($pedido->cliente->email_facturacion) ? $pedido->cliente->email_facturacion : "Cliente";
        // Parametros para el mailing
        $parametros = array("pedido" => $pedido->toArray(), 'producto' => $producto->toArray());

        // Se envia mensaje al cliente
        Mail::send('mail.informar_envio', $parametros, function($message) use($email_cliente)
        {
          $message->from('info@decowood.es', 'Información de su PEDIDO');
          $message->to($email_cliente, 'Información')->subject('Información');
        });

        // Se envia copia del mensaje al administrador y al usuario que envia.
        Mail::send('mail.informar_envio', $parametros, function($message) use($correo_comercial)
        {
          $message->from('info@decowood.es', 'Información de su PEDIDO');
          $message->to($correo_comercial, 'Información')->subject('Información (COPIA)');
        });
        $mensaje = "El pedido se ha actualizado, y se ha enviado una notificación al correo del cliente.";
      } else {
        $mensaje = "El pedido se ha actualizado, pero no se ha notificado al cliente.";
      }

      $resultado = array('0' => "", '1' => "", '2' => "");
      try {

        $producto->fecha_envio = $fecha ;
        $producto->estado_envio = 1 ;
        $producto->save();

        // Llenamos array a retornar.
        $resultado[0] = $mensaje;
        $resultado[1] = $fecha;

        if(($pedido->origen->api_key != null)&&($pedido->numero_pedido_ps != '99999')&&($pedido->numero_pedido_ps = '')){
          $resultado[0] .= " \n || Falta por activar el webservice!!!!";
        }

      } catch(Exception $e){
        $resultado[0] = "Ha habido un error durante la actualización, si el error persiste contacte con developer@decowood.es";
        $resultado[1] = "";
        $resultado[2] = "false";
      }
      // Retornamos array en formato json_decode

      return json_encode($resultado);

    }

    public function actualizar_pedidos_ps($origen,$id_order)
    {

      /* Obtenemos los datos de la tabla origen */
      $origen_pedidos =  Origen_pedidos::where('referencia','=',$origen)->first();
      define('DEBUG', false);
      /*Definimos el host y la key que será la url de la web recuperada desde la BDD*/
      define('PS_SHOP_PATH', $origen_pedidos->web );
      define('PS_WS_AUTH_KEY', $origen_pedidos->api_key);
      //require_once('PSWebServiceLibrary/PrestaShopWebservice.php');
      // Obtencion del pedido
      try
      {
        /*Petición al web service*/
        $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
  	    $opt = array('resource' => 'orders');
        $opt['id'] = $id_order;
        $xml = $webService->get($opt);
        $resources = $xml->children()->children();
      }
      catch (PrestaShopWebserviceException $e)
      {
      	// Here we are dealing with errors
      	$trace = $e->getTrace();
      	if ($trace[0]['args'][0] == 404) echo 'Bad ID';
      	else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
      	else echo 'Other error';
      }
      // asignamos nuevo estado_envio
      //var_dump($resources);
      /*
        2: Pago aceptado
        5 : Entregado
        4 : ENVIADO

      */
      $resources->current_state = 4;

      try
    	{
        //Envío de la petición PUT
    		$opt = array('resource' => 'orders');
    		$opt['putXml'] = $xml->asXML();
    	  $opt['id'] = $id_order;
    		$xml = $webService->edit($opt);

    	}
    	catch (PrestaShopWebserviceException $ex)
    	{

    		$trace = $ex->getTrace();
    		if ($trace[0]['args'][0] == 404) echo 'Bad ID';
    		else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
    		else echo 'Other error<br />'.$ex->getMessage();
    	}

      return true;

    }

    public function seguimiento_pedido($id, Request $request){

      $post = $request->all();

      try {
        $pedido = Pedidos::find($id);

        $seguimiento = new Seguimiento_pedidos;

        $seguimiento->origen =  $pedido->origen->referencia;
        $seguimiento->numero_pedido = $pedido->numero_pedido;
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

    public function crear_observacion_bultos($id, Request $request){
      try {
      $pedido = Pedidos::find($id);
      if($request["bultos"]==0){
        $pedido->bultos = 1;
      } else {
        $pedido->bultos = $request["bultos"];
      }

        $pedido->save();
      } catch(Exception $e) {
        return "No se ha podido actualizar, contactar con el administrador developer@decowood.es";
      }
      return "Actualizado.";
    }

    public function crear_observacion($id, Request $request){
      try {
        $pedido = Pedidos::find($id);

        $pedido->observaciones = $request["mensaje_observacion"];

        $pedido->save();
      } catch(Exception $e) {
        return "No se ha podido actualizar, contactar con el administrador developer@decowood.es";
      }
      return "Actualizado.";
    }

    public function gpdf_albaran($type, $idm, Request $request){
      $ids = null;

      $pedido = Pedidos::find($idm);
      $ruta_pdf = "documentos/albaranes/agrupados/";
      $nombre_pdf = $type."_albaran_".$pedido->origen->referencia.$pedido->numero_pedido;
      $view = "";

      if($type== "etiqueta"){
        $template= "pedidosnew.albaran_etiqueta";
      }else if($type== "A4"){
        $template= "pedidosnew.albaran";
      }else{
        $template= "pedidosnew.albaran1copia";
      }

      if(isset($request->all()["ids"])){ $ids = json_decode($request->all()["ids"],true); }

      if(!is_null($ids)){
        $trans_array = array();
        foreach ($ids as $producto_array) {
          $producto = Productos_pedidos::find($producto_array['value']);
          if(isset($trans_array[$producto->transportista->id])){
            array_push($trans_array[$producto->transportista->id],$producto);
          }else{
            $trans_array[$producto->transportista->id] = array();
            array_push($trans_array[$producto->transportista->id],$producto);
          }
        }
        $view = $this->crear_vista_albaran_producto($trans_array, $template);
        return $this->generar_pdf($view,$ruta_pdf,$nombre_pdf);
      }else{
        $view = $this->crear_vista_albaran($pedido, $template);

        return $this->generar_pdf($view,$ruta_pdf,$nombre_pdf);
      }

    }



    private function generar_pdf($view,$ruta_pdf,$nombre_pdf){
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

    public function gpdf_albaranes(Request $request){
      $ruta_pdf = "documentos/albaranes/agrupados/";

      $type = $request->all()["type"];

      if($type== "etiqueta"){
        $template= "pedidosnew.albaran_etiqueta";
      }else if($type== "A4"){
        $template= "pedidosnew.albaran";
      }else{
        $template= "pedidosnew.albaran1copia";
      }

      $view = "";
      if(isset($request->all()["ids"])){ $ids = json_decode($request->all()["ids"],true); }

      foreach($ids as $id){
        $pedido = Pedidos::find($id['value']);
        $view .= $this->crear_vista_albaran($pedido,$template);

        $nombre_pdf = $type."_albaran_mult_".$pedido->origen->referencia.$pedido->numero_pedido;

      }

      return $this->generar_pdf($view,$ruta_pdf,$nombre_pdf);

    }

    public function modificar_producto(){
      return View::make('pedidosnew/form_producto', array());
    }

    public function alta_proveedor(){
      return View::make('pedidosnew/alta_proveedor', array());
    }

    public function alta_transportista(){
      return View::make('pedidosnew/alta_transportista', array());
    }

    public function alta_origen(){
      return View::make('pedidosnew/alta_origen', array());
    }

    private function crear_vista_albaran($pedido, $vista){
      $view = '';
      $transportistas = Transportistas::whereHas('productos' , function ($query) use($pedido){
        $query->whereHas('pedido', function ($query) use($pedido){
          $query->where('id', '=', $pedido->id);
        });
      })->get();

      foreach ($transportistas as $transportista) {

        $productos = Productos_pedidos::whereHas('transportista', function($query) use ($transportista){
          $query->where('id', '=', $transportista->id);
        })
        ->whereHas('pedido', function($query) use($pedido){
          $query->where('id','=',$pedido->id);
        })
        ->get();

        $datos = array('pedido' => $pedido,
                      'productos' => $productos);

        $view .= View::make($vista, $datos)->render();

        foreach ($productos as $producto) {
          $producto->albaran_generado = 1;
          $producto->save();
        }


      }

      return $view;
    }

    private function crear_vista_albaran_producto($trans_array, $template){
      $view = '';
      foreach ($trans_array as $id_trans => $productos) {

        $transportista = Transportistas::find($id_trans);

        $datos = array('pedido' => $productos[0]->pedido,
                      'productos' => $productos);

        $view .= View::make($template, $datos)->render();

        foreach ($productos as $producto) {
          $producto->albaran_generado = 1;
          $producto->save();
        }


      }

      return $view;
    }

    public function csv_mrw($id,$generar_csv){
      $pedido = Pedidos::find($id);

      $datos_adicionales = '#SeguimientoSMS=1#';
      $date = getdate();
      $fecha = $date['year'].str_pad($date['mon'], 2, "0", STR_PAD_LEFT).str_pad($date['mday'], 2, "0", STR_PAD_LEFT);

      $tlf = "";
      $tlf = str_replace("/", "", $pedido->cliente->telefono);
      $tlf = trim($tlf);

      if($pedido->cliente->direccion->pais_envio == ""){
        $pais_fact = "ES";
      }else{
        $pais_fact = $pedido->cliente->direccion->pais_envio;
      }

      $empty = "";
      $csv = array('numero_albaran' => $empty,
                    'referencia_envio' => $pedido->numero_albaran,
                    'referencia_bulto' => $empty,
                    'peso' => $empty,
                    'bultos' => $pedido->bultos,
                    'fecha_recogida' => ''.$fecha.'',
                    'observacion' => ''.$pedido->observaciones.'',
                    'nombre_apellido' => ''.$pedido->cliente->nombre_envio.'',
                    'direccion' => ''.$pedido->cliente->direccion->direccion_envio.'',
                    'cp' => ''.trim($pedido->cliente->direccion->cp_envio).'',
                    'poblacion' => ''.$pedido->cliente->direccion->ciudad_envio.'',
                    'codigo_pais' => $pais_fact,
                    'telefono' => ''.$tlf.'',
                    'franquicia' => '',
                    'adicionales' => ''.$datos_adicionales.''
                    );

        return json_encode($csv);

    }

    public function csv_mrw_post($id,Request $request){
      $inputs = $request->all();
      $pedido = Pedidos::find($id);
      $productos = Productos_pedidos::whereHas('transportista', function($query) {
        $query->where('nombre', '=', 'mrw');
      })
      ->whereHas('pedido', function($query) use($pedido){
        $query->where('id','=',$pedido->id);
      })
      ->get();

      $fecha = new DateTime();
      $fecha = $fecha->format('Y-m-d');

      $datos_adicionales = '#SeguimientoSMS=1#';
      try {
        if(($pedido->origen->api_key != null)&&($pedido->origen->api_key != '99999')){
          $this->actualizar_pedidos_ps($pedido->origen->referencia,$pedido->numero_pedido_ps);
        }

        foreach ($productos as $producto) {
          $producto->fecha_envio = $fecha;
          $producto->estado_envio = 1;
          $producto->save();
        }

      } catch(Exception $e){

      }

      $empty='';
      if($inputs['kg-mrw'] > 5){
        $datos_adicionales .= "#TipoServicio=0205#";
      }else{
        $datos_adicionales .= "#TipoServicio=0800#";
      }

      $csv = array('numero_albaran' => $empty,
                    'referencia_envio' => $pedido->numero_albaran,
                    'referencia_bulto' => $empty,
                    'peso' => $inputs['kg-mrw'],
                    'bultos' => $inputs['bultos-mrw'],
                    'fecha_recogida' => $inputs['fecha-mrw'],
                    'observacion' => ''.$pedido->observaciones.'',
                    'nombre_apellido' => $inputs['nombre-mrw'],
                    'direccion' => $inputs['direccion-mrw'],
                    'cp' => trim($inputs['cp-mrw']),
                    'poblacion' => $inputs['ciudad-mrw'],
                    'codigo_pais' => $inputs['pais-mrw'],
                    'telefono' => $inputs['telefono-mrw'],
                    'franquicia' => '',
                    'adicionales' => ''.$datos_adicionales.''
                    );

      return Excel::create('mrw_csv_'.$pedido->numero_albaran , function($excel) use($csv) {
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
          $csv2= implode(';', $csv);
          $csv3= array($csv2);
          //dd($csv3);
          $sheet->row($row , $csv);
          //$header = array_map('strtoupper', $header_valor);
          $sheet->fromArray('', null, 'A1', true);
          //$sheet->getStyle("A1:D1")->getFont()->setBold(true);
        });
      })->export('csv');

    }

    public function csv_tipsa($id,$generar_csv){
      $pedido = Pedidos::find($id);

      $date = getdate();
      $fecha = $date['year'].str_pad($date['mon'], 2, "0", STR_PAD_LEFT).str_pad($date['mday'], 2, "0", STR_PAD_LEFT);

      $tlf = "";
      $tlf = str_replace("/", "", $pedido->cliente->telefono);
      $tlf = trim($tlf);

      if($pedido->cliente->direccion->pais_envio == ""){
        $pais_fact = "ES";
      }else{
        $pais_fact = $pedido->cliente->direccion->pais_envio;
      }

      $empty = "";
      $csv = array('numero_albaran' => $empty,
                    'referencia_envio' => $pedido->numero_albaran,
                    'referencia_bulto' => $empty,
                    'peso' => $empty,
                    'bultos' => $pedido->bultos,
                    'fecha_recogida' => ''.$fecha.'',
                    'observacion' => ''.$pedido->observaciones.'',
                    'nombre_apellido' => ''.$pedido->cliente->nombre_envio.'',
                    'direccion' => ''.$pedido->cliente->direccion->direccion_envio.'',
                    'cp' => ''.trim($pedido->cliente->direccion->cp_envio).'',
                    'poblacion' => ''.$pedido->cliente->direccion->ciudad_envio.'',
                    'codigo_pais' => $pais_fact,
                    'telefono' => ''.$tlf.'',
                    'franquicia' => '',
                    'adicionales' => $empty
                    );
      return json_encode($csv);
    }

    public function csv_tipsa_post($id,Request $request){
      $inputs = $request->all();
      $pedido = Pedidos::find($id);
      $productos = Productos_pedidos::whereHas('transportista', function($query){
        $query->where('nombre', '=', 'tipsa');
      })
      ->whereHas('pedido', function($query) use($pedido){
        $query->where('id','=',$pedido->id);
      })
      ->get();

      $fecha = new DateTime();
      $fecha = $fecha->format('Y-m-d');

      try {
        if(($pedido->origen->api_key != null)&&($pedido->origen->api_key != '99999')){
          $this->actualizar_pedidos_ps($pedido->origen->referencia,$pedido->numero_pedido_ps);
        }

        foreach ($productos as $producto) {
          $producto->fecha_envio = $fecha;
          $producto->estado_envio = 1;
          $producto->save();
        }

      } catch(Exception $e){

      }

      $csv = array( 'inicio' => "",
                    'referencia_envio' => $pedido->numero_albaran,
                    'bultos' => $inputs['bultos-mrw'],
                    'observacion' => ''.$pedido->observaciones.'',
                    'nombre_apellido' => $inputs['nombre-mrw'],
                    'direccion' => $inputs['direccion-mrw'],
                    'cp' => trim($inputs['cp-mrw']),
                    'poblacion' => $inputs['ciudad-mrw'],
                    'codigo_pais' => $inputs['pais-mrw'],
                    'telefono' => $inputs['telefono-mrw'],
                    'correo' => $pedido->cliente->email,
                    'fin' => ""
                    );

        return Excel::create('tipsa_csv_'.$pedido->numero_albaran , function($excel) use($csv) {
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
            $csv2= implode(';', $csv);
            $csv3= array($csv2);
            //dd($csv3);
            $sheet->row($row , $csv3);


            //$header = array_map('strtoupper', $header_valor);
            $sheet->fromArray('', null, 'A1', true);
            //$sheet->getStyle("A1:D1")->getFont()->setBold(true);

          });


        })->export('csv');


    }

    public function csv_szendex($id,$generar_csv){
      $pedido = Pedidos::find($id);

      $datos_adicionales = '#SeguimientoSMS=1#';
      $date = getdate();
      $fecha = $date['year'].str_pad($date['mon'], 2, "0", STR_PAD_LEFT).str_pad($date['mday'], 2, "0", STR_PAD_LEFT);

      $tlf = "";
      $tlf = str_replace("/", "", $pedido->cliente->telefono);
      $tlf = trim($tlf);


      $pais_fact = $pedido->cliente->direccion->pais_envio;
      switch ($pedido->cliente->direccion->pais_envio) {
        case 'ES':
          $pais_fact = 'ESPAÑA';
          break;
        case 'PT':
          $pais_fact = 'PORTUGAL';
          break;
        case 'FR':
          $pais_fact = 'FRANCIA';
          break;
        default:
          $pais_fact = 'ESPAÑA';
          break;
      }




      $empty = "";
      $csv = array('numero_albaran' => $empty,
                    'referencia_envio' => $pedido->numero_albaran,
                    'referencia_bulto' => $empty,
                    'peso' => $empty,
                    'bultos' => $pedido->bultos,
                    'fecha_recogida' => ''.$fecha.'',
                    'observacion' => ''.$pedido->observaciones.'',
                    'nombre_apellido' => ''.$pedido->cliente->nombre_envio.'',
                    'direccion' => ''.$pedido->cliente->direccion->direccion_envio.'',
                    'cp' => ''.trim($pedido->cliente->direccion->cp_envio).'',
                    'poblacion' => ''.$pedido->cliente->direccion->ciudad_envio.'',
                    'codigo_pais' => $pais_fact,
                    'telefono' => ''.$tlf.'',
                    'email' => ''.$pedido->cliente->email.'',
                    'franquicia' => '',
                    'adicionales' => ''.$datos_adicionales.''
                    );

        return json_encode($csv);

    }

    public function csv_szendex_post($id,Request $request){
      $inputs = $request->all();
      $pedido = Pedidos::find($id);
      $productos = Productos_pedidos::whereHas('transportista', function($query) {
        $query->where('nombre', '=', 'mrw');
      })
      ->whereHas('pedido', function($query) use($pedido){
        $query->where('id','=',$pedido->id);
      })
      ->get();

      $fecha = new DateTime();
      $fecha = $fecha->format('Y-m-d');

      $datos_adicionales = '#SeguimientoSMS=1#';
      try {
        if(($pedido->origen->api_key != null)&&($pedido->origen->api_key != '99999')){
          $this->actualizar_pedidos_ps($pedido->origen->referencia,$pedido->numero_pedido_ps);
        }

        foreach ($productos as $producto) {
          $producto->fecha_envio = $fecha;
          $producto->estado_envio = 1;
          $producto->save();
        }

      } catch(Exception $e){

      }

      $empty='';
      if($inputs['kg-mrw'] > 5){
        $datos_adicionales .= "#TipoServicio=0205#";
      }else{
        $datos_adicionales .= "#TipoServicio=0800#";
      }

      switch ($inputs['pais-mrw']) {
        case 'ESPAÑA':
          $codigo_pais = '0050';
          break;
        case 'PORTUGAL':
          $codigo_pais = '0004';
          break;
        default:
          $codigo_pais = '0420';
      }

      $csv = array('referencia_envio' => $pedido->numero_albaran,
                    'peso' => $inputs['kg-mrw'],
                    'bultos' => $inputs['bultos-mrw'],
                    'fecha_recogida' => $inputs['fecha-mrw'],
                    'observacion' => ''.$pedido->observaciones.'',
                    'nombre_apellido' => $inputs['nombre-mrw'],
                    'direccion' => $inputs['direccion-mrw'],
                    'cp' => trim($inputs['cp-mrw']),
                    'poblacion' => $inputs['ciudad-mrw'],
                    'codigo_pais' => $inputs['pais-mrw'],
                    'telefono' => $inputs['telefono-mrw'],
                    'email' => ''.$pedido->cliente->email.'',
                    'código país' => ''.$codigo_pais.''
                    );

      return Excel::create('szendex_csv_'.$pedido->numero_albaran , function($excel) use($csv) {
        $excel->getDefaultStyle()
               ->getAlignment()
               ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getDefaultStyle()
               ->getAlignment()
               ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $excel->sheet('pedido', function($sheet) use($csv) {
          // headers del documento xls
          $header = array('referencia_envio', 'peso', 'bultos', 'fecha_recogida', 'observacion', 'nombre_apellido', 'direccion', 'cp', 'poblacion', 'codigo_pais', 'telefono', 'email', 'tipo_servicio');
          $row = 2;
          //Crear headers
          //añadimos las rows
          //dd($productos_amazon);
          $csv2= implode(';', $csv);
          $csv3= array($csv2);
          //dd($csv3);
          $sheet->row($row , $csv);
          //$header = array_map('strtoupper', $header_valor);
          $sheet->fromArray($header, null, 'A1', true);
          //$sheet->getStyle("A1:D1")->getFont()->setBold(true);
        });
      })->export('xlsx');

    }

    public function pedidos_transportista(Request $request,$nombre_transportista){
      //$this->marcar_enviados();
      $origenes = Origen_pedidos::get();
      $proveedores = Proveedores::get();
      $filtro_origenes = array();
      $filtro_proveedores = array();
      //$incidencias = Incidencias::get();
      $transportista = Transportistas::where('nombre', '=', $nombre_transportista )->first();
      $filtro = $request->query();
      if(!$filtro){
        $listado_pedidos =  Pedidos::whereHas('productos',  function ($query) use($transportista){
                                      $query->whereHas('transportista', function($query) use($transportista){
                                                $query->where('id','=',$transportista->id);
                                      });
                                    })
                                    ->orderBy('id','DESC')
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

        if(isset($filtro["numero_pedido"])&& $filtro["numero_pedido"]!="") $queryRaw .= " and numero_pedido = ".$filtro["numero_pedido"]."";
        if(isset($filtro["fecha_pedido"])&& $filtro["fecha_pedido"]!="") $queryRaw .= " and fecha_pedido >= '".$filtro["fecha_pedido"]."'";
        if(isset($filtro["fecha_pedido_fin"])&& $filtro["fecha_pedido_fin"]!="") $queryRaw .= " and fecha_pedido <= '".$filtro["fecha_pedido_fin"]."'";
        if(isset($filtro["precio"])&& $filtro["precio"]!="") $queryRaw .= " and total <= '".$filtro["precio"]."'";

        if(!isset($filtro["cliente"])){ $filtro["cliente"] = ''; }
        if(!isset($filtro["correo_comprador"])){ $filtro["correo_comprador"] = ''; }
        if(!isset($filtro["telefono_comprador"])){ $filtro["telefono_comprador"] = ''; }
        if(!isset($filtro["estado_incidencia"])){ $filtro["estado_incidencia"] = ''; }

        if(!isset($filtro["nombre_producto"])){ $filtro["nombre_producto"] = ''; }
        if(!isset($filtro["estado_envio"])){ $filtro["estado_envio"] = ''; }


        //Obtenemos los pedidos con paginación
        $listado_pedidos =  Pedidos::whereRaw($queryRaw)
          ->whereHas('cliente',  function ($query) use($filtro) {
            $query->where('nombre_apellidos', 'like', "%".$filtro["cliente"]."%")
                  ->where('email', 'like', "%".$filtro["correo_comprador"]."%")
                  ->where('telefono', 'like', "%".$filtro["telefono_comprador"]."%");
          })
          ->whereHas('productos',  function ($query) use($filtro, $transportista){
            $query->where('nombre_esp', 'like', "%".$filtro["nombre_producto"]."%")
                  ->whereHas('transportista', function($query) use($transportista){
                      $query->where('id','=',$transportista->id);
            });
            if($filtro["estado_envio"] != ''){
              $query->where('estado_envio', '=', $filtro["estado_envio"]);
            }
            if($filtro["estado_incidencia"] != ''){
              $query->whereHas('productos_incidencias', function($query) use($filtro) {
                $query->whereHas('incidencia', function($query) use($filtro){
                  $query->where('estado', '=', $filtro["estado_incidencia"]);
                });
              });
            }
          })
          ->whereHas('origen',  function ($query) use($filtro_origenes) {
            $query->Where( function ($query) use($filtro_origenes) {
                foreach ($filtro_origenes as $origen) {
                  $query->orWhere('referencia', 'like', $origen);
                }
            });
          })
          ->whereHas('productos',  function ($query) use($filtro,$filtro_proveedores){
            foreach ($filtro_proveedores as $proveedor) {
              $query->where('id_proveedor', '=', $proveedor);
              if($filtro["estado_envio"] != ''){
                $query->where('estado_envio', '=', $filtro["estado_envio"]);
              }
              if($filtro["estado_incidencia"] != ''){
                $query->whereHas('productos_incidencias', function($query) use($filtro) {
                  $query->whereHas('incidencia', function($query) use($filtro){
                    $query->where('estado', '=', $filtro["estado_incidencia"]);
                  });
                });
              }
            }
          })
          ->orderBy('id','DESC')
          ->paginate(50);
      }

      $paginaTransportista = NULL;

      return View::make('pedidosnew/inicio', array('listado_pedidos' => $listado_pedidos,
                                                    'origenes' => $origenes,
                                                    'filtro_origenes' => $filtro_origenes,
                                                    'proveedores' => $proveedores,
                                                    'filtro_proveedores' => $filtro_proveedores,
                                                    'paginaTransportista' => $paginaTransportista));
    }

    public function view_proveedor($nombre_proveedor){
      $listado_pedidos = Pedidos::whereHas('productos',  function ($query) use($nombre_proveedor){
                                    $query->whereHas('proveedor', function($query) use($nombre_proveedor){
                                              $query->where('nombre','=',$nombre_proveedor);
                                    })
                                    ->where('estado_proveedor', '=', 0);
                                  })
                                  ->orderBy('id','DESC')
                                  ->paginate(50);

      return View::make('pedidosnew/proveedor', array('listado_pedidos' => $listado_pedidos,
                                                        'nombre_proveedor' => $nombre_proveedor));
    }

    public function aviso_proveedor($nombre_proveedor){
      $a_nompdf = array();
      $a_apdf = array();
      $pedidos = Pedidos::whereHas('productos',  function ($query) use($nombre_proveedor) {
                                    $query->whereHas('proveedor', function($query) use($nombre_proveedor){
                                              $query->where('nombre','=',$nombre_proveedor);
                                    })
                                    ->where('estado_proveedor', '=', 0);
                                  })
                                  ->orderBy('id','DESC')
                                  ->paginate(50);

        foreach ($pedidos as $pedido) {
          $nombre_pdf = "pedido_".$pedido->o_csv.$pedido->numero_pedido.".pdf";
          if(!in_array($nombre_pdf,$a_nompdf)){
            $productos = array();

            foreach ($pedido->productos as $producto_pedido) {
              if($producto_pedido->proveedor->nombre == $nombre_proveedor){
                array_push($productos, $producto_pedido);
              }
            }

          }

          $datos = array('pedido' => $pedido,
                        'productos' => $productos);

          $view = View::make('pedidosnew.albaran', $datos)->render();
          // Inicializamos DOMPDF
          $dompdf = new Dompdf();

          // Renderizamos view::make para poder generar pdf
          $dompdf->loadHtml($view);
          $dompdf->set_option('enable_css_float',true);

          // Renderizamos PDF
          $dompdf->render();
          // Enviamos resultado al navegador
          $output = $dompdf->output();
          array_push($a_apdf,$output);
          array_push($a_nompdf,$nombre_pdf);
        }

        $proveedor = Proveedores::where('nombre','=',$nombre_proveedor)->first();

        if($_SERVER['HTTP_HOST'] == "admin.decowood.es" ){
          if(count($a_apdf) > 0){
            $parametros = array("pedidos" => $pedidos);
            Mail::send('mail.dups', $parametros, function($message) use($a_apdf, $a_nompdf, $proveedor)
            {
              $message->from('info@decowood.es', 'Info ');
              $message->to( $proveedor->email, 'Información')->subject('Nuevos pedidos Decowood '.date("d-m-Y"));
              if($proveedor->email == "ICOMMERS"){
                $message->cc('info@icommers.com', 'Icommers');
              }else if($proveedor->email == "TIC TAC"){
                $message->cc('maderasfelix@gmail.com', 'Icommers');
              }
              $message->cc('sandra@decowood.es', 'sandra');
              $message->cc('info@decowood.es', 'info');
              $message->bcc('developer@decowood.es', 'Developer');
              foreach ($a_apdf as $key => $output) {
                $message->attachData($output, $a_nompdf[$key]);
              }
            });
          }
        }else{
          if(count($a_apdf) > 0){
            $parametros = array("pedidos" => $pedidos);
            Mail::send('mail.dups', $parametros, function($message) use($a_apdf, $a_nompdf,$proveedor)
            {
              $message->from('info@decowood.es', 'Info ');
              $message->to('developer@decowood.es', 'Información')->subject('Nuevos pedidos Decowood '.$proveedor->email.date("d-m-Y"));
              foreach ($a_apdf as $key => $output) {
                $message->attachData($output, $a_nompdf[$key]);
              }
            });
          }
        }
        foreach ($pedidos as $pedido) {

          foreach ($pedido->productos as $producto_pedido) {
            if($producto_pedido->proveedor->nombre == $nombre_proveedor){
              $producto_pedido->estado_proveedor = 1;
              $producto_pedido->save();
            }
          }

        }

        return back();

    }

    public function no_enviados(Request $request){
      //$this->marcar_enviados();
      $origenes = Origen_pedidos::get();
      $proveedores = Proveedores::get();
      $filtro_origenes = array();
      $filtro_proveedores = array();
      //$incidencias = Incidencias::get();
      $filtro = $request->query();
      if(!$filtro){
        $listado_pedidos =  Pedidos::whereHas('productos', function($query){
                                      $query->where('estado_envio', '!=', 1);
                                    })
                                    ->orderBy('fecha_pedido','ASC')
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

        if(isset($filtro["numero_pedido"])&& $filtro["numero_pedido"]!="") $queryRaw .= " and numero_pedido = ".$filtro["numero_pedido"]."";
        if(isset($filtro["fecha_pedido"])&& $filtro["fecha_pedido"]!="") $queryRaw .= " and fecha_pedido >= '".$filtro["fecha_pedido"]."'";
        if(isset($filtro["fecha_pedido_fin"])&& $filtro["fecha_pedido_fin"]!="") $queryRaw .= " and fecha_pedido <= '".$filtro["fecha_pedido_fin"]."'";
        if(isset($filtro["precio"])&& $filtro["precio"]!="") $queryRaw .= " and total <= '".$filtro["precio"]."'";

        if(!isset($filtro["cliente"])){ $filtro["cliente"] = ''; }
        if(!isset($filtro["correo_comprador"])){ $filtro["correo_comprador"] = ''; }
        if(!isset($filtro["telefono_comprador"])){ $filtro["telefono_comprador"] = ''; }
        if(!isset($filtro["estado_incidencia"])){ $filtro["estado_incidencia"] = ''; }

        if(!isset($filtro["nombre_producto"])){ $filtro["nombre_producto"] = ''; }
        if(!isset($filtro["estado_envio"])){ $filtro["estado_envio"] = ''; }

        //dd($filtro);
        //dd($queryRaw);
        //Obtenemos los pedidos con paginación
        $listado_pedidos =  Pedidos::whereRaw($queryRaw)
          ->whereHas('cliente',  function ($query) use($filtro) {
            $query->where('nombre_apellidos', 'like', "%".$filtro["cliente"]."%")
                  ->where('email', 'like', "%".$filtro["correo_comprador"]."%")
                  ->where('telefono', 'like', "%".$filtro["telefono_comprador"]."%");
          })
          ->whereHas('productos',  function ($query) use($filtro){
            $query->where('nombre_esp', 'like', "%".$filtro["nombre_producto"]."%");
            if($filtro["estado_envio"] != ''){
              $query->where('estado_envio', '=', $filtro["estado_envio"]);
            }else{
              $query->where('estado_envio', '!=', 1);
            }
            if($filtro["estado_incidencia"] != ''){
              $query->whereHas('productos_incidencias', function($query) use($filtro) {
                $query->whereHas('incidencia', function($query) use($filtro){
                  $query->where('estado', '=', $filtro["estado_incidencia"]);
                });
              });
            }
          })
          ->whereHas('origen',  function ($query) use($filtro_origenes) {
            $query->Where( function ($query) use($filtro_origenes) {
                foreach ($filtro_origenes as $origen) {
                  $query->orWhere('referencia', 'like', $origen);
                }
            });
          })
          ->whereHas('productos',  function ($query) use($filtro,$filtro_proveedores){
            foreach ($filtro_proveedores as $proveedor) {
              $query->where('id_proveedor', '=', $proveedor);
              if($filtro["estado_envio"] != ''){
                $query->where('estado_envio', '=', $filtro["estado_envio"]);
              }
              if($filtro["estado_incidencia"] != ''){
                $query->whereHas('productos_incidencias', function($query) use($filtro) {
                  $query->whereHas('incidencia', function($query) use($filtro){
                    $query->where('estado', '=', $filtro["estado_incidencia"]);
                  });
                });
              }
            }
          })
          ->orderBy('id','DESC')
          ->paginate(50);
      }

      $paginaTransportista = NULL;

      return View::make('pedidosnew/inicio', array('listado_pedidos' => $listado_pedidos,
                                                    'origenes' => $origenes,
                                                    'filtro_origenes' => $filtro_origenes,
                                                    'proveedores' => $proveedores,
                                                    'filtro_proveedores' => $filtro_proveedores,
                                                    'paginaTransportista' => $paginaTransportista));



    }

    public function aviso_retraso(){


      $this->actualizar_informar_retraso(10);
      $this->actualizar_informar_retraso(20);
      $this->actualizar_informar_retraso(30);




    }

    private function actualizar_informar_retraso($dias){

      $correo_comercial = 'info@decowood.es';
      $correo_2 = 'sandra@decowood.es';
      $correo_3 = 'f.jimenez@decowood.es';
      $correo_4 = 'talavera@decowood.es';
      $correo_5 = 'logistica@decowood.es';
      $correo_developer = 'developer@decowood.es';

      $productos_no_enviados = Productos_pedidos::where('estado_envio','<',$dias)
                                                ->where('estado_envio','!=','1')
                                                ->whereHas('pedido', function($query) use($dias){
                                                  $query->whereRaw('(DATEDIFF(now(),fecha_pedido)) >= '.$dias.'');
                                                })
                                                ->get();


      $pedidos_retraso = Pedidos::whereRaw('(DATEDIFF(now(),fecha_pedido)) >= '.$dias.'')
                                ->whereHas('productos', function($query) use($dias) {
                                      $query->where('estado_envio','<',$dias)
                                            ->where('estado_envio','!=','1');
                                    })
                                  ->get();


      if(sizeof($pedidos_retraso)>0){
        // Mailing
        //$correo_comercial = Auth::user()->email;

        //  dd($correo_comercial);
        // Parametros para el mailing
        $parametros = array("pedidos" => $pedidos_retraso, "productos_no_enviados" => $productos_no_enviados);//parametros para correo.
        // Se envia mensaje al cliente
        Mail::send('mail.informar_retraso_new', $parametros, function($message) use($correo_comercial,$correo_2,$correo_3,$correo_4,$correo_5,$correo_developer,$dias)
        {
          $message->from('info@decowood.es', 'Decowood ADMIN');
          $message->to($correo_comercial, 'Información')->subject('ATENCIÓN: Pedidos con '.$dias.' días de retraso');
          $message->cc($correo_2, 'Sandra');
          $message->cc($correo_3, 'Fernando');
          $message->cc($correo_4, 'Josep Talavera');
          $message->cc($correo_5, 'Herminio');
          $message->bcc($correo_developer, 'Developer');
        });
      }

      //dd($pedidos_retraso);
      /*foreach ($pedidos_retraso as $pedido) {
        $productos_pedido = $productos_no_enviados->where('id_pedido', '=', $pedido->id);

      }*/
      foreach ($productos_no_enviados as $producto) {
        $producto->estado_envio = $dias;
        $producto->save();
      }
    }

    public function gexcel_pedidos(Request $request){
      $ruta_doc_xls = "documentos/albaranes/agrupados/";
      $nombre_xls = "";

      // Request con ids para rellenar excel
      $ids = json_decode($request->all()["ids"], true);
      $filtersE = json_decode($request->all()["filterse"], true);

      // Generamos la estructura del XLS.
      return Excel::create('pedidos_admin', function($excel) use($ids,$filtersE) {

        $excel->sheet('Sheetname', function($sheet) use($ids,$filtersE) {
          // headers del documento xls
          $header = [];
          $row = 1;


          $header = array('id','origen','numero_pedido','numero_albaran','fecha_pedido','cliente','productos','total','email','telefono','direccion_envio');

          // Bucle para rellenar el documento segun el numero de pedidos
          foreach($ids as $key => $id){
            $row++;
            // Cargamos datos del pedido
            $pedido = Pedidos::find($id["value"]);

            $productos = '';
            foreach ($pedido->productos as $producto) {
              $productos .= $producto->nombre_esp.',';
            }
            $product_excel = array(
              $pedido->id,
              $pedido->origen->nombre,
              $pedido->numero_pedido,
              $pedido->numero_albaran,
              $pedido->fecha_pedido,
              $pedido->cliente->nombre_apellidos,
              $productos,
              $pedido->total,
              $pedido->cliente->email,
              $pedido->cliente->telefono,
              $pedido->cliente->direccion->direccion_envio
            );



            $sheet->row($row, $product_excel);


          }

          $sheet->fromArray($header, null, 'A1', true);


        });

      })->export('xls');
    }


    public function pruebacron(){

      $parametros = array();

      Mail::send('mail.informar_envio_plantilla', $parametros, function($message)
      {
        $message->from('info@decowood.es', 'Decowood ADMIN');
        $message->to('developer@decowood.es', 'Información')->subject('proba cron');
      });
    }
}
