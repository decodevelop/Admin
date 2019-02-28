<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use View;
use Illuminate\Support\Facades\DB;
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

class PedidosController extends Controller
{
    /**
     * Constructor y middleware
     * @return void(true/false auth)
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	// **** IMPORTACIÓN ****
	
/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function importar_csv()
    {
        return view('herramientas/importar_csv');
    }

	/**
     * Orden datos CSV de WIX
		[0] => ﻿Order #
		[1] => Date
		[2] => Time
		[3] => Billing Customer
		[4] => Billing Country
		[5] => Billing State
		[6] => Billing City
		[7] => Billing Street Name&Number
		[8] => Billing Zip Code
		[9] => Shipping Customer
		[10] => Shipping Country
		[11] => Shipping State
		[12] => Shipping City
		[13] => Shipping Street Name&Number
		[14] => Shipping Zip Code
		[15] => Buyer's Phone #
		[16] => Buyer's Email
		[17] => Delivery Method
		[18] => Item's Name
		[19] => Item's Variant
		[20] => SKU
		[21] => Qty
		[22] => Item's Price
		[23] => Item's Weight
		[24] => Item's Custom Text
		[25] => Coupon
		[26] => Shipping
		[27] => Tax
		[28] => Total
		[29] => Payment Method
		[30] => Payment
		[31] => Fulfillment
		
		Campos cliente(db) -> id, dni, nombre_apellidos, fecha_nacimiento, email, telefono, created_at, updated_at
     */
	 
