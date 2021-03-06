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

    public function importar_observaciones()
    {
        return view('herramientas/importar_observaciones');
    }


	/*
     * Orden datos CSV de WIX
    	*	[0] => ﻿Order #
    	*	[1] => Date
    	*	[2] => Time
    	*	[3] => Billing Customer
    	*	[4] => Billing Country
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
    		[24] => proveedor
    		[25] => Coupon
    		[26] => Shipping
    		[27] => Tax
    		[28] => Total
    		[29] => Payment Method
    		[30] => Payment
    		[31] => Fulfillment

    		Campos cliente(db) -> id, dni, nombre_apellidos, fecha_nacimiento, email, telefono, created_at, updated_at
     */

    public function importar_csv_post(Request $request){
      	// Codigo origen datos del CSV importado.
      	$origen_csv = $request['o_csv'];
        $origen_csv_ps = $request['o_csv'];
      	//echo "extension => ".$request->csv->getClientOriginalExtension();
      	//exit();
      	// Control de registros
      	$registros = 0;
      	$repetidos = 0;
      	$ped_anterior = "DFAULT00000";
      	$last_ps_id = 	"NFAULT00000";
        $created = date("Y-m-d H:i:s");


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
      		//if($origen_csv_ps=="CA" || $origen_csv_ps=="CB" || $origen_csv_ps=="CN" || $origen_csv_ps=="DW"  || $origen_csv_ps=="CJ" ||  $origen_csv_ps=="TT" || $origen_csv_ps == 'FS' || $origen_csv_ps=="FE" ){
      			$fila = fgetcsv($documento_csv,$length=0,$delimiter=';',$enclosure='"');
      		/*} else {
      			$fila = fgetcsv($documento_csv);
      		}*/

          // Saltamos la primera fila con los atributos del documento csv.
      		if($registros==0){$registros++;continue;}
          //dd($fila[32]);
      		// Para evitar uploads de csv erroneos, comprobamos si en la casilla 32 hay información un campo extra del export de prestashop.
      		if(($origen_csv_ps=="CA" || $origen_csv_ps=="CB" || $origen_csv_ps=="CN" || $origen_csv_ps=="DW" || $origen_csv_ps=="CJ" ||  $origen_csv_ps=="TT" || $origen_csv_ps=="FS" || $origen_csv_ps=="FE" ) && !isset($fila[32])){
            //dd($fila[32]);
      			return back()
      			->with(array("danger" => "Error: Fichero con formato no valido, no se ha podido cargar."))
      			->with('ptime','Fecha y hora de error: '.date('d-m-Y H:i:s'))
      			->with('user','Cargado por: '.Auth::user()->apodo)
      			->with('path',$nombreFichero);
      		}elseif($origen_csv_ps != $fila[32]){
            return back()
      			->with(array("danger" => "Error: Origen erroneo. ERROR: ".$origen_csv."-".$fila[32]))
      			->with('ptime','Fecha y hora de error: '.date('d-m-Y H:i:s'))
      			->with('user','Cargado por: '.Auth::user()->apodo)
      			->with('path',$nombreFichero);
          }




      		// Comprobamos el primer registro del documento,
      		// si existe, nos lo saltamos y lo marcamos como repetido.
      		//echo "<pre>";
      		//print_r($fila);
      		//exit();
          if($origen_csv_ps =="CN"){
            $pedido = new Pedidos_wix_importados;
            $id_shop = $fila[34];
            switch ($id_shop) {
              case 2:
                $origen_csv = "AC";
                break;
              case 3:
                $origen_csv = "LT";
                break;
              case 4:
                $origen_csv = "TT";
                break;
              case 5:
                $origen_csv = "BK";
                break;
              default:
                $origen_csv = "CN";
                break;
            }
            if (Pedidos_wix_importados::where('numero_pedido_ps', '=', $fila[0])->where('sku_producto', '=', $fila[20])->where('o_csv', '=', $origen_csv)->exists()){
      				$repetidos++;
      				continue;
      			}

          } else if($origen_csv=="CA" || $origen_csv=="CB" || $origen_csv=="DW"  || $origen_csv=="FE"){
      			$pedido = new Pedidos_wix_importados;
      			if (Pedidos_wix_importados::where('numero_pedido_ps', '=', $fila[0])->where('nombre_producto', '=', $fila[18])->where('variante_producto', '=', $fila[19])->where('sku_producto', '=', $fila[20])->where('o_csv', '=', $origen_csv)->exists()){
      				$repetidos++;
      				continue;
      			}
      		} else {
            //dd($origen_csv_ps);
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

      		* --  * --  * --  * --  * --  * --  * -- * -- * -- * --*/

          if((($last_ps_id!=$fila[0]) && ($origen_csv=="DW")) || (($last_ps_id!=$fila[0]) && ($origen_csv_ps=="CN")) || (($last_ps_id!=$fila[0]) && ($origen_csv_ps=="FE"))){
            $last_ps_id = $fila[0];
      			$lastID = (Pedidos_wix_importados::where('o_csv', '=', $origen_csv)->max('numero_pedido_real')+1);
          }else if($last_ps_id!=$fila[0]){
      			$last_ps_id = $fila[0];
      			$lastID = (Pedidos_wix_importados::where('o_csv', '=', $origen_csv)->max('numero_pedido')+1);
      		}

          //dd($lastID);

      		// Cargamos los datos del pedido en el objeto y los guardamos en db. ( segun si es wix o prestashop varian los datos )
      		if($origen_csv=="CA" || $origen_csv=="CB" || $origen_csv=="DW" || $origen_csv_ps=="CN" || $origen_csv_ps=="FE" ){ //Según su origen cargamos unos datos u otros.
            $pedido->numero_pedido_real = $lastID;

      			$pedido->numero_pedido_ps = $fila[0]; // Campo para identificar los pedidos importados de prestashop
            if($origen_csv=="DW" || $origen_csv_ps=="CN" || $origen_csv_ps=="FE"){
              $pedido->numero_pedido = str_pad($lastID, 5, "0", STR_PAD_LEFT);
            }else{
              $pedido->numero_pedido = $lastID;
            }
      		} else {
      			$pedido->numero_pedido = $fila[0];
            $pedido->numero_pedido_ps = $fila[0];
      		}
      		$pedido->fecha_pedido = date_format(date_create($fila[1]), 'Y-m-d');
      		$pedido->hora_pedido = $fila[2];
      		/* datos facturación */
      		$pedido->cliente_facturacion = $fila[3];
          if($fila[4]==""){
            $pedido->pais_facturacion = "ES";
          }
          else{
            $pedido->pais_facturacion = $fila[4];
          }
      		$pedido->estado_facturacion = $fila[5];
      		$pedido->ciudad_facturacion = $fila[6];
      		$pedido->direccion_facturacion = $fila[7];
      		$pedido->cp_facturacion = $fila[8];
      		/* datos envio */
      		$pedido->cliente_envio = $fila[9];

          if($fila[10]==""){
            $pedido->pais_envio = "ES";
          }
          else{
            $pedido->pais_envio = $fila[10];
          }
      		$pedido->estado_envio = $fila[11];
      		$pedido->ciudad_envio = $fila[12];
      		$pedido->direccion_envio = $fila[13];
      		$pedido->cp_envio = $fila[14];
      		/* datos contacto */
      		$pedido->telefono_comprador = $fila[15];
      		$pedido->correo_comprador = $fila[16];
      		/* Adicional envío */
          if($origen_csv=="CA"){
            if($fila[28] <= 350 ){
              $pedido->metodo_entrega = 'MRW';
            }else{
              $pedido->metodo_entrega = $fila[17];
            }
          }elseif ($origen_csv=="FS"){
            $pedido->metodo_entrega = 'MRW';
          }elseif($origen_csv=="CN" || ($origen_csv=="AC") ){
            $pedido->metodo_entrega = 'tipsa';
          }elseif(($origen_csv=="TT") || ($origen_csv=="LT") || ($origen_csv=="BK") || ($origen_csv=="FE") ){
            $pedido->metodo_entrega = 'ups';
          }else{
            $pedido->metodo_entrega = $fila[17];
          }
          /*OBSERVACIONES*/
          if(isset($fila[35])){
          $pedido->observaciones = $fila[35];
          }
      		/* detalles producto*/
          if($origen_csv_ps=="CN" && isset($fila[36])){
            $pedido->nombre_producto = $fila[36];
      			$pedido->variante_producto = $fila[18];
          }else{
      		$pedido->nombre_producto = $fila[18];
      		$pedido->variante_producto = $fila[19];
          }
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
          $pedido->bultos = 1;
          /* ean */
          $pedido->ean = $fila[33];

          /*Stock automatico*/
          if(isset($fila[33]) && $fila[33]!=''){
            $where = "1=1 and ean = ".$fila[33];
            $p_comanda = DB::table('productos')->whereRaw($where)->get();
            if(isset($p_comanda[0])){
                $p_comanda = $p_comanda[0];
                $parametros = array("producto" => $p_comanda);//parametros para correo.
                $email_cliente = "developer@decowood.es";//
                $new_stock = $p_comanda->stock - $fila[21];
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
                                      ->whereRaw('ean = "'.$fila[33].'"')
                                      ->update(['stock' => $new_stock,
                                                'esperaStock' => 1]);
                }elseif ($new_stock < $p_comanda->stockControl) {

                  if($p_comanda->esperaStock != 1){
                    //Correo informativo stock de control

                    //Enviar correo comanda

                  }
                  $producto_actualizado = DB::table('productos')
                                        ->whereRaw('ean = "'.$fila[33].'"')
                                        ->update(['stock' => $new_stock,
                                                  'esperaStock' => 1]);
                }else{
                  $producto_actualizado = DB::table('productos')
                                      ->whereRaw('ean = "'.$fila[33].'"')
                                      ->update(['stock' => $new_stock]);
                }
            }



          }


      		// Asignamos como principal el registro
      		// Guardamos NUM_PED Y O_CSV para comparar con el siguiente registro.
      		if($ped_anterior!=($pedido->o_csv.$pedido->numero_pedido)){
      			$pedido->entrada_principal = 1;
      			$ped_anterior = $pedido->o_csv.$pedido->numero_pedido;
      		}
          $pedido->created_at = $created;
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
        if(($_SERVER['HTTP_HOST'] == "admin.decowood.es" ) || ($_SERVER['HTTP_HOST'] == "admin.decowoodservers.cat" )){
          $this->aviso_retraso();
        }
      	return back()
      	->with($mensaje)
      	->with('ptime','Fecha y hora de carga: '.date('d-m-Y H:i:s'))
      	->with('user','Cargado por: '.Auth::user()->apodo)
      	->with('path',$nombreFichero);

    }

    /**
    * Sin uso (obsoleto)
    */
    public function importar_observaciones_post(Request $request){
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
    			if($origen_csv=="CA" || $origen_csv=="CB" || $origen_csv=="CJ" ||  $origen_csv=="TT" || $origen_csv == 'FS'){
    				$fila = fgetcsv($documento_csv,$length=0,$delimiter=';',$enclosure='"');
    			} else {
    				$fila = fgetcsv($documento_csv);
    			}

    			// Para evitar uploads de csv erroneos, comprobamos si en la casilla 32 hay información un campo extra del export de prestashop.
    			if(($origen_csv=="CA" || $origen_csv=="CB" || $origen_csv=="CJ" ||  $origen_csv=="TT" || $origen_csv == 'FS') && !isset($fila[1])){
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
    			if($origen_csv=="CA" || $origen_csv=="CB"){
    				$pedido = Pedidos_wix_importados::where('numero_pedido_ps', '=', $fila[0])
                                              ->where('o_csv', '=', $origen_csv)
                                              ->where('entrada_principal', '=', 1)
                                              ->first();


    			} else {
    				$pedido = Pedidos_wix_importados::where('numero_pedido', '=', $fila[0])
                                              ->where('o_csv', '=', $origen_csv)
                                              ->where('entrada_principal', '=', 1)
                                              ->first();

    			}


    			// Cargamos los datos del pedido en el objeto y los guardamos en db. ( segun si es wix o prestashop varian los datos )
          if($pedido != null){
          $pedido->observaciones = $fila[1];

    			// Asignamos como principal el registro
    			// Guardamos NUM_PED Y O_CSV para comparar con el siguiente registro.

          $pedido->save();
          }






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
        //dd($request);
        /*-------------- ORDENACIONES --------------*/
        $getParams = $request->query();
        if(isset($getParams["ob"],$getParams["obt"])){
        	$orderBy = $getParams["ob"];
        	$orderByType = $getParams["obt"];
        } else {
        	$orderBy = "fecha_pedido";
        	$orderByType = "desc";
        	$orderBy2 = "numero_pedido";
        	$orderByType2 = "desc";
        }

        if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
          $ocsvs = explode(',', $getParams["o_csv"] );
        }else{
          $ocsvs = '';
        }

        /*-------------- FILTROS --------------*/
        $where = "1=1 ";
        if(isset($getParams["id"]) && $getParams["id"]!="") $where .= " and id = ".$getParams["id"]."";
        //if(isset($getParams["o_csv"])&& $getParams["o_csv"]!="") $where .= " and o_csv = '".$getParams["o_csv"]."'";
        if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
          foreach ($ocsvs as $keyocsvs => $origen) {//Bucle para filtrar por mas de un origen
            if($keyocsvs == 0){
              $where .= " and o_csv = '".$origen."'";
              if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
              if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
              if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
              if(isset($getParams["telefono_comprador"])&& $getParams["telefono_comprador"]!="") $where .= " and telefono_comprador like '%".$getParams["telefono_comprador"]."%'";
              if(isset($getParams["nombre_producto"])&& $getParams["nombre_producto"]!="") $where .= " and nombre_producto like '%".$getParams["nombre_producto"]."%'";
              if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
              if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
              if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
              if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
              if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."' and entrada_principal = 1";
            }else{
              $where .= " or o_csv = '".$origen."'";
              if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
              if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
              if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
              if(isset($getParams["telefono_comprador"])&& $getParams["telefono_comprador"]!="") $where .= " and telefono_comprador like '%".$getParams["telefono_comprador"]."%'";
              if(isset($getParams["nombre_producto"])&& $getParams["nombre_producto"]!="") $where .= " and nombre_producto like '%".$getParams["nombre_producto"]."%'";
              if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
              if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
              if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
              if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
              if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."' and entrada_principal = 1";
            }
          }
        }else{
          if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
          if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
          if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
          if(isset($getParams["telefono_comprador"])&& $getParams["telefono_comprador"]!="") $where .= " and telefono_comprador like '%".$getParams["telefono_comprador"]."%'";
          if(isset($getParams["nombre_producto"])&& $getParams["nombre_producto"]!="") $where .= " and nombre_producto like '%".$getParams["nombre_producto"]."%'";
          if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
          if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
          if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
          if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
          if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."' and entrada_principal = 1";

        }



        $pedidos_agrupados = "";

        // Variables de busqueda y retención.
        $last_value = "";
        $keyIndex = "";


        /*-------------- QUERY MySQL -------------- orderBy("numero_pedido", "desc")-> */
        $listado_pedidos = DB::table('pedidos_wix_importados')->whereRaw($where)->orderBy($orderBy,$orderByType)->orderBy($orderBy2,$orderByType)->paginate(100);
        /* Hay un problema al realizar la paginación, en cada página obtiene 100 registros, cada registro es 1 producto.
          Si hay un pedido con más de 1 producto puede quedar dividido en 2 páginas y crear confusión.
          Para solucionar esto deberiamos obtener primero los registros con entrada_principal = 1 y luego por separado los demás, o realizar una subconsulta como
          en la función aviso retraso.
          La solución más correcta sería cambiar la estructura de la base de datos, por ejemplo dividirla en Tabla_pedidos y Tabla_productos_pedidos, y relacionarla.
          Podríamos crear otra Tabla_clientes, pero al no tener un identificador único fiable habría mucho redundancia de datos.
        */
        //dd($listado_pedidos);
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
            $listado_pedidos[$keyIndex]->estado_incidencia .= ",".$value->estado_incidencia;
        		unset($listado_pedidos[$key]);
        		continue;
        	} else {
        		//$norepetidos++;
        		$keyIndex = $key;
        		$last_value = $value->o_csv.$value->numero_pedido;
        	}

        }

        $origen_pedidos = DB::select('select * from `origen_pedidos` ORDER BY `origen_pedidos`.`grupo` ASC');
        //Array de los diferentes origenes
        /*obsoleto*/$o_csv_array = array(
          'CA' => 'Cajasdemadera.com',
          'CB' => 'Cabeceros.com',
          'TT' => 'Tetedelit.fr',
          'FS' => 'Foxandsocks.es',
          'CM' => 'Cajasdemadera.com (manual)',
          'CC' => 'Cabeceros.com (manual)',
          'TL' => 'Latetedelit.fr (manual)',
          'FX' => 'Foxandsocks.es (manual)',
          'MA' => 'Milanuncios',
          'DT' => 'Descontalia',
          'IC' => 'Icommerce',
          'MV' => 'MiVinteriores',
          'HT' => 'Hogarterapia',
          'DA' => 'Decoratualma',
          'CJ' => 'Cojines.es',
          'CO' => 'Cojines.es (Manual)',
          'WL' => 'Wallapop',
          'AR' => 'Areas',
          'AM' => 'Amazon',
          'MM' => 'Mercado de María',
          'CR' => 'Carrefour',
          'MO' => 'Monoqi',
          'WW' => 'Westwing',
          'AS' => 'Otros'
        );
        // debuger
        /*  if(isset($getParams["o_csv"])){
          $debug = implode ( '&' , $ocsvs );
        }else{
          $debug = 'nothing' ;
        }*/
        $debug = '' ;
        return View::make('pedidos/inicio', array('listado_pedidos' => $listado_pedidos, 'filtros' => array($orderBy, $orderByType), 'o_csv_array' => $o_csv_array ,'origen_pedidos' => $origen_pedidos ,'debug' => $debug ));
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
      $seguimiento_pedido = Seguimiento_pedidos::where('numero_pedido','=',$detalles_pedido->numero_pedido)
                                      ->where('origen','=',$detalles_pedido->o_csv)
                                      ->orderBy('ID','DESC')->get();
      $usuarios = User::get();

      //dd($usuarios);
      $user = User::find($detalles_pedido->creador_incidencia);
      if(@$user->apodo!=null){
      	$detalles_pedido->creador_incidencia = $user->apodo;
      } else {
      	$detalles_pedido->creador_incidencia = "null";
      }
      $i_mens = explode(": ",$detalles_pedido->mensaje_incidencia);
      $i_ges = explode(": ",$detalles_pedido->gestion_incidencia);

      if(sizeof($i_mens)<2){
        $incidencia['mensaje'] = '';
      }else{
        $incidencia['mensaje'] = $i_mens[1];
      }
      if(sizeof($i_ges)<2){
        $incidencia['gestion'] = '';
      }else{
        $incidencia['gestion'] = $i_ges[1];
      }



      return View::make('pedidos/detalle', array('detalles_pedido' => $detalles_pedido,
      'productos_pedido' => $productos_pedido,
      'seguimiento' => $seguimiento_pedido,
      'usuarios' => $usuarios,
      'incidencia' => $incidencia));
      }

      public function subir_seguimiento(Request $request){
      $post = $request->all();
      $post = 'hi';

        //$pedido = Pedidos_wix_importados::find($id);

        //subir mensaje:
        //$seguimiento = new Seguimiento_pedidos;




        /*$productos = DB::table('seguimiento_pedidos')
                            ->whereRaw('ean = "'.$producto[0]["codigo_ean"].'"')
                            ->update(['ean' => $producto[0]["codigo_ean"],
                                      'stock' => $producto[0]["stock"],
                                      'stockControl' => $producto[0]["stockControl"],
                                      'pedidoMinim' => $producto[0]["pedidoMinim"]]);*/


      }

      /**
      * Actualiza con nuevos datos la incidencia del pedido especificado.
      * @return $mensaje_resultado
      */
      public function actualizar_detalles_incidencia($id, Request $request){

      $post = $request->all();
      //dd($post);
      try {
      	if(isset($post["mensaje_observacion"])){
      		$pedido = Pedidos_wix_importados::find($id);
      		$pedido->observaciones = $post['mensaje_observacion'];
      		$pedido->save();
      		return "Se ha actualizado correctamente la observación del pedido.";
      	} else if (isset($post["mensaje_incidencia"])){
          //Buscamos el pedido en la base de datos
      		$pedido_p = Pedidos_wix_importados::find($id);
          // Buscamos por producto seleccionado
          foreach ($post['productos_incidencia'] as $keyP => $productos_incidencia) {
            $pedido = Pedidos_wix_importados::find($productos_incidencia);
            $pedido->estado_incidencia = $post['estado_incidencia'];
      			$pedido->mensaje_incidencia = $post['desplegable_mensaje_incidencia'].": ".$post['mensaje_incidencia'];
            $pedido->gestion_incidencia = $post['desplegable_gestion_incidencia'].": ".$post['gestion_incidencia'];
            $pedido->historial_incidencia = $post['historial_incidencia'];
      			if($pedido->creador_incidencia==0){
      			$pedido->creador_incidencia = Auth::user()->id;
            }
            //dd($pedido);
      			$pedido->save();
          }


      		return "Se ha actualizado correctamente el estado de la incidencia.";
      	}else if(isset($post["comentario_seguimiento"])){
          $pedido = Pedidos_wix_importados::find($id);

          DB::table('seguimiento_pedidos')->insert(
            ['origen' => $pedido->o_csv,
            'numero_pedido' => $pedido->numero_pedido,
            'id_usuario' => Auth::user()->id,
            'mensaje' => $post["comentario_seguimiento"],
            'created_at' => date('Y-m-d H:i:s')
          ]);

          //subir mensaje:
          /*$seguimiento = new Seguimiento_pedidos;
          $seguimiento->origen = $pedido->o_csv;
          $seguimiento->numero_pedido = $pedido->numero_pedido;
          $seguimiento->id_usuario = Auth::user()->id;
          $seguimiento->mensaje = $post["comentario_seguimiento"];
          $seguimiento->created_at = date('Y-m-d H:i:s');

          $seguimiento->save();*/

          return "Seguimiento actualizado";
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
        $origen_pedidos = DB::select('select * from `origen_pedidos` where referencia = "'.$pedido->o_csv.'"');
        $transportista = $pedido->metodo_entrega;
        $cliente = $pedido->cliente_facturacion;
        $origen_pedidos = $origen_pedidos[0];
        //dd($origen_pedidos->web);
        // Formato fecha update
        $fecha = new DateTime();
        $fecha = $fecha->format('Y-m-d');

        // Si se desea enviar notificación, realizamos comprobacion.
        if($_GET["notificar"]=="si"){
        	// Mailing
        	//$correo_comercial = Auth::user()->email;
          $correo_comercial = 'support@decowood.es';
        	$titulo = "Hola, ".$cliente." tu pedido ha salido enviado.";
        	$email_cliente = ($pedido->correo_comprador) ? $pedido->correo_comprador : "Cliente";
          //  dd($correo_comercial);
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



        $resultado = array('0' => "", '1' => "", '2' => "");
        try {
          $id_ps = $pedido->numero_pedido_ps;
        	$pedido->fecha_envio = $fecha;
        	$pedido->enviado = 1;
        	$pedido->save();
        	// Llenamos array a retornar.
        	$resultado[0] = $mensaje;
        	$resultado[1] = $fecha;
          if(($origen_pedidos->api_key != null)&&($pedido->numero_pedido_ps != '99999')){
            if($this->actualizar_pedidos_ps($origen_pedidos->referencia,$id_ps)){
              $resultado[2] = "true";
            }
          }
        } catch(Exception $e){
        	$resultado[0] = "Ha habido un error durante la actualización, si el error persiste contacte con developer@decowood.es";
        	$resultado[1] = "";
          $resultado[2] = "false";
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
      $origen_pedidos = DB::select('select * from `origen_pedidos` GROUP BY `grupo` ORDER BY `origen_pedidos`.`grupo` ASC');
		  return View::make('pedidos/nuevo', array('origen_pedidos' => $origen_pedidos ));
    }

	/**
     * Guarda un pedido NUEVO.
     *
     * @return \Illuminate\Http\Response
     */
     public function guardar(Request $request){

    		$mensaje = "";

    		try {
          switch ($request['o_csv']) {
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
            default:
              $origen_csv = $request['o_csv'];
              break;
          }
          $lastID = (Pedidos_wix_importados::where('o_csv', '=', $origen_csv)->where('entrada_principal', '=', 1)->max('ID'));
          $ultimoPedido = (Pedidos_wix_importados::where('ID', '=', $lastID)->max('numero_pedido_real'))+1;

    			foreach($request['nombre_producto'] as $key => $producto){

    				$pedido = new Pedidos_wix_importados;

    				if (Pedidos_wix_importados::where('numero_pedido', '=', $ultimoPedido)->where('nombre_producto', '=', $request['nombre_producto'][$key])->where('variante_producto', '=', $request['variante_producto'][$key])->where('sku_producto', '=', $request['sku_producto'][$key])->where('o_csv', '=', $request['o_csv'])->exists()){
    					$repetidos++;
    					continue;
    				}
            $pedido->numero_pedido_real = $ultimoPedido;
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

            /* observaciones*/
            $pedido->observaciones = $request['observaciones'];

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
    				$pedido->o_csv = $origen_csv;
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
    		$filtersE = json_decode($request->all()["filterse"], true);

    		// Generamos la estructura del XLS.
    		return Excel::create('pedidos_admin', function($excel) use($ids,$filtersE) {
    			$excel->sheet('Sheetname', function($sheet) use($ids,$filtersE) {
    				// headers del documento xls
    				$header = [];
    				$row = 1;

    				// Miramos filtros que añadiremos a la consulta que genera el excel
    				/*
    					Filtros:
    					[0] --> o_csv
    					[1] --> numero_pedido
    					[2] --> fecha_pedido
    					[3] --> fecha_pedido_fin
    					[4] --> cliente_facturacion
    					[5] --> correo_comprador
    					[6] --> direccion_envio
    					[7] --> estado_incidencia
    					[8] --> enviado

    					Estado incidencia:
    					150 --> Sin estado
    					0 --> Cerrada
    					1 --> Abierta

    				*/

    				$estado_incidencia = 150; //Sin estado de incidencia
    				//if($filtersE[7]["value"]!='') $estado_incidencia = $filtersE[7]["value"];

    				// montamos header del documento
    				if($estado_incidencia===150){ //Si es uno normal
    					foreach(Pedidos_wix_importados::find(1)->toArray() as $key => $atributos){
    							array_push($header, $key);
    					}
    				}else{ //Si tiene incidencia
    					$header = ['numero_pedido','fecha_pedido','cliente_facturacion','metodo_entrega','nombre_producto','cantidad_producto','total','mensaje_incidencia'];
    				}

    				// Bucle para rellenar el documento segun el numero de pedidos
    				foreach($ids as $key => $id){

    					// Cargamos datos del pedido
    					$pedido = Pedidos_wix_importados::find($id["value"]);

    					//Si estamos descargando sin filtrar por estado de incidencia no filtraremos
    					if($estado_incidencia===150){
    						$productos_pedido = Pedidos_wix_importados::where('numero_pedido','=',$pedido->numero_pedido)->where('o_csv','=', $pedido->o_csv)->get();
    					}
    					else{
    						//Si queremos mostrar abiertas o cerradas, filtraremos en la consulta
    						$productos_pedido = Pedidos_wix_importados::select('numero_pedido','fecha_pedido','cliente_facturacion','metodo_entrega','nombre_producto','cantidad_producto','total','mensaje_incidencia')->where('numero_pedido','=',$pedido->numero_pedido)
    						->where('estado_incidencia','=',$estado_incidencia)
    						->get();
    					}

    					// Realizamos comprobaciones para cargar pedidos.
              $product_excel = array();
    					if(count($productos_pedido) != 1){
    						foreach($productos_pedido as $key2 => $producto_pedido){
    							$row++;

                  foreach ($producto_pedido->toArray() as $key3 => $pr) {
                    if($key3 == 'estado_incidencia'){
                        switch ($pr) {
                          case 0:
                            $product_excel[$key3] = 'Cerrada';
                            break;

                          case 1:
                            $product_excel[$key3] = 'Abierta';
                            break;

                          case 2:
                            $product_excel[$key3] = 'Resuelta';
                            break;
                          default:
                            $product_excel[$key3] = $pr;
                            break;
                        }
                      }elseif($key3 == 'mensaje_incidencia'){
                        switch ($pr) {
                          case 1:
                            $product_excel[$key3] = 'Rotura en transporte';
                            break;

                          case 2:
                            $product_excel[$key3] = 'Rotura en transporte por mal embalaje';
                            break;

                          case 3:
                            $product_excel[$key3] = 'Error de referencia';
                            break;

                          case 4:
                            $product_excel[$key3] = 'Producto incompleto';
                            break;

                          case 5:
                            $product_excel[$key3] = 'Error de producción';
                            break;

                          case 6:
                            $product_excel[$key3] = 'Fallo de documentación';
                            break;

                          case 7:
                            $product_excel[$key3] = 'Entrega fuera de plazo';
                            break;

                          case 8:
                            $product_excel[$key3] = 'No se ajusta a las necesidades del cliente';
                            break;

                          case 9:
                            $product_excel[$key3] = 'Error de compra';
                            break;

                          default:
                            $product_excel[$key3] = $pr;
                            break;
                          }

                      }elseif($key3 == 'gestion_incidencia'){

                    switch ($pr) {
                      case 1:
                        $product_excel[$key3] = 'Devolución';
                        break;

                      case 2:
                        $product_excel[$key3] = 'Reposición';
                        break;

                      case 3:
                        $product_excel[$key3] = 'Descuento por tara';
                        break;

                      default:
                        $product_excel[$key3] = $pr;
                        break;
                    }

                  }else{
                        $product_excel[$key3] = $pr;
                    }
                  }

                //  dd($product_excel);

                  $sheet->row($row, $product_excel);
    						}
    					} else {

                foreach ($productos_pedido[0]->toArray() as $key3 => $pr) {
                  if($key3 == 'estado_incidencia'){
                      switch ($pr) {
                        case 0:
                          $product_excel[$key3] = 'Cerrada';
                          break;

                        case 1:
                          $product_excel[$key3] = 'Abierta';
                          break;

                        case 2:
                          $product_excel[$key3] = 'Resuelta';
                          break;
                        default:
                          $product_excel[$key3] = $pr;
                          break;
                      }
                    }elseif($key3 == 'mensaje_incidencia'){
                      switch ($pr) {
                        case 1:
                          $product_excel[$key3] = 'Rotura en transporte';
                          break;

                        case 2:
                          $product_excel[$key3] = 'Rotura en transporte por mal embalaje';
                          break;

                        case 3:
                          $product_excel[$key3] = 'Error de referencia';
                          break;

                        case 4:
                          $product_excel[$key3] = 'Producto incompleto';
                          break;

                        case 5:
                          $product_excel[$key3] = 'Error de producción';
                          break;

                        case 6:
                          $product_excel[$key3] = 'Fallo de documentación';
                          break;

                        case 7:
                          $product_excel[$key3] = 'Entrega fuera de plazo';
                          break;

                        case 8:
                          $product_excel[$key3] = 'No se ajusta a las necesidades del cliente';
                          break;

                        case 9:
                          $product_excel[$key3] = 'Error de compra';
                          break;

                        default:
                          $product_excel[$key3] = $pr;
                          break;
                        }

                    }elseif($key3 == 'gestion_incidencia'){

                  switch ($pr) {
                    case 1:
                      $product_excel[$key3] = 'Devolución';
                      break;

                    case 2:
                      $product_excel[$key3] = 'Reposición';
                      break;

                    case 3:
                      $product_excel[$key3] = 'Descuento por tara';
                      break;

                    default:
                      $product_excel[$key3] = $pr;
                      break;
                  }

                }else{
                      $product_excel[$key3] = $pr;
                  }
                }

    						$row++;
    						$sheet->row($row, $product_excel);
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
      foreach ($productos_pedido as $producto) {
        $producto->impreso = 1;
        $producto->save();
      }
      //plox();
      $datos = array('pedido' => $pedido->toArray(),'productos' => $productos_pedido->toArray());

      //dd($pedido->toArray());
      // Inicializamos clase Dompdf
      $nombre_pdf = "pedido_".$pedido->numero_albaran;
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
        foreach ($productos_pedido as $producto) {
          $producto->impreso = 1;
          $producto->save();
        }
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
        /* PEDIDO PRINCIPAL */
    		$pedido->entrada_principal = 1;

        $pedido_secundarios =  Pedidos_wix_importados::where('numero_pedido','=',$pedido->numero_pedido)->where('o_csv', '=', $pedido->o_csv)->get();

    		$atributos_pedido = $pedido->getAttributes();

    		// Comparamos los inputs, y si el atributo existe, asignamos el valor modificado.
    		foreach($inputs as $key => $input){
    			if($key=="_token" || $key=="productos_serializados") continue;
    			if(array_key_exists($key ,$atributos_pedido)){
    				$pedido->$key = $inputs[$key];
    			}
    		}



    		/* Guardamos los detalles del pedido */
    		$pedido->save();

        /* Guardamos los detalles en todos los productos */

        foreach($pedido_secundarios as $key_sec => $pedido_sec){

          foreach($inputs as $key_i => $input2){

      			if($key_i=="_token" || $key_i=="productos_serializados") continue;
      			if(array_key_exists($key_i ,$atributos_pedido)){
      				 $pedido_secundarios[$key_sec]->$key_i = $inputs[$key_i];
      			}
      		}


          $pedido_secundarios[$key_sec]->save();
        }

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
            $nuevo_producto->metodo_entrega = $pedido->metodo_entrega;
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

    		//return redirect('pedidos/detalle/'.$id)->with('mensaje', 'El pedido se ha actualizado correctamente.');
        return redirect('pedidos')->with('mensaje', 'El pedido se ha actualizado correctamente.');
    		//return View::make('pedidos.modificar', array("detalles_pedido" => $pedido, 'productos_pedido' => $productos_pedido));
    }

    public function crear_observacion_bultos($id, Request $request){
      	try {
  			$pedido = Pedidos_wix_importados::find($id);
  			if($request["bultos"]==0){
  				$pedido->bultos = "";
  			} else {
  				/*if($request["bultos"]==1){
  					$num_bultos = " BULTO";
  				} else {
  					$num_bultos = " BULTOS";
  				}*/
  				$pedido->bultos = $request["bultos"];
  			}

  	    	$pedido->save();
      	} catch(Exception $e) {
  			return "No se ha podido actualizar, contactar con el administrador developer@decowood.es";
  		}
      	return "Actualizado.";
    }

	/**
     * Duplica el producto.

     *
     * @return view
     */
    public function duplicar($modo,$numero_pedido,$o_csv){
       $pedidos = Pedidos_wix_importados::where('numero_pedido','=',$numero_pedido)->where('o_csv','=',$o_csv)->get();
       //dd($pedidos);
       //Último id
         if($o_csv == 'CJ'){
           $o_csv = 'CO';
         }
         if($o_csv == 'FS'){
           $o_csv = 'FX';
         }
         if($o_csv == 'TT'){
           $o_csv = 'TL';
         }
         $lastID = (Pedidos_wix_importados::where('o_csv', '=', $o_csv)->max('numero_pedido_real'));
         $lastID = $lastID + 1;

         $id_nuevo = Pedidos_wix_importados::find(DB::table('pedidos_wix_importados')->max('id'));
         $id_nuevo = ($id_nuevo->id+1);

          if($modo == 'reposicion'){
            	$lastID = (Pedidos_wix_importados::where('o_csv', '=', 'RP')->max('numero_pedido_real')+1);
              $numero_pedido_real_rep = $lastID;
              $numero_pedido_rep =  str_pad($lastID, 5, "0", STR_PAD_LEFT);

          }

         //Último numero_pedido


         //return back()->with('mensaje', $numero_pedido);
         /*if($o_csv == 'CJ'){
           $numero_pedido = $id_nuevo;
         }else{
           $numero_pedido = Pedidos_wix_importados::where('o_csv', '=', $o_csv)->max('numero_pedido')+1;
         }*/
         $numero_pedido_real = $lastID;
         $numero_pedido =  str_pad($lastID, 5, "0", STR_PAD_LEFT);

         //Numero_pedido_ps. Pondremos 99999 siempre que sean duplicados para identificarlos.
         $numero_pedido_ps = 99999;
         date_default_timezone_set('Europe/Madrid');

       foreach ($pedidos as $key => $pedido) {
         $pedido_nuevo = new Pedidos_wix_importados;

         $pedido_nuevo->id = $id_nuevo;

         $pedido_nuevo->fecha_pedido = date("Y-m-d");
         $pedido_nuevo->hora_pedido = date("H:i");
         /* datos facturación */
         $pedido_nuevo->cliente_facturacion = $pedido->cliente_facturacion;
         $pedido_nuevo->pais_facturacion = $pedido->pais_facturacion;
         $pedido_nuevo->estado_facturacion = $pedido->estado_facturacion;
         $pedido_nuevo->ciudad_facturacion = $pedido->ciudad_facturacion;
         $pedido_nuevo->direccion_facturacion = $pedido->direccion_facturacion;
         $pedido_nuevo->cp_facturacion = $pedido->cp_facturacion;
         /* datos envio */
         $pedido_nuevo->cliente_envio = $pedido->cliente_envio;
         $pedido_nuevo->pais_envio = $pedido->pais_envio;
         $pedido_nuevo->estado_envio = $pedido->estado_envio;
         $pedido_nuevo->ciudad_envio = $pedido->ciudad_envio;
         $pedido_nuevo->direccion_envio = $pedido->direccion_envio;
         $pedido_nuevo->cp_envio = $pedido->cp_envio;
         /* datos contacto */
         $pedido_nuevo->telefono_comprador = $pedido->telefono_comprador;
         $pedido_nuevo->correo_comprador = $pedido->correo_comprador;
         /* Adicional envío */
         $pedido_nuevo->metodo_entrega = $pedido->metodo_entrega;
         /* detalles producto*/
         $pedido_nuevo->nombre_producto = $pedido->nombre_producto;
         $pedido_nuevo->variante_producto = $pedido->variante_producto;
         $pedido_nuevo->sku_producto = $pedido->sku_producto;
         $pedido_nuevo->cantidad_producto = $pedido->cantidad_producto;
         $pedido_nuevo->precio_producto = $pedido->precio_producto;
         $pedido_nuevo->peso_producto = $pedido->peso_producto;
         $pedido_nuevo->texto_especial_producto = $pedido->texto_especial_producto;
         /* extras compra */
         $pedido_nuevo->cupon = $pedido->cupon;
         $pedido_nuevo->envio = $pedido->envio;
         $pedido_nuevo->tasas = $pedido->tasas;
         $pedido_nuevo->entrada_principal = $pedido->entrada_principal;
         /* Información metodo de pago */

         $pedido_nuevo->pago = 'Paid';
         $pedido_nuevo->bultos = $pedido->bultos;
         $pedido_nuevo->orden_completada = 'fullfilled';
         if($modo == 'reposicion' && $pedido->metodo_entrega == 'MRW'){

           $pedido_nuevo->numero_pedido = $numero_pedido_rep;
           $pedido_nuevo->numero_pedido_ps = $pedido->numero_pedido_ps;
           $pedido_nuevo->numero_pedido_real = $numero_pedido_real_rep;

           $pedido_nuevo->observaciones = 'Entrega y recogida de '.$pedido->bultos.' bulto(s)';
           $pedido_nuevo->forma_de_pago = 'reposicion';

           $pedido_nuevo->total = 0;
           // Mailing
           //$correo_comercial = Auth::user()->email;
           $correo_comercial = 'info@decowood.es';
           //$email_cliente = ($pedido->correo_comprador) ? $pedido->correo_comprador : "Cliente";
           // Parametros para el mailing
           $parametros = array("pedido" => $pedido->toArray());
           $subject_mail = 'Recogida bulto defectuoso '. $pedido->cliente_envio;

           // Se envia mensaje al cliente
           /*Mail::send('mail.informar_reposicion', $parametros, function($message) use($subject_mail)
           {
             $message->from('info@decowood.es', 'DECOWOOD EUROPA SL');
             $message->to('00602@grupomrw.com', 'Proveedor')->subject($subject_mail);
             $message->cc('info@decowood.es', 'Info');
           });*/

         }else if($modo == 'reposicion'){

           $pedido_nuevo->numero_pedido = $numero_pedido_rep;
           $pedido_nuevo->numero_pedido_ps = $pedido->numero_pedido_ps;
           $pedido_nuevo->numero_pedido_real = $numero_pedido_real_rep;

           $pedido_nuevo->total = 0;
           $pedido_nuevo->observaciones = 'Entrega y recogida de '.$pedido->bultos.' bulto(s)';
           $pedido_nuevo->forma_de_pago = 'reposicion';
         }else{
           $pedido_nuevo->forma_de_pago = 'transerenciabancaria';
           $pedido_nuevo->total = $pedido->total;
           $pedido_nuevo->numero_pedido_real = $numero_pedido_real;

           $pedido_nuevo->numero_pedido = $numero_pedido;
           $pedido_nuevo->numero_pedido_ps = $numero_pedido_ps;
         }
         if($pedido->o_csv == 'FS'){
           $pedido_nuevo->o_csv = 'FX';
         }else if($pedido->o_csv == 'CJ'){
           $pedido_nuevo->o_csv = 'CO';
         }else if($pedido->o_csv == 'TT'){
           $pedido_nuevo->o_csv = 'TL';
         }else{
           if($modo == 'reposicion'){
             $pedido_nuevo->o_csv = 'RP';
           }else{
             $pedido_nuevo->o_csv = $pedido->o_csv;
           }
         }
         $pedido_nuevo->enviado = 0;
         $pedido_nuevo->fecha_envio = NULL;
         $pedido_nuevo->save();
         $id_nuevo+=1;
       }
       return back();
    }

    public function obtener_detalles_mes($ano,$mes,Request $request){

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

        if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
          $ocsvs = explode(',', $getParams["o_csv"] );
          }else{
          $ocsvs = '';
        }

    		/*-------------- FILTROS --------------*/
    		$where = "1=1 ";
        if(isset($getParams["id"]) && $getParams["id"]!="") $where .= " and id = ".$getParams["id"]."";
        //if(isset($getParams["o_csv"])&& $getParams["o_csv"]!="") $where .= " and o_csv = '".$getParams["o_csv"]."'";
        if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
          foreach ($ocsvs as $keyocsvs => $origen) {
            if($keyocsvs == 0){
              $where .= " and o_csv = '".$origen."'";
              if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
              if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
              if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
              $where .= " and fecha_pedido >= '".$ano."-".$mes."-"."01"."'";
              $where .= " and fecha_pedido <= '".$ano."-".$mes."-"."31"."'";
              if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
              if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
              if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."'";
            }else{
              $where .= " or o_csv = '".$origen."'";
              if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
              if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
              if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
              $where .= " and fecha_pedido >= '".$ano."-".$mes."-"."01"."'";
              $where .= " and fecha_pedido <= '".$ano."-".$mes."-"."31"."'";
              if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
              if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
              if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."'";

            }
          }
        }else{
          if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
          if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
          if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
          $where .= " and fecha_pedido >= '".$ano."-".$mes."-"."01"."'";
          $where .= " and fecha_pedido <= '".$ano."-".$mes."-"."31"."'";
          if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
          if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
          if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."'";


        }



    		$pedidos_agrupados = "";

    		// Variables de busqueda y retención.
    		$last_value = "";
    		$keyIndex = "";


    		/*-------------- QUERY MySQL -------------- orderBy("numero_pedido", "desc")-> */
    		$listado_pedidos = DB::table('pedidos_wix_importados')->whereRaw($where)->orderBy($orderBy,$orderByType)->orderBy($orderBy2,$orderByType)->paginate(100);
        //$listado_pedidos = DB::table('pedidos_wix_importados')->orderBy($orderBy,$orderByType)->orderBy($orderBy2,$orderByType)->paginate(100);

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

        //Array de los diferentes origenes
        $origen_pedidos = DB::select('select * from `origen_pedidos`');
        $o_csv_array = array(
          'CA' => 'Cajasdemadera.com',
          'CB' => 'Cabeceros.com',
          'TT' => 'Tetedelit.fr',
          'FS' => 'Foxandsocks.es',
          'CM' => 'Cajasdemadera.com (manual)',
          'CC' => 'Cabeceros.com (manual)',
          'TL' => 'Latetedelit.fr (manual)',
          'FX' => 'Foxandsocks.es (manual)',
          'MA' => 'Milanuncios',
          'DT' => 'Descontalia',
          'IC' => 'Icommerce',
          'MV' => 'MiVinteriores',
          'HT' => 'Hogarterapia',
          'DA' => 'Decoratualma',
          'CJ' => 'Cojines.es',
          'CO' => 'Cojines.es (Manual)',
          'WL' => 'Wallapop',
          'AR' => 'Areas',
          'AM' => 'Amazon',
          'MM' => 'Mercado de María',
          'CR' => 'Carrefour',
          'MO' => 'Monoqi',
          'WW' => 'Westwing',
          'AS' => 'Otros'
        );
        // debuger
      /*  if(isset($getParams["o_csv"])){
          $debug = implode ( '&' , $ocsvs );
        }else{
          $debug = 'nothing' ;
        }*/
        $debug = '' ;

        return View::make('pedidos/mes', array('listado_pedidos' => $listado_pedidos, 'filtros' => array($orderBy, $orderByType), 'o_csv_array' => $o_csv_array ,'origen_pedidos' => $origen_pedidos,'debug' => $debug ));

	  }


    public function obtener_detalles_mrw(Request $request){

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

        if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
          $ocsvs = explode(',', $getParams["o_csv"] );
        }else{
          $ocsvs = '';
        }

    		/*-------------- FILTROS --------------*/
    		$where = "1=1 ";
    		if(isset($getParams["id"]) && $getParams["id"]!="") $where .= " and id = ".$getParams["id"]."";
    		//if(isset($getParams["o_csv"])&& $getParams["o_csv"]!="") $where .= " and o_csv = '".$getParams["o_csv"]."'";
        if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
          foreach ($ocsvs as $keyocsvs => $origen) {
            if($keyocsvs == 0){
              $where .= " and o_csv = '".$origen."'";
              if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
              if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
              if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
              if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
              if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
              if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
              if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
              if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."' and entrada_principal = 1";
              $where .= " and metodo_entrega = 'mrw'";
            }else{
              $where .= " or o_csv = '".$origen."'";
              if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
              if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
              if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
              if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
              if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
              if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
              if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
              if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."' and entrada_principal = 1";
              $where .= " and metodo_entrega = 'mrw'";
            }
          }
        }else{
          if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
          if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
          if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
          if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
          if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
          if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
          if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
          if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."' and entrada_principal = 1";
          $where .= " and metodo_entrega = 'mrw'";

        }



    		$pedidos_agrupados = "";

    		// Variables de busqueda y retención.
    		$last_value = "";
    		$keyIndex = "";


    		/*-------------- QUERY MySQL -------------- orderBy("numero_pedido", "desc")-> */
    		$listado_pedidos = DB::table('pedidos_wix_importados')->whereRaw($where)->orderBy($orderBy,$orderByType)->orderBy($orderBy2,$orderByType)->paginate(50);


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
            $listado_pedidos[$keyIndex]->estado_incidencia .= ",".$value->estado_incidencia;
    				unset($listado_pedidos[$key]);
    				continue;
    			} else {
    				//$norepetidos++;
    				$keyIndex = $key;
    				$last_value = $value->o_csv.$value->numero_pedido;
    			}

    		}

        //Array de los diferentes origenes
        $origen_pedidos = DB::select('select * from `origen_pedidos`');
        $o_csv_array = array(
          'CA' => 'Cajasdemadera.com',
          'CB' => 'Cabeceros.com',
          'TT' => 'Tetedelit.fr',
          'FS' => 'Foxandsocks.es',
          'CM' => 'Cajasdemadera.com (manual)',
          'CC' => 'Cabeceros.com (manual)',
          'TL' => 'Latetedelit.fr (manual)',
          'FX' => 'Foxandsocks.es (manual)',
          'MA' => 'Milanuncios',
          'DT' => 'Descontalia',
          'IC' => 'Icommerce',
          'MV' => 'MiVinteriores',
          'HT' => 'Hogarterapia',
          'DA' => 'Decoratualma',
          'CJ' => 'Cojines.es',
          'CO' => 'Cojines.es (Manual)',
          'WL' => 'Wallapop',
          'AR' => 'Areas',
          'AM' => 'Amazon',
          'MM' => 'Mercado de María',
          'CR' => 'Carrefour',
          'MO' => 'Monoqi',
          'WW' => 'Westwing',
          'AS' => 'Otros'
        );
        // debuger
      /*  if(isset($getParams["o_csv"])){
          $debug = implode ( '&' , $ocsvs );
        }else{
          $debug = 'nothing' ;
        }*/
        $debug = '' ;
    		return View::make('pedidos/inicio_mrw', array('listado_pedidos' => $listado_pedidos, 'filtros' => array($orderBy, $orderByType), 'o_csv_array' => $o_csv_array ,'origen_pedidos' => $origen_pedidos,'debug' => $debug ));
  	}

    public function obtener_detalles_tipsa(Request $request){

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

          if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
            $ocsvs = explode(',', $getParams["o_csv"] );
          }else{
            $ocsvs = '';
          }

          /*-------------- FILTROS --------------*/
          $where = "1=1 ";
          if(isset($getParams["id"]) && $getParams["id"]!="") $where .= " and id = ".$getParams["id"]."";
          //if(isset($getParams["o_csv"])&& $getParams["o_csv"]!="") $where .= " and o_csv = '".$getParams["o_csv"]."'";
          if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
            foreach ($ocsvs as $keyocsvs => $origen) {
              if($keyocsvs == 0){
                $where .= " and o_csv = '".$origen."'";
                if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
                if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
                if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
                if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
                if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
                if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
                if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
                if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."' and entrada_principal = 1";
                $where .= " and metodo_entrega = 'tipsa'";
              }else{
                $where .= " or o_csv = '".$origen."'";
                if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
                if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
                if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
                if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
                if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
                if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
                if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
                if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."' and entrada_principal = 1";
                $where .= " and metodo_entrega = 'tipsa'";
              }
            }
          }else{
            if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
            if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
            if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
            if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
            if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
            if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
            if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
            if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."' and entrada_principal = 1";
            $where .= " and metodo_entrega = 'tipsa'";

          }



          $pedidos_agrupados = "";

          // Variables de busqueda y retención.
          $last_value = "";
          $keyIndex = "";


          /*-------------- QUERY MySQL -------------- orderBy("numero_pedido", "desc")-> */
          $listado_pedidos = DB::table('pedidos_wix_importados')->whereRaw($where)->orderBy($orderBy,$orderByType)->orderBy($orderBy2,$orderByType)->paginate(50);


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
              $listado_pedidos[$keyIndex]->estado_incidencia .= ",".$value->estado_incidencia;
              unset($listado_pedidos[$key]);
              continue;
            } else {
              //$norepetidos++;
              $keyIndex = $key;
              $last_value = $value->o_csv.$value->numero_pedido;
            }

          }

          //Array de los diferentes origenes
          $origen_pedidos = DB::select('select * from `origen_pedidos`');
          $o_csv_array = array(
            'CA' => 'Cajasdemadera.com',
            'CB' => 'Cabeceros.com',
            'TT' => 'Tetedelit.fr',
            'FS' => 'Foxandsocks.es',
            'CM' => 'Cajasdemadera.com (manual)',
            'CC' => 'Cabeceros.com (manual)',
            'TL' => 'Latetedelit.fr (manual)',
            'FX' => 'Foxandsocks.es (manual)',
            'MA' => 'Milanuncios',
            'DT' => 'Descontalia',
            'IC' => 'Icommerce',
            'MV' => 'MiVinteriores',
            'HT' => 'Hogarterapia',
            'DA' => 'Decoratualma',
            'CJ' => 'Cojines.es',
            'CO' => 'Cojines.es (Manual)',
            'WL' => 'Wallapop',
            'AR' => 'Areas',
            'AM' => 'Amazon',
            'MM' => 'Mercado de María',
            'CR' => 'Carrefour',
            'MO' => 'Monoqi',
            'WW' => 'Westwing',
            'AS' => 'Otros'
          );
          // debuger
        /*  if(isset($getParams["o_csv"])){
            $debug = implode ( '&' , $ocsvs );
          }else{
            $debug = 'nothing' ;
          }*/
          $debug = '' ;
          return View::make('pedidos/inicio_tipsa', array('listado_pedidos' => $listado_pedidos, 'filtros' => array($orderBy, $orderByType), 'o_csv_array' => $o_csv_array,'origen_pedidos' => $origen_pedidos ,'debug' => $debug ));
    }


    public function obtener_detalles_ups(Request $request){

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

          if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
            $ocsvs = explode(',', $getParams["o_csv"] );
          }else{
            $ocsvs = '';
          }

          /*-------------- FILTROS --------------*/
          $where = "1=1 ";
          if(isset($getParams["id"]) && $getParams["id"]!="") $where .= " and id = ".$getParams["id"]."";
          //if(isset($getParams["o_csv"])&& $getParams["o_csv"]!="") $where .= " and o_csv = '".$getParams["o_csv"]."'";
          if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
            foreach ($ocsvs as $keyocsvs => $origen) {
              if($keyocsvs == 0){
                $where .= " and o_csv = '".$origen."'";
                if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
                if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
                if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
                if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
                if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
                if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
                if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
                if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."' and entrada_principal = 1";
                $where .= " and metodo_entrega = 'ups'";
              }else{
                $where .= " or o_csv = '".$origen."'";
                if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
                if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
                if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
                if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
                if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
                if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
                if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
                if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."' and entrada_principal = 1";
                $where .= " and metodo_entrega = 'ups'";
              }
            }
          }else{
            if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
            if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
            if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
            if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
            if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
            if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
            if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
            if(isset($getParams["enviado"])&& $getParams["enviado"]!="") $where .= " and enviado = '".$getParams["enviado"]."' and entrada_principal = 1";
            $where .= " and metodo_entrega = 'ups'";

          }



          $pedidos_agrupados = "";

          // Variables de busqueda y retención.
          $last_value = "";
          $keyIndex = "";


          /*-------------- QUERY MySQL -------------- orderBy("numero_pedido", "desc")-> */
          $listado_pedidos = DB::table('pedidos_wix_importados')->whereRaw($where)->orderBy($orderBy,$orderByType)->orderBy($orderBy2,$orderByType)->paginate(50);


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
              $listado_pedidos[$keyIndex]->estado_incidencia .= ",".$value->estado_incidencia;
              unset($listado_pedidos[$key]);
              continue;
            } else {
              //$norepetidos++;
              $keyIndex = $key;
              $last_value = $value->o_csv.$value->numero_pedido;
            }

          }

          //Array de los diferentes origenes
          $origen_pedidos = DB::select('select * from `origen_pedidos`');
          $o_csv_array = array(
            'CA' => 'Cajasdemadera.com',
            'CB' => 'Cabeceros.com',
            'TT' => 'Tetedelit.fr',
            'FS' => 'Foxandsocks.es',
            'CM' => 'Cajasdemadera.com (manual)',
            'CC' => 'Cabeceros.com (manual)',
            'TL' => 'Latetedelit.fr (manual)',
            'FX' => 'Foxandsocks.es (manual)',
            'MA' => 'Milanuncios',
            'DT' => 'Descontalia',
            'IC' => 'Icommerce',
            'MV' => 'MiVinteriores',
            'HT' => 'Hogarterapia',
            'DA' => 'Decoratualma',
            'CJ' => 'Cojines.es',
            'CO' => 'Cojines.es (Manual)',
            'WL' => 'Wallapop',
            'AR' => 'Areas',
            'AM' => 'Amazon',
            'MM' => 'Mercado de María',
            'CR' => 'Carrefour',
            'MO' => 'Monoqi',
            'WW' => 'Westwing',
            'AS' => 'Otros'
          );
          // debuger
        /*  if(isset($getParams["o_csv"])){
            $debug = implode ( '&' , $ocsvs );
          }else{
            $debug = 'nothing' ;
          }*/
          $debug = '' ;
          return View::make('pedidos/inicio_ups', array('listado_pedidos' => $listado_pedidos, 'filtros' => array($orderBy, $orderByType), 'o_csv_array' => $o_csv_array,'origen_pedidos' => $origen_pedidos ,'debug' => $debug ));
    }

    public function exportar_detalles_mes($ano,$mes,$ocsv){

      // Rutas de guardado ficheros excel
  		$ruta_doc_xls = "documentos/albaranes/agrupados/";
  		$nombre_xls = "";

  		// Generamos la estructura del XLS.
  		return Excel::create('pedidos_admin', function($excel) use($ano,$mes,$ocsv) {
  			$excel->sheet('Sheetname', function($sheet) use($ano,$mes,$ocsv) {
  				// headers del documento xls
  				$header = [];
  				$row = 1;

  				// Miramos filtros que añadiremos a la consulta que genera el excel
  				/*

  					Estado incidencia:
  					150 --> Sin estado
  					0 --> Cerrada
  					1 --> Abierta

  				*/

  				$estado_incidencia = 150; //Sin estado de incidencia
  				//if($filtersE[7]["value"]!='') $estado_incidencia = $filtersE[7]["value"];

  				// montamos header del documento
  				if($estado_incidencia===150){ //Si es uno normal
  					foreach(Pedidos_wix_importados::find(1)->toArray() as $key => $atributos){
  							array_push($header, $key);
  					}
  				}else{ //Si tiene incidencia
  					$header = ['numero_pedido','fecha_pedido','cliente_facturacion','metodo_entrega','nombre_producto','cantidad_producto','total','mensaje_incidencia'];
  				}
            /*
            $where .= " and fecha_pedido >= '".$ano."-".$mes."-"."01"."'";
            $where .= " and fecha_pedido <= '".$ano."-".$mes."-"."31"."'";
            */
          // Cargamos los pedidos
          $fecha_min = $ano."-".$mes."-"."01";
          $fecha_max = $ano."-".$mes."-"."31";
          //$ocsv = '"'.$ocsv.'"';
          $o_csv = $ocsv;
          $pedidos = Pedidos_wix_importados::where('o_csv', '=' , $o_csv)
                                            ->where('fecha_pedido', '>=', $fecha_min)
                                            ->where('fecha_pedido', '<=', $fecha_max)
                                            ->get();

  				// Bucle para rellenar el documento segun el numero de pedidos
  				foreach($pedidos as $key => $pedido){

  					//Si estamos descargando sin filtrar por estado de incidencia no filtraremos
  					if($estado_incidencia===150){
  						$productos_pedido = Pedidos_wix_importados::where('numero_pedido','=',$pedido->numero_pedido)->where('o_csv','=', $pedido->o_csv)->get();
  					}
  					else{
  						//Si queremos mostrar abiertas o cerradas, filtraremos en la consulta
  						$productos_pedido = Pedidos_wix_importados::select('numero_pedido','fecha_pedido','cliente_facturacion','metodo_entrega','nombre_producto','cantidad_producto','total','mensaje_incidencia')->where('numero_pedido','=',$pedido->numero_pedido)
  						->where('estado_incidencia','=',$estado_incidencia)
  						->get();
  					}

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

    public function csv_mrw($id,$generar_csv){

        $pedido = Pedidos_wix_importados::find($id);
        $peso = array();
        $datos_adicionales = '#SeguimientoSMS=1#';

        $date = getdate();

        $fecha = $date['year'].str_pad($date['mon'], 2, "0", STR_PAD_LEFT).str_pad($date['mday'], 2, "0", STR_PAD_LEFT);
        //dd($pedido);
        $tlf = "";
        $tlf = str_replace("/", "", $pedido->telefono_comprador);
        $tlf = trim($tlf);
        if($pedido->telefono_comprador != ""){
          //$tlf = "34".$tlf;
        }
        $productos = Pedidos_wix_importados::where('numero_pedido', '=', $pedido->numero_pedido)->get();
        //dd($productos);

        foreach ($productos as $key => $producto) {
          //dd($producto->sku_producto);
          $peso_producto = DB::select("select peso from productos where '".$producto->sku_producto."' like skuActual");

          if(isset($peso_producto[0])){
            array_push( $peso ,  $peso_producto[0]);
          }
        }
        $peso_final = 0;
        foreach ($peso as $preu) {
          //dd($preu->peso);
          if($preu->peso>0){
            $peso_final += $preu->peso;
          }
        }
        //dd($peso_final);

        if($peso_final > 5){
          $datos_adicionales += "#TipoServicio=0205#";
        }else{
          $datos_adicionales += "#TipoServicio=0800#";
        }
        if($pedido->pais_envio == ""){
          $pais_fact = "ES";
        }else{
          $pais_fact = $pedido->pais_envio;
        }


        $n_pedido= $pedido->o_csv.$pedido->numero_pedido;
        $empty = "";
        $csv = array('numero_albaran' => $empty,
                      'referencia_envio' => $n_pedido,
                      'referencia_bulto' => $empty,
                      'peso' => $peso_final,
                      'bultos' => $pedido->bultos,
                      'fecha_recogida' => ''.$fecha.'',
                      'observacion' => ''.$pedido->observaciones.'',
                      'nombre_apellido' => ''.$pedido->cliente_envio.'',
                      'direccion' => ''.$pedido->direccion_envio.'',
                      'cp' => ''.trim($pedido->cp_envio).'',
                      'poblacion' => ''.$pedido->ciudad_envio.'',
                      'codigo_pais' => $pais_fact,
                      'telefono' => ''.$tlf.'',
                      'franquicia' => '',
                      'adicionales' => ''.$datos_adicionales.''
                      );
      //  dd($csv);


        if($generar_csv == "TRUE"){
            return Excel::create('mrw_csv_'.$n_pedido , function($excel) use($csv) {
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
        }else{
        //  dd(json_encode($csv));
            return json_encode($csv);

          }



    }

    public function csv_mrw_post($id,Request $request){

        $inputs = $request->all();
        $pedido = Pedidos_wix_importados::find($id);
        // Formato fecha update
    		$fecha = new DateTime();
    		$fecha = $fecha->format('Y-m-d');
        //----------
        $origen_pedidos = DB::select('select * from `origen_pedidos` where referencia = "'.$pedido->o_csv.'"');
        $origen_pedidos = $origen_pedidos[0];
        try {
          if(($origen_pedidos->api_key != null)&&($pedido->numero_pedido_ps != '99999')){
            $this->actualizar_pedidos_ps($origen_pedidos->referencia,$pedido->numero_pedido_ps);
          }
    			$pedido->fecha_envio = $fecha;
    			$pedido->enviado = 1;
    			$pedido->save();
        } catch(Exception $e){

    	  }

        $datos_adicionales = '#SeguimientoSMS=1#';
        //dd($inputs);
        $empty='';
        $n_pedido= $pedido->o_csv.$pedido->numero_pedido;
        if($inputs['kg-mrw'] > 5){
          $datos_adicionales .= "#TipoServicio=0205#";
        }else{
          $datos_adicionales .= "#TipoServicio=0800#";
        }
        $csv = array('numero_albaran' => $empty,
                      'referencia_envio' => $n_pedido,
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
       //dd($csv);

            return Excel::create('mrw_csv_'.$n_pedido , function($excel) use($csv) {
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

          $pedido = Pedidos_wix_importados::find($id);
          $peso = array();
          $datos_adicionales = '#SeguimientoSMS=1#';

          $date = getdate();

          $fecha = $date['year'].str_pad($date['mon'], 2, "0", STR_PAD_LEFT).str_pad($date['mday'], 2, "0", STR_PAD_LEFT);
          //dd($pedido);
          $tlf = "";
          $tlf = str_replace("/", "", $pedido->telefono_comprador);
          $tlf = trim($tlf);
          if($pedido->telefono_comprador != ""){
            //$tlf = "34".$tlf;
          }
          $productos = Pedidos_wix_importados::where('numero_pedido', '=', $pedido->numero_pedido)->get();
          //dd($productos);

          foreach ($productos as $key => $producto) {
            //dd($producto->sku_producto);
            $peso_producto = DB::select("select peso from productos where '".$producto->sku_producto."' like skuActual");

            if(isset($peso_producto[0])){
              array_push( $peso ,  $peso_producto[0]);
            }
          }
          $peso_final = 0;
          foreach ($peso as $preu) {
            //dd($preu->peso);
            if($preu->peso>0){
              $peso_final += $preu->peso;
            }
          }
          //dd($peso_final);

          if($peso_final > 5){
            $datos_adicionales += "#TipoServicio=0205#";
          }else{
            $datos_adicionales += "#TipoServicio=0800#";
          }
          if($pedido->pais_envio == ""){
            $pais_fact = "ES";
          }else{
            $pais_fact = $pedido->pais_envio;
          }


          $n_pedido= $pedido->o_csv.$pedido->numero_pedido;
          $empty = "";
          $csv = array('numero_albaran' => $empty,
                        'referencia_envio' => $n_pedido,
                        'referencia_bulto' => $empty,
                        'peso' => $peso_final,
                        'bultos' => $pedido->bultos,
                        'fecha_recogida' => ''.$fecha.'',
                        'observacion' => ''.$pedido->observaciones.'',
                        'nombre_apellido' => ''.$pedido->cliente_envio.'',
                        'direccion' => ''.$pedido->direccion_envio.'',
                        'cp' => ''.trim($pedido->cp_envio).'',
                        'poblacion' => ''.$pedido->ciudad_envio.'',
                        'codigo_pais' => $pais_fact,
                        'telefono' => ''.$tlf.'',
                        'franquicia' => '',
                        'adicionales' => ''.$datos_adicionales.''
                        );
        //  dd($csv);


          if($generar_csv == "TRUE"){
              return Excel::create('tipsa_csv_'.$n_pedido , function($excel) use($csv) {
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
          }else{
          //  dd(json_encode($csv));
              return json_encode($csv);

            }



    }

    public function csv_tipsa_post($id,Request $request){

          $inputs = $request->all();
          $pedido = Pedidos_wix_importados::find($id);
          // Formato fecha update
      		$fecha = new DateTime();
      		$fecha = $fecha->format('Y-m-d');
          //----------
          $origen_pedidos = DB::select('select * from `origen_pedidos` where referencia = "'.$pedido->o_csv.'"');
          $origen_pedidos = $origen_pedidos[0];
          try {

            if(($origen_pedidos->api_key != null)&&($pedido->numero_pedido_ps != '99999')){
              $this->actualizar_pedidos_ps($origen_pedidos->referencia,$pedido->numero_pedido_ps);
            }

            $pedido->fecha_envio = $fecha;
      			$pedido->enviado = 1;
      			$pedido->save();



          } catch(Exception $e){

      	  }

          $datos_adicionales = '#SeguimientoSMS=1#';
          //dd($inputs);
          $empty='';
          $n_pedido= $pedido->o_csv.$pedido->numero_pedido;
          if($inputs['kg-mrw'] > 5){
            $datos_adicionales .= "#TipoServicio=0205#";
          }else{
            $datos_adicionales .= "#TipoServicio=0800#";
          }
          $csv = array( 'inicio' => "",
                        'referencia_envio' => $n_pedido,
                        'bultos' => $inputs['bultos-mrw'],
                        'observacion' => ''.$pedido->observaciones.'',
                        'nombre_apellido' => $inputs['nombre-mrw'],
                        'direccion' => $inputs['direccion-mrw'],
                        'cp' => trim($inputs['cp-mrw']),
                        'poblacion' => $inputs['ciudad-mrw'],
                        'codigo_pais' => $inputs['pais-mrw'],
                        'telefono' => $inputs['telefono-mrw'],
                        'correo' => $pedido->correo_comprador,
                        'fin' => ""
                        );
         //dd($csv);

              return Excel::create('tipsa_csv_'.$n_pedido , function($excel) use($csv) {
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


    /**
       * Cargamos la vista con los pedidos,
  	 * Se aplicaran los filtros que se hayan especificado.
       *
       * @return view
       */
    public function no_enviados(Request $request){

    		/*-------------- ORDENACIONES --------------*/
    		$getParams = $request->query();
    		if(isset($getParams["ob"],$getParams["obt"])){
    			$orderBy = $getParams["ob"];
    			$orderByType = $getParams["obt"];
    		} else {
    			$orderBy = "created_at";
    			$orderByType = "asc";
    			$orderBy2 = "numero_pedido";
    			$orderByType2 = "desc";
    		}

        if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
          $ocsvs = explode(',', $getParams["o_csv"] );
        }else{
          $ocsvs = '';
        }

    		/*-------------- FILTROS --------------*/
    		$where = "1=1 ";
    		if(isset($getParams["id"]) && $getParams["id"]!="") $where .= " and id = ".$getParams["id"]."";
    		//if(isset($getParams["o_csv"])&& $getParams["o_csv"]!="") $where .= " and o_csv = '".$getParams["o_csv"]."'";
        if(isset($getParams["o_csv"])&& $getParams["o_csv"]!=""){
          foreach ($ocsvs as $keyocsvs => $origen) {
            if($keyocsvs == 0){
              $where .= " and o_csv = '".$origen."'";
              if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
              if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
              if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
              if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
              if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
              if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
              if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
              $where .= " and enviado != '1' and entrada_principal = 1";
            }else{
              $where .= " or o_csv = '".$origen."'";
              if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
              if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
              if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
              if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
              if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
              if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
              if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
              $where .= " and enviado != '1' and entrada_principal = 1";              }
          }
        }else{
          if(isset($getParams["numero_pedido"])&& $getParams["numero_pedido"]!="") $where .= " and numero_pedido = ".$getParams["numero_pedido"]."";
          if(isset($getParams["cliente_facturacion"])&& $getParams["cliente_facturacion"]!="") $where .= " and cliente_facturacion like '%".$getParams["cliente_facturacion"]."%'";
          if(isset($getParams["correo_comprador"])&& $getParams["correo_comprador"]!="") $where .= " and correo_comprador like '%".$getParams["correo_comprador"]."%'";
          if(isset($getParams["fecha_pedido"])&& $getParams["fecha_pedido"]!="") $where .= " and fecha_pedido >= '".$getParams["fecha_pedido"]."'";
          if(isset($getParams["fecha_pedido_fin"])&& $getParams["fecha_pedido_fin"]!="") $where .= " and fecha_pedido <= '".$getParams["fecha_pedido_fin"]."'";
          if(isset($getParams["estado_incidencia"])&& $getParams["estado_incidencia"]!="") $where .= " and estado_incidencia = '".$getParams["estado_incidencia"]."'";
          if(isset($getParams["direccion_envio"])&& $getParams["direccion_envio"]!="") $where .= " and direccion_envio like '%".$getParams["direccion_envio"]."%'";
          $where .= " and enviado != '1' and entrada_principal = 1";
        }



    		$pedidos_agrupados = "";

    		// Variables de busqueda y retención.
    		$last_value = "";
    		$keyIndex = "";


    		/*-------------- QUERY MySQL -------------- orderBy("numero_pedido", "desc")-> */
    		$listado_pedidos = DB::table('pedidos_wix_importados')->whereRaw($where)->orderBy($orderBy,$orderByType)->orderBy($orderBy2,$orderByType)->paginate(50);

        //dd($listado_pedidos);
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
            $listado_pedidos[$keyIndex]->estado_incidencia .= ",".$value->estado_incidencia;
    				unset($listado_pedidos[$key]);
    				continue;
    			} else {
    				//$norepetidos++;
    				$keyIndex = $key;
    				$last_value = $value->o_csv.$value->numero_pedido;
    			}

    		}

        $origen_pedidos = DB::select('select * from `origen_pedidos`');
        //Array de los diferentes origenes
        $o_csv_array = array(
          'CA' => 'Cajasdemadera.com',
          'CB' => 'Cabeceros.com',
          'TT' => 'Tetedelit.fr',
          'FS' => 'Foxandsocks.es',
          'CM' => 'Cajasdemadera.com (manual)',
          'CC' => 'Cabeceros.com (manual)',
          'TL' => 'Latetedelit.fr (manual)',
          'FX' => 'Foxandsocks.es (manual)',
          'MA' => 'Milanuncios',
          'DT' => 'Descontalia',
          'IC' => 'Icommerce',
          'MV' => 'MiVinteriores',
          'HT' => 'Hogarterapia',
          'DA' => 'Decoratualma',
          'CJ' => 'Cojines.es',
          'CO' => 'Cojines.es (Manual)',
          'WL' => 'Wallapop',
          'AR' => 'Areas',
          'AM' => 'Amazon',
          'MM' => 'Mercado de María',
          'CR' => 'Carrefour',
          'MO' => 'Monoqi',
          'WW' => 'Westwing',
          'AS' => 'Otros'
        );
        // debuger
      /*  if(isset($getParams["o_csv"])){
          $debug = implode ( '&' , $ocsvs );
        }else{
          $debug = 'nothing' ;
        }*/
        $debug = '' ;
    		return View::make('pedidos/inicio', array('listado_pedidos' => $listado_pedidos, 'filtros' => array($orderBy, $orderByType), 'o_csv_array' => $o_csv_array ,'origen_pedidos' => $origen_pedidos ,'debug' => $debug ));
  	}

    /**
    * Función para actualizar el estado del producto en las webs de
    * Prestashop mediantes webservice con ayuda de la clase app/PsController.php
    * QUEDA PENDIENTE UN CONTROLADOR DE ERRORES
    */
    public function actualizar_pedidos_ps($origen,$id_order)
    {

      /* Obtenemos los datos de la tabla origen */
      $origen_pedidos = DB::select('select * from `origen_pedidos` where referencia = "'.$origen.'"');
      $origen_pedidos = $origen_pedidos[0];
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

    /**
    * Actualización y envío de correo de los nuevos pedidos con retraso.
    */
    public function aviso_retraso(){
        $correo_comercial = 'info@decowood.es';
        $correo_2 = 'sandra@decowood.es';
        $correo_3 = 'f.jimenez@decowood.es';
        $correo_4 = 'talavera@decowood.es';
        $correo_5 = 'logistica@decowood.es';
        $correo_developer = 'developer@decowood.es';

        $n_enviado = 0;
        $dias = 0;

        //Obtención pedidos con retraso de 30 dias sin avisar:
        $n_enviado = 3;
        $dias = 30;
        $query = "SELECT pd.id,pd.o_csv,pd.numero_pedido,pd.numero_pedido_ps,pd.fecha_pedido,pd.cliente_envio,".
        "(SELECT GROUP_CONCAT(pd2.nombre_producto SEPARATOR ' || ') ".
        "FROM pedidos_wix_importados as pd2 where pd.numero_pedido = pd2.numero_pedido and pd.o_csv = pd2.o_csv ".
        "GROUP BY pd2.numero_pedido,pd2.o_csv)  as productos ".
        "FROM pedidos_wix_importados as pd ".
        "WHERE entrada_principal = 1 and pd.enviado = ".$n_enviado." and (DATEDIFF(now(),pd.fecha_pedido)) > ".$dias." ".
        "GROUP BY numero_pedido,o_csv ORDER BY id ASC";
        $pedidos =  DB::select($query);


        if(sizeof($pedidos)>0){
          // Mailing
          //$correo_comercial = Auth::user()->email;

          //  dd($correo_comercial);
          // Parametros para el mailing
          $parametros = array("pedidos" => $pedidos);//parametros para correo.
          // Se envia mensaje al cliente
          Mail::send('mail.informar_retraso', $parametros, function($message) use($correo_comercial,$correo_2,$correo_3,$correo_4,$correo_5,$correo_developer)
          {
            $message->from('info@decowood.es', 'Decowood ADMIN');
            $message->to($correo_comercial, 'Información')->subject('ATENCIÓN: Pedidos con 30 días de retraso');
            $message->cc($correo_2, 'Sandra');
            $message->cc($correo_3, 'Fernando');
            $message->cc($correo_4, 'Josep Talavera');
            $message->cc($correo_5, 'Herminio');
            $message->bcc($correo_developer, 'Developer');
          });
        }

        foreach ($pedidos as $key => $pedido) {
          $pedido->enviado = 4;
          DB::table('pedidos_wix_importados')
                              ->whereRaw('id = '.$pedido->id )
                              ->update(['enviado' => $pedido->enviado]);
        }

        //Obtención pedidos con retraso de 20 dias sin avisar:
        $n_enviado = 2;
        $dias = 19;
        $query = "SELECT pd.id,pd.o_csv,pd.numero_pedido,pd.numero_pedido_ps,pd.fecha_pedido,pd.cliente_envio,".
        "(SELECT GROUP_CONCAT(pd2.nombre_producto SEPARATOR ' || ') ".
        "FROM pedidos_wix_importados as pd2 where pd.numero_pedido = pd2.numero_pedido and pd.o_csv = pd2.o_csv ".
        "GROUP BY pd2.numero_pedido,pd2.o_csv)  as productos ".
        "FROM pedidos_wix_importados as pd ".
        "WHERE entrada_principal = 1 and pd.enviado = ".$n_enviado." and (DATEDIFF(now(),pd.fecha_pedido)) > ".$dias." ".
        "GROUP BY numero_pedido,o_csv ORDER BY id ASC";
        $pedidos =  DB::select($query);



        if(sizeof($pedidos)>0){
          // Mailing
          //$correo_comercial = Auth::user()->email;

          //  dd($correo_comercial);
          // Parametros para el mailing
          $parametros = array("pedidos" => $pedidos);//parametros para correo.
          // Se envia mensaje al cliente
          Mail::send('mail.informar_retraso', $parametros, function($message) use($correo_comercial,$correo_2,$correo_3,$correo_4,$correo_5,$correo_developer)
          {
            $message->from('info@decowood.es', 'Decowood ADMIN');
            $message->to($correo_comercial, 'Información')->subject('ATENCIÓN: Pedidos con 20 días de retraso');
            $message->cc($correo_2, 'Sandra');
            $message->cc($correo_3, 'Fernando');
            $message->cc($correo_4, 'Josep Talavera');
            $message->cc($correo_5, 'Herminio');
            $message->bcc($correo_developer, 'Developer');
          });
        }

        foreach ($pedidos as $key => $pedido) {
          $pedido->enviado = 3;
          DB::table('pedidos_wix_importados')
                              ->whereRaw('id = '.$pedido->id )
                              ->update(['enviado' => $pedido->enviado]);
        }

        //Obtención pedidos con retraso de 15 dias sin avisar:
        $n_enviado = 0;
        $dias = 9;
        $query = "SELECT pd.id,pd.o_csv,pd.numero_pedido,pd.numero_pedido_ps,pd.fecha_pedido,pd.cliente_envio,".
        "(SELECT GROUP_CONCAT(pd2.nombre_producto SEPARATOR ' || ') ".
        "FROM pedidos_wix_importados as pd2 where pd.numero_pedido = pd2.numero_pedido and pd.o_csv = pd2.o_csv ".
        "GROUP BY pd2.numero_pedido,pd2.o_csv)  as productos ".
        "FROM pedidos_wix_importados as pd ".
        "WHERE entrada_principal = 1 and pd.enviado = ".$n_enviado." and (DATEDIFF(now(),pd.fecha_pedido)) > ".$dias." ".
        "GROUP BY numero_pedido,o_csv ORDER BY id ASC";
        $pedidos =  DB::select($query);


        if(sizeof($pedidos)>0){
          // Mailing
          //$correo_comercial = Auth::user()->email;

          //  dd($correo_comercial);
          // Parametros para el mailing
          $parametros = array("pedidos" => $pedidos);//parametros para correo.
          // Se envia mensaje al cliente
          Mail::send('mail.informar_retraso', $parametros, function($message) use($correo_comercial,$correo_2,$correo_3,$correo_4,$correo_5,$correo_developer)
          {
            $message->from('info@decowood.es', 'Decowood ADMIN');
            $message->to($correo_comercial, 'Información')->subject('ATENCIÓN: Pedidos con 10 días de retraso');
            $message->cc($correo_2, 'Sandra');
            $message->cc($correo_3, 'Fernando');
            $message->cc($correo_4, 'Josep Talavera');
            $message->cc($correo_5, 'Herminio');
            $message->bcc($correo_developer, 'Developer');
          });
        }

        foreach ($pedidos as $key => $pedido) {
          $pedido->enviado = 2;
          DB::table('pedidos_wix_importados')
                              ->whereRaw('id = '.$pedido->id )
                              ->update(['enviado' => $pedido->enviado]);
        }

        return 'true';
    }

    /**
    * Función para enviar correos de todos los pedidos con retraso
    */
    public function forzar_aviso(){
      $correo_comercial = 'info@decowood.es';
      $correo_2 = 'sandra@decowood.es';
      $correo_3 = 'f.jimenez@decowood.es';
      $correo_4 = 'talavera@decowood.es';
      $correo_developer = 'developer@decowood.es';

      $n_enviado = 0;
      $dias = 0;

      //Obtención pedidos con retraso de 30 dias sin avisar:
      $n_enviado = 4;
      $query = "SELECT pd.id,pd.o_csv,pd.numero_pedido,pd.numero_pedido_ps,pd.fecha_pedido,pd.cliente_envio,".
      "(SELECT GROUP_CONCAT(pd2.nombre_producto SEPARATOR ' || ') ".
      "FROM pedidos_wix_importados as pd2 where pd.numero_pedido = pd2.numero_pedido and pd.o_csv = pd2.o_csv ".
      "GROUP BY pd2.numero_pedido,pd2.o_csv)  as productos ".
      "FROM pedidos_wix_importados as pd ".
      "WHERE entrada_principal = 1 and pd.enviado = ".$n_enviado.
      " GROUP BY numero_pedido,o_csv ORDER BY id ASC";
      $pedidos =  DB::select($query);


      if(sizeof($pedidos)>0){
        // Mailing
        //$correo_comercial = Auth::user()->email;

        //  dd($correo_comercial);
        // Parametros para el mailing
        $parametros = array("pedidos" => $pedidos);//parametros para correo.
        // Se envia mensaje al cliente
        Mail::send('mail.informar_retraso', $parametros, function($message) use($correo_comercial,$correo_2,$correo_3,$correo_4,$correo_developer)
        {
          $message->from('info@decowood.es', 'Decowood ADMIN');
          $message->to($correo_comercial, 'Información')->subject('ATENCIÓN: Pedidos con 30 días de retraso');
          $message->cc($correo_2, 'Sandra');
          $message->cc($correo_3, 'Fernando');
          $message->cc($correo_4, 'Josep Talavera');
          $message->bcc($correo_developer, 'Developer');
        });
      }

      //Obtención pedidos con retraso de 20 dias sin avisar:
      $n_enviado = 3;
      $dias = 19;
      $query = "SELECT pd.id,pd.o_csv,pd.numero_pedido,pd.numero_pedido_ps,pd.fecha_pedido,pd.cliente_envio,".
      "(SELECT GROUP_CONCAT(pd2.nombre_producto SEPARATOR ' || ') ".
      "FROM pedidos_wix_importados as pd2 where pd.numero_pedido = pd2.numero_pedido and pd.o_csv = pd2.o_csv ".
      "GROUP BY pd2.numero_pedido,pd2.o_csv)  as productos ".
      "FROM pedidos_wix_importados as pd ".
      "WHERE entrada_principal = 1 and pd.enviado = ".$n_enviado." ".
      "GROUP BY numero_pedido,o_csv ORDER BY id ASC";
      $pedidos =  DB::select($query);



      if(sizeof($pedidos)>0){
        // Mailing
        //$correo_comercial = Auth::user()->email;

        //  dd($correo_comercial);
        // Parametros para el mailing
        $parametros = array("pedidos" => $pedidos);//parametros para correo.
        // Se envia mensaje al cliente
        Mail::send('mail.informar_retraso', $parametros, function($message) use($correo_comercial,$correo_2,$correo_3,$correo_4,$correo_developer)
        {
          $message->from('info@decowood.es', 'Decowood ADMIN');
          $message->to($correo_comercial, 'Información')->subject('ATENCIÓN: Pedidos con 20 días de retraso');
          $message->cc($correo_2, 'Sandra');
          $message->cc($correo_3, 'Fernando');
          $message->cc($correo_4, 'Josep Talavera');
          $message->bcc($correo_developer, 'Developer');
        });
      }

      //Obtención pedidos con retraso de 15 dias sin avisar:
      $n_enviado = 2;
      $dias = 9;
      $query = "SELECT pd.id,pd.o_csv,pd.numero_pedido,pd.numero_pedido_ps,pd.fecha_pedido,pd.cliente_envio,".
      "(SELECT GROUP_CONCAT(pd2.nombre_producto SEPARATOR ' || ') ".
      "FROM pedidos_wix_importados as pd2 where pd.numero_pedido = pd2.numero_pedido and pd.o_csv = pd2.o_csv ".
      "GROUP BY pd2.numero_pedido,pd2.o_csv)  as productos ".
      "FROM pedidos_wix_importados as pd ".
      "WHERE entrada_principal = 1 and pd.enviado = ".$n_enviado." ".
      "GROUP BY numero_pedido,o_csv ORDER BY id ASC";
      $pedidos =  DB::select($query);


      if(sizeof($pedidos)>0){
        // Mailing
        //$correo_comercial = Auth::user()->email;

        //  dd($correo_comercial);
        // Parametros para el mailing
        $parametros = array("pedidos" => $pedidos);//parametros para correo.
        // Se envia mensaje al cliente
        Mail::send('mail.informar_retraso', $parametros, function($message) use($correo_comercial,$correo_2,$correo_3,$correo_4,$correo_developer)
        {
          $message->from('info@decowood.es', 'Decowood ADMIN');
          $message->to($correo_comercial, 'Información')->subject('ATENCIÓN: Pedidos con 10 días de retraso');
          $message->cc($correo_2, 'Sandra');
          $message->cc($correo_3, 'Fernando');
          $message->cc($correo_4, 'Josep Talavera');
          $message->bcc($correo_developer, 'Developer');
        });
      }

      return 'true';
    }

    public function view_dups(){
      $listado_pedidos = Pedidos_wix_importados::where('texto_especial_producto', '=', 'DUPS')->paginate(100);

      return View::make('pedidos/dups', array('listado_pedidos' => $listado_pedidos));

    }


    public function aviso_dups(){
      $a_nompdf = array();
      $a_apdf = array();
      $pedidos = Pedidos_wix_importados::where('texto_especial_producto', '=', 'DUPS')->get();

      foreach ($pedidos as $pedido) {
        $nombre_pdf = "pedido_".$pedido->o_csv.$pedido->numero_pedido.".pdf";
        if(!in_array($nombre_pdf,$a_nompdf)){
          $productos_pedido = Pedidos_wix_importados::where('numero_pedido','=',$pedido->numero_pedido)->where('o_csv','=',$pedido->o_csv)->where('texto_especial_producto','=','DUPS')->get();
          $datos = array('pedido' => $pedido->toArray(),'productos' => $productos_pedido->toArray());

          //dd($pedido->toArray());
          // Inicializamos clase Dompdf

          $view = View::make('pedidos.albaran', $datos)->render();

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
      }
      if(($_SERVER['HTTP_HOST'] == "admin.decowood.es" ) || ($_SERVER['HTTP_HOST'] == "admin.decowoodservers.cat" )){
        if(count($a_apdf) > 0){
          $parametros = array("pedidos" => $pedidos);
          Mail::send('mail.dups', $parametros, function($message) use($a_apdf, $a_nompdf)
          {
            $message->from('info@decowood.es', 'Info ');
            $message->to('digital@dups.net', 'Información')->subject('Nuevos pedidos Decowood '.date("d-m-Y"));
            $message->cc('sandra@decowood.es', 'sandra');
            $message->cc('info@decowood.es', 'info');
            $message->bcc('developer@decowood.es', 'Developer');
            foreach ($a_apdf as $key => $output) {
              $message->attachData($output, $a_nompdf[$key]);
            }
          });
        }
      }
       Pedidos_wix_importados::where('texto_especial_producto', '=', 'DUPS')
                              ->update(['texto_especial_producto' => 'DUPS: Enviado']);

      return back();
    }

    public function excel_mails(){
       // Rutas de guardado ficheros excel
       $ruta_doc_xls = "documentos/albaranes/agrupados/";
       $nombre_xls = "";

       // Request con ids para rellenar excel
       $mails = Pedidos_wix_importados::select('correo_comprador')->where('correo_comprador','NOT LIKE','%amazon%')->groupBy('correo_comprador')->get();

       // Generamos la estructura del XLS.
       return Excel::create('pedidos_admin', function($excel) use($mails) {
         $excel->sheet('Sheetname', function($sheet) use($mails) {
           // headers del documento xls
           $header = ['correo'];
           $row = 1;

           foreach($mails as $mail){
             $product_excel = array();
             //dd($mail->correo_comprador);
             $row++;
             array_push($product_excel,$mail->correo_comprador );
             $sheet->row($row, $product_excel);
           }




           //print_r($header);
           //kaboom();

           $sheet->fromArray($header, null, 'A1', true);

        });
      })->export('xls');
    }


}