	public function importar_csv_post(Request $request)
	{
		// Codigo origen datos del CSV importado.
		$origen_csv = $request['o_csv'];
		//echo "extension => ".$request->csv->getClientOriginalExtension();
		//exit();
		// Control de registros
		$registros = 0;
		$repetidos = 0;
		$ped_anterior = "DFAULT00000";
		$last_ps_id = 	"NFAULT00000";
	
		
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
		$nombreFichero = 'CSV_'.$origen_csv.'_'.date('d-m-Y_H-i-s').'.'.$request->csv->getClientOriginalExtension();
		// 3. Subir fichero al directorio de archivos
		$request->csv->move(public_path('documentos'), $nombreFichero);
		
		// Paso 3.1: Eliminar documentos anteriores a 1 semana.
		//* Hay que crear un CRON en el sistema para que elimine cierto numero cada semana, aunque se puede hacer
		//* el cleanup aquí mismo al cargar un nuevo fichero.
		
		
		// 4. Abrimos el documento para realizar la lectura.
		$documento_csv = fopen(public_path('documentos').'/'.$nombreFichero,"r");

		// Paso 5: Bucle donde validaremos los registros del documento 
		// y en caso de no estar repetidos los guardaremos en DB.
		while(!feof($documento_csv))
		{

			// Leemos la primera fila del documento, segun el origen del CSV utilizamos unos parametros u otros.
			if($origen_csv=="CA"){
				$fila = fgetcsv($documento_csv,$length=0,$delimiter=';',$enclosure='"');
			} else {
				$fila = fgetcsv($documento_csv);
			}

			// Para evitar uploads de csv erroneos, comprobamos si en la casilla 32 hay información un campo extra del export de prestashop.
			if($origen_csv=="CA" && !isset($fila[32])){
				return back()
				->with(array("danger" => "Error: Fichero con formato no valido, no se ha podido cargar."))
				->with('ptime','Fecha y hora de error: '.date('d-m-Y H:i:s'))
				->with('user','Cargado por: '.Auth::user()->apodo)
				->with('path',$nombreFichero);
			}

			// Saltamos la primera fila con los atributos del documento csv.
			if($registros==0){$registros++;continue;}
			
			// Comprobamos el primer registro del documento,
			// si existe, nos lo saltamos y lo marcamos como repetido.
			//echo "<pre>";
			//print_r($fila);
			//exit();
			// Si el O_CSV es PRESTASHOP, cambiaremos de forma para validar
			if($origen_csv=="CA"){
				$pedido = new Pedidos_wix_importados;
				if (Pedidos_wix_importados::where('numero_pedido_ps', '=', $fila[0])->where('nombre_producto', '=', $fila[18])->where('variante_producto', '=', $fila[19])->where('sku_producto', '=', $fila[20])->where('o_csv', '=', $origen_csv)->exists()){
					$repetidos++;
					continue;
				}
			} else {
				$pedido = new Pedidos_wix_importados;
				if (Pedidos_wix_importados::where('numero_pedido', '=', $fila[0])->where('nombre_producto', '=', $fila[18])->where('variante_producto', '=', $fila[19])->where('sku_producto', '=', $fila[20])->where('o_csv', '=', $origen_csv)->exists()){
					$repetidos++;
					continue;
				}
			}
			 
			
			/* ---- * --  * --  * --  * --  * --  * --  * --  * --  
			 ____ ____ ____ ____ ____ ____ ____ ____ ____ ____ 
			||I |||M |||P |||O |||R |||T |||A |||N |||T |||E ||
			||__|||__|||__|||__|||__|||__|||__|||__|||__|||__||
			|/__\|/__\|/__\|/__\|/__\|/__\|/__\|/__\|/__\|/__\|

			# Habría que separar los resultados en tablas diferentes,
			# provisionalmente se cargan todos los datos en una única tabla
			# de forma identica a las exportaciónes de WIX, esto hay que cambiarlo
			# en un futuro, ya que no es optimo y reduce el rango de ampliación
			# a la vez que dificulta la programación de nuevas funciones.
			
			* --  * --  * --  * --  * --  * --  * -- * ------*/
			if($last_ps_id!=$fila[0]){
				$last_ps_id = $fila[0];
				$lastID = (Pedidos_wix_importados::max('numero_pedido')+1);
			}
			
			
			// Cargamos los datos del pedido en el objeto y los guardamos en db. ( segun si es wix o prestashop varian los datos )
			if($origen_csv=="CA"){ // Según su origen cargamos unos datos u otros.
				$pedido->numero_pedido = $lastID;
				$pedido->numero_pedido_ps = $fila[0]; // Campo para identificar los pedidos importados de prestashop
			} else {
				$pedido->numero_pedido = $fila[0];
			}
			$pedido->fecha_pedido = date_format(date_create($fila[1]), 'Y-m-d');
			$pedido->hora_pedido = $fila[2];
			/* datos facturación */
			$pedido->cliente_facturacion = $fila[3];
			$pedido->pais_facturacion = $fila[4];
			$pedido->estado_facturacion = $fila[5];
			$pedido->ciudad_facturacion = $fila[6];
			$pedido->direccion_facturacion = $fila[7];
			$pedido->cp_facturacion = $fila[8];
			/* datos envio */
			$pedido->cliente_envio = $fila[9];
			$pedido->pais_envio = $fila[10];
			$pedido->estado_envio = $fila[11];
			$pedido->ciudad_envio = $fila[12];
			$pedido->direccion_envio = $fila[13];
			$pedido->cp_envio = $fila[14];
			/* datos contacto */
			$pedido->telefono_comprador = $fila[15];
			$pedido->correo_comprador = $fila[16];
			/* Adicional envío */
			$pedido->metodo_entrega = $fila[17];
			/* detalles producto*/
			$pedido->nombre_producto = $fila[18];
			$pedido->variante_producto = $fila[19];
			$pedido->sku_producto = $fila[20];
			$pedido->cantidad_producto = $fila[21];
			$pedido->precio_producto = $fila[22];
			$pedido->peso_producto = $fila[23];
			$pedido->texto_especial_producto = $fila[24];
			/* extras compra */
			$pedido->cupon = $fila[25];
			$pedido->envio = $fila[26];
			$pedido->tasas = $fila[27];
			$pedido->total = $fila[28];
			/* Información metodo de pago */
			$pedido->forma_de_pago = $fila[29];
			$pedido->pago = $fila[30];
			$pedido->orden_completada = $fila[31];
			$pedido->o_csv = $origen_csv;
			
			// Asignamos como principal el registro
			// Guardamos NUM_PED Y O_CSV para comparar con el siguiente registro.
			if($ped_anterior!=($pedido->o_csv.$pedido->numero_pedido)){
				$pedido->entrada_principal = 1;
				$ped_anterior = $pedido->o_csv.$pedido->numero_pedido;
			} 
			$pedido->save();
			
			
			
			
			
			
			
			// Si se inserta registro, se suma +1 al contador de registros insertados.
			$registros++;
			
			
		}
		// 6. Restamos -1 a registros para eliminar el conteo de la fila de atributos.
		$registros--;
		
		
		// 7. Preparamos mensaje y retornamos el resultado a la vista origen.
		if($registros==0) {
			$mensaje['info'] = 'CSV se ha subido con exito. No se han cargado los registros, ya existen.';
		} else if($registros > 0 && $repetidos > 0){
			$mensaje['success'] = 'CSV se han subido con exito ('.$registros.') registros. No subidos ('.$repetidos.') ya existen.';
		} else {
			$mensaje['success'] = 'CSV cargado con exito con un total de ( '.$registros.' ) registros insertados.';
		}
		return back()
		->with($mensaje)
		->with('ptime','Fecha y hora de carga: '.date('d-m-Y H:i:s'))
		->with('user','Cargado por: '.Auth::user()->apodo)
		->with('path',$nombreFichero);
		
	}
	
	// **** PEDIDOS ****
	
	/**
     * Cargamos la vista con los pedidos,
	 * Se aplicaran los filtros que se hayan especificado.
     *
     * @return view
     */
    public function index(Request $request){

		/*-------------- ORDENACIONES --------------*/
		$getParams = $request->query();
		if(isset($getParams["ob"],$getParams["obt"])){
			$orderBy = $getParams["ob"];
			$orderByType = $getParams["obt"];
		} else {
			$orderBy = "created_at";
			$orderByType = "desc";
			$orderBy2 = "numero_pedido";
			$orderByType2 = "desc";
		}
		
		/*-------------- FILTROS --------------*/
		$where = "1=1 ";
		if(isset($getParams["id"]) && $getParams["id"]!="") $where .= " and id = ".$getParams["id"]."";
		if(isset($getParams["o_csv"])&& $getParams["o_csv"]!="") $where .= " and o_csv = '".$getParams["o_csv"]."'";
		if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
		if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
		if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
		if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido = '".$getParams["fecha_pedido"]."'";
		if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
		if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
		if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."'";

		
		$pedidos_agrupados = "";
		
		// Variables de busqueda y retención.
		$last_value = "";
		$keyIndex = "";
		
		
		/*-------------- QUERY MySQL -------------- orderBy("numero_pedido", "desc")-> */
		$listado_pedidos = DB::table('pedidos_wix_importados')->whereRaw($where)->orderBy($orderBy,$orderByType)->orderBy($orderBy2,$orderByType)->paginate(30);
		
		
		/*-------------- PROCESAR RESULTADO --------------*/
		
		/* Obtenemos un array con los números de pedido para despues buscar el total de pedidos en el mismo id */
		foreach($listado_pedidos as $key => $value) {

			if($key==0){
				$last_value = $value->o_csv.$value->numero_pedido;
				$keyIndex = $key;
			}
			
			if($value->o_csv.$value->numero_pedido==$last_value && $key!=0){
				//$repetidos++;
				$listado_pedidos[$keyIndex]->nombre_producto .= ",".$value->nombre_producto;
				$listado_pedidos[$keyIndex]->variante_producto .= ",".$value->variante_producto;
				$listado_pedidos[$keyIndex]->sku_producto .= ",".$value->sku_producto;
				$listado_pedidos[$keyIndex]->cantidad_producto .= ",".$value->cantidad_producto;
				$listado_pedidos[$keyIndex]->precio_producto .= ",".$value->precio_producto;
				unset($listado_pedidos[$key]);
				continue;
			} else {
				//$norepetidos++;
				$keyIndex = $key;
				$last_value = $value->o_csv.$value->numero_pedido;
			}
			
		}
		
		return View::make('pedidos/inicio', array('listado_pedidos' => $listado_pedidos, 'filtros' => array($orderBy, $orderByType)));
	}
	
	/**
     * No se utiliza, habrá que eliminarlo en futuros updates.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request){
		return View::make('pedidos/datatable', array('listado_pedidos' => $request["listado_pedidos"]));
	}
	
	/**
     * Carga el detalle del pedido seleccionado.
     *
     * @return view
     */
    public function obtener_detalles($id){
		$detalles_pedido = Pedidos_wix_importados::find($id);
		$productos_pedido = Pedidos_wix_importados::where('numero_pedido','=',$detalles_pedido->numero_pedido)->where('o_csv','=',$detalles_pedido->o_csv)->get();
		$user = User::find($detalles_pedido->creador_incidencia);
		if(@$user->apodo!=null){
			$detalles_pedido->creador_incidencia = $user->apodo;
		} else {
			$detalles_pedido->creador_incidencia = "null";
		}		
		return View::make('pedidos/detalle', array('detalles_pedido' => $detalles_pedido, 'productos_pedido' => $productos_pedido));
	}
	
	
	/**
     * Actualiza con nuevos datos la incidencia del pedido especificado.
     * @return $mensaje_resultado
     */
    public function actualizar_detalles_incidencia($id, Request $request){
		
		$post = $request->all();
		
		try {
			if(isset($post["mensaje_observacion"])){
				$pedido = Pedidos_wix_importados::find($id);
				$pedido->observaciones = $post['mensaje_observacion'];
				$pedido->save();
				return "Se ha actualizado correctamente la observación del pedido.";
			} else if (isset($post["mensaje_incidencia"])){
				$pedido = Pedidos_wix_importados::find($id);
				$pedido->estado_incidencia = $post['estado_incidencia'];
				$pedido->mensaje_incidencia = $post['mensaje_incidencia'];
				if($pedido->creador_incidencia==null) 
				$pedido->creador_incidencia = Auth::user()->id;
				$pedido->save();
				return "Se ha actualizado correctamente el estado de la incidencia.";
			}
		} catch(Exception $e){
			return "Ha habido un error durante la actualización, si el error persiste contacte con developer@decowood.es";
		}
		
		
		
		
	}
	
	/**
     * Se intenta enviar y marcar como enviado el pedido.
     *
     * @return $mensaje_resultado
     */
    public function enviar($id){
		
		// Datos pedido y productos
		$pedido = Pedidos_wix_importados::find($id);
		$productos_pedido = Pedidos_wix_importados::where('numero_pedido','=',$pedido->numero_pedido)->where('o_csv','=',$pedido->o_csv)->get();
		$transportista = $pedido->metodo_entrega;
		$cliente = $pedido->cliente_facturacion;

		// Formato fecha update
		$fecha = new DateTime();
		$fecha = $fecha->format('Y-m-d');
		
		// Si se desea enviar notificación, realizamos comprobacion.
		if($_GET["notificar"]=="si"){
			// Mailing
			$correo_comercial = Auth::user()->email;
			$titulo = "Hola, ".$cliente." tu pedido ha salido enviado.";
			$email_cliente = ($pedido->correo_comprador) ? $pedido->correo_comprador : "Cliente";
			
			// Parametros para el mailing
			$parametros = array("pedido" => $pedido->toArray(), 'productos' => $productos_pedido->toArray());
			
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
		$resultado = array('0' => "", '1' => "");
		try {
			
			$pedido->fecha_envio = $fecha;
			$pedido->enviado = 1;
			$pedido->save();
			// Llenamos array a retornar.
			$resultado[0] = $mensaje;
			$resultado[1] = $fecha;
		} catch(Exception $e){
			$resultado[0] = "Ha habido un error durante la actualización, si el error persiste contacte con developer@decowood.es";
			$resultado[1] = "";
		}
		// Retornamos array en formato json_decode
		return json_encode($resultado);
		
		
		
	}
	
	/**
     * Carga vista para añadir un nuevo pedido de forma manual.
     *
     * @return view
     */
    public function nuevo(){
		return View::make('pedidos/nuevo');
	}

	/**
     * Guarda un pedido NUEVO.
     *
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request){

		$mensaje = "";
		
		try {
			
			$ultimoPedido = (Pedidos_wix_importados::where("o_csv","=",$request['o_csv'])->max('numero_pedido')+1);
			
			foreach($request['nombre_producto'] as $key => $producto){
				
				$pedido = new Pedidos_wix_importados;
				
				if (Pedidos_wix_importados::where('numero_pedido', '=', $ultimoPedido)->where('nombre_producto', '=', $request['nombre_producto'][$key])->where('variante_producto', '=', $request['variante_producto'][$key])->where('sku_producto', '=', $request['sku_producto'][$key])->where('o_csv', '=', $request['o_csv'])->exists()){
					$repetidos++;
					continue;
				}
				// Formateamos numero 
				$ultimoPedido = sprintf( '%05d', $ultimoPedido );
				// Cargamos los datos del pedido en el objeto y los guardamos en db.
				$pedido->numero_pedido = $ultimoPedido;
				$pedido->codigo_factura = $request['codigo_factura'];
				$pedido->fecha_pedido = date_format(date_create($request['fecha_pedido']), 'Y-m-d');
				$pedido->hora_pedido = $request['hora_pedido'];
				/* datos facturación */
				$pedido->cliente_facturacion = $request['cliente_facturacion'];
				$pedido->pais_facturacion = $request['pais_facturacion'];
				$pedido->estado_facturacion = $request['estado_facturacion'];
				$pedido->ciudad_facturacion = $request['ciudad_facturacion'];
				$pedido->direccion_facturacion = $request['direccion_facturacion'];
				$pedido->cp_facturacion = $request['cp_facturacion'];
				/* datos envio */
				$pedido->cliente_envio = $request['cliente_envio'];
				$pedido->pais_envio = $request['pais_envio'];
				$pedido->estado_envio = $request['estado_envio'];
				$pedido->ciudad_envio = $request['ciudad_envio'];
				$pedido->direccion_envio = $request['direccion_envio'];
				$pedido->cp_envio = $request['cp_envio'];
				/* datos contacto */
				$pedido->telefono_comprador = $request['telefono_comprador'];
				$pedido->correo_comprador = $request['correo_comprador'];
				/* Adicional envío */
				$pedido->metodo_entrega = $request['metodo_entrega'];
				/* detalles producto*/
				$pedido->nombre_producto = $request['nombre_producto'][$key];
				$pedido->variante_producto = $request['variante_producto'][$key];
				$pedido->sku_producto = $request['sku_producto'][$key];
				$pedido->cantidad_producto = $request['cantidad_producto'][$key];
				$pedido->precio_producto = $request['precio_producto'][$key];
				$pedido->peso_producto = $request['peso_producto'][$key];
				$pedido->texto_especial_producto = $request['texto_especial_producto'][$key];
				/* extras compra */
				$pedido->cupon = $request['cupon'];
				$pedido->envio = $request['envio'];
				$pedido->tasas = 0.0; // $request['tasas']; - no se utiliza por ahora.
				$pedido->total = $request['total'];
				// Marcamos como entrada principal el primer objeto que guardaremos en la db.
				if($key==0){
					$pedido->entrada_principal = 1;
				}
				/* Información metodo de pago */
				$pedido->forma_de_pago = $request['forma_de_pago'];
				$pedido->pago = $request['pago'];
				$pedido->orden_completada = $request['orden_completada'];
				$pedido->o_csv = $request['o_csv'];
				$pedido->save();
			}
			$mensaje = "Pedido insertado correctamente";
		} catch(\Exception $e){
			$mensaje = "No se ha podido cargar, debido a algún registro repetido o dato incorrecto, revisa los datos.";
			return back()
			->with('mensaje',$mensaje)->withInput();
		}
		
		return back()
		->with('mensaje',$mensaje);
		
		
		
	}
	
	/**
     * Generador de pedidos en formato XSL.
     *
     * @return $documento_xsl;
     */
    public function gexcel_pedidos(Request $request){
		// Rutas de guardado ficheros excel
		$ruta_doc_xls = "documentos/albaranes/agrupados/";
		$nombre_xls = "";

		// Request con ids para rellenar excel
		$ids = json_decode($request->all()["ids"], true);
		
		// Generamos la estructura del XLS.
		return Excel::create('nombre_excel2', function($excel) use($ids) {
			$excel->sheet('Sheetname', function($sheet) use($ids) {
				// headers del documento xls
				$header = [];
				$row = 1;
				
				// montamos header del documento
				foreach(Pedidos_wix_importados::find(1)->toArray() as $key => $atributos){
						array_push($header, $key);
				}
				//print_r($header);
				// Bucle para rellenar el documento segun el numero de pedidos
				foreach($ids as $key => $id){
					// Cargamos datos del pedido
					$pedido = Pedidos_wix_importados::find($id["value"]);
					$productos_pedido = Pedidos_wix_importados::where('numero_pedido','=',$pedido->numero_pedido)->get();
					
					// Realizamos comprobaciones para cargar pedidos.
					if(count($productos_pedido) != 1){
						foreach($productos_pedido as $key2 => $producto_pedido){
							$row++;
							$sheet->row($row, $producto_pedido->toArray());
						}
					} else {
						$row++;
						$sheet->row($row, $productos_pedido[0]->toArray());
					}
				
				}
				//print_r($header);
				//kaboom();
				
				$sheet->fromArray($header, null, 'A1', true);
				
			});
		})->export('xls');
		
	}

	/**
     * Generador Albaran en formato PDF.
	 * Esta funcion se encarga de generar los albaranes para bultos, únicos
	 * y para múltiples bultos en un mismo pedido.
     *
     * @return $dompdf->stream($nombre_pdf);
     */
    public function gpdf_albaran($idm, Request $request){
		
		// Inicializamos variables a utilizar durante el proceso
		$ids = null;
		$pedido = [];
		$productos_pedido = [];
		
		
		// Si existen las IDS de los diferentes bultos los asignamos a una variable $ids.
		if(isset($request->all()["ids"]))$ids = json_decode($request->all()["ids"],true);

		// Si existen tanto la ID principal, y además se ha inicializado un array de $ids, procesamos los bultos.
		if(isset($idm,$ids)){
			$idsfind = [];
			// Separamos las IDS para formar array y así realizar la consulta con eloquent.
			foreach($ids as $key => &$id){
				$id = $id["value"];
				array_push($idsfind, $id);
			}
			// Buscamos los detalles y productos del pedido principal ( primario ).
			// !!! ES POSIBLE que en un futuro haya que mejorar la forma de leer este numero para evitar errores.
			$pedido = Pedidos_wix_importados::find($idm);
			
			// Obtenemos los ids de los productos que apareceran en el albaran
			$ids = implode(",",$ids); // no se usa?
			
			// buscamos los productos
			$productos_pedido = Pedidos_wix_importados::whereIn('id',$idsfind)->get();
			
		} else {// si no existe procesaremos de forma normal los datos como un solo bulto.
			$nombre_pdf = "";
			$view = "";
			// Seleccionamos el pedido principal que contiene los datos correctos ( los demás deberían contener los mismos datos pero por fallos no todos lo contienen )
			// de esta forma solventamos posibles errores, no es optimo, hay que migrarlo en un futuro.
			$pedido = Pedidos_wix_importados::find($idm);
			// Obtenemos los "demás" pedidos ( aunque todos son lo mismo ).
			$productos_pedido = Pedidos_wix_importados::where('numero_pedido','=',$pedido->numero_pedido)->where('o_csv','=',$pedido->o_csv)->get();
			//boom();
		}
		
		//plox();
		$datos = array('pedido' => $pedido->toArray(),'productos' => $productos_pedido->toArray());
		// Inicializamos clase Dompdf
		$nombre_pdf = "pedido_".$pedido->o_csv.$pedido->numero_pedido;
		$view = View::make('pedidos.albaran', $datos)->render();
		
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
	
	/**
     * Se encarga de generar un pdf con multiples albaranes de distintos productos
	 * seleccionados en el listado mediante checkbox.
     *
     * @return \Illuminate\Http\Response
     */
    public function gpdf_albaranes(Request $request){
		$ruta_pdf = "documentos/albaranes/agrupados/";
		$nombre_pdf = "";
		$view = "";
		$ids = json_decode($request->all()["ids"], true);
		foreach($ids as $key => $id){
			$pedido = Pedidos_wix_importados::find($id["value"]);
			$productos_pedido = Pedidos_wix_importados::where('numero_pedido','=',$pedido->numero_pedido)->where('o_csv','=',$pedido->o_csv)->get();
			$datos = array('pedido' => $pedido->toArray(),'productos' => $productos_pedido->toArray());
			$view .= View::make('pedidos.albaran', $datos)->render();
			$nombre_pdf = "pedidos_mult_".$pedido->o_csv;
		}
		
		$dompdf = new Dompdf();
			
		// Renderizamos view::make para poder generar pdf
		$dompdf->loadHtml($view);
		$dompdf->set_option('enable_css_float',true);

		// Renderizamos PDF
		$dompdf->render();
		$output = $dompdf->output();
		// Guardamos fichero y enviamos resultado al navegador
		file_put_contents($ruta_pdf.$nombre_pdf."_".count($ids).".pdf", $output);
		return $dompdf->stream($nombre_pdf);
		
	}
	
	/**
     * Muestra la vista para modificar el pedido y sus productos.
     *
     * @return view
     */
    public function modificar($id){
		$pedido = Pedidos_wix_importados::find($id);
		$productos_pedido = Pedidos_wix_importados::where('numero_pedido','=',$pedido->numero_pedido)->where('o_csv','=',$pedido->o_csv)->get();
		return View::make('pedidos.modificar', array("detalles_pedido" => $pedido, 'productos_pedido' => $productos_pedido));
    }
	
	
		/**
     * Muestra la vista para modificar el pedido y sus productos.
     *
     * @return view
     */
    public function eliminar(Request $request){
		
		try {
			// Asignamos variables
			$id = $request->all()["id"];
			$eliminados = "(".$id.") reg [ ";

			$pedido = Pedidos_wix_importados::find($id);
			$productos_pedido = Pedidos_wix_importados::where('numero_pedido','=',$pedido->numero_pedido)->where('o_csv', '=', $pedido->o_csv)->get();
			foreach($productos_pedido as $key => $producto){
				$eliminados .= "{".$producto->id.":".$producto->nombre_producto."} ";
				$productos_pedido[$key]->delete();
			}
			$eliminados .= "]";
			return "Eliminado correctamente: ".$eliminados;
		} catch(\Exception $err){
			return "No se ha podido eliminar, intentelo de nuevo o contacte con el administrador.".var_dump();
		}
    }
	
	/**
     * Actualiza los detalles del producto y pedido.
     *
     * @return \Illuminate\Http\Response
     */
    public function actualizar($id, Request $request){
		$inputs = $request->all();
		$pedido = Pedidos_wix_importados::find($id);
		$atributos_pedido = $pedido->getAttributes();

		// Comparamos los inputs, y si el atributo existe, asignamos el valor modificado.
		foreach($inputs as $key => $input){
			if($key=="_token" || $key=="productos_serializados") continue;
			if(array_key_exists($key ,$atributos_pedido)){
				$pedido->$key = $inputs[$key];
			} 
		}
		/* PEDIDO PRINCIPAL */
		$pedido->entrada_principal = 1;
		/* Guardamos los detalles del pedido */
		$pedido->save();
		
		/* Procesamos los productos del pedido y añadimos o eliminamos en funcion del resultado */
		$productos = json_decode($inputs["productos_serializados"], true);
		foreach($productos["id"] as $key2 => $atributo){
			// Si es nuevo añadimos, de lo contrario creamos uno nuevo
			if($productos["id"][$key2]["value"]==0){
				/* Si nos pasan nuevos productos, creamos nuevo registro, asignamos solo la información básica del pedido */
				$nuevo_producto = new Pedidos_wix_importados;
				/* datos del pedido */
				//$nuevo_producto->cliente_facturacion = $pedido->cliente_facturacion;
				$nuevo_producto->numero_pedido = $pedido->numero_pedido;
				$nuevo_producto->fecha_pedido = $pedido->fecha_pedido;
				$nuevo_producto->o_csv = $pedido->o_csv;
				/* detalles del producto */
				$nuevo_producto->nombre_producto = $productos["nombre_producto"][$key2]["value"];
				$nuevo_producto->variante_producto = $productos["variante_producto"][$key2]["value"];
				$nuevo_producto->sku_producto = $productos["sku_producto"][$key2]["value"];
				$nuevo_producto->cantidad_producto = $productos["cantidad_producto"][$key2]["value"];
				$nuevo_producto->precio_producto = $productos["precio_producto"][$key2]["value"];
				/* importe pedido */
				//$nuevo_producto->total = $productos["total"][$key2]["value"];
				/* created_at */
				$nuevo_producto->created_at = $pedido->created_at;
				/* SAVE */
				$nuevo_producto->save();
				//echo "save new<br>";
			} else {
				if($productos["nombre_producto"][$key2]["value"]=="" && $productos["variante_producto"][$key2]["value"]=="" && 
				$productos["sku_producto"][$key2]["value"]=="" && $productos["cantidad_producto"][$key2]["value"]==""){
					$modificar_producto = Pedidos_wix_importados::find($productos["id"][$key2]["value"]);
					$modificar_producto->delete();
				} else {
				/* Si ya existe, modificamos */
				/* datos del pedido */
				$modificar_producto = Pedidos_wix_importados::find($productos["id"][$key2]["value"]);
				$modificar_producto->numero_pedido = $pedido->numero_pedido;
				/* datos envio */
				$modificar_producto->pais_envio = $pedido->pais_envio;
				$modificar_producto->cp_envio = $pedido->cp_envio;
				$modificar_producto->fecha_pedido = $pedido->fecha_pedido;
				$modificar_producto->hora_pedido = $pedido->hora_pedido;
				/* Origen CSV */
				$modificar_producto->o_csv = $pedido->o_csv;
				/* detalles del producto */
				$modificar_producto->nombre_producto = $productos["nombre_producto"][$key2]["value"];
				$modificar_producto->variante_producto = $productos["variante_producto"][$key2]["value"];
				$modificar_producto->sku_producto = $productos["sku_producto"][$key2]["value"];
				$modificar_producto->cantidad_producto = $productos["cantidad_producto"][$key2]["value"];
				$modificar_producto->precio_producto = $productos["precio_producto"][$key2]["value"];
				/* importe pedido */
				//$modificar_producto->total = $productos["total"][$key2]["value"];
				/* created_at */
				$modificar_producto->created_at = $pedido->created_at;
				/* SAVE */
				$modificar_producto->save();
				//echo "save modif<br>";
				}
			}
		}
		
		//boom();
		$productos_pedido = Pedidos_wix_importados::where('numero_pedido','=',$pedido->numero_pedido)->where('o_csv','=',$pedido->o_csv)->get();
		
		return redirect('pedidos/detalle/'.$id)->with('mensaje', 'El pedido se ha actualizado correctamente.');
		//return View::make('pedidos.modificar', array("detalles_pedido" => $pedido, 'productos_pedido' => $productos_pedido));
    }

    public function crear_observacion_bultos($id, Request $request){
    	try {
			$pedido = Pedidos_wix_importados::find($id);
			if($request["bultos"]==0){
				$pedido->observaciones = "";
			} else {
				if($request["bultos"]==1){
					$num_bultos = " BULTO";
				} else {
					$num_bultos = " BULTOS";
				}
				$pedido->observaciones = $request["bultos"].$num_bultos;
			}
	    	
	    	$pedido->save();
    	} catch(Exception $e) {
			return "No se ha podido actualizar, contactar con el administrador developer@decowood.es";
		}
    	return "Actualizado.";
    }
}
