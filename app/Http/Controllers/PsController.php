<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use View;
use Illuminate\Support\Facades\DB;
use App\Productos_amazon;
use App\Pedidos_wix_importados;
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
use \Milon\Barcode\DNS1D;
use Session;
use PHPExcel_Worksheet_Drawing;



class PsController extends Controller
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
    }

    public function actualizar_pedidos(Request $request)
    {

      if((isset($request['o_csv'])) && (isset($request['id_ps_order'])) ){

        $origen = $request['o_csv'];
        $id_order = $request['id_ps_order'];

      }else{

        return  View::make('development/error');

      }

      $origen_pedidos = DB::select('select * from `origen_pedidos` where referencia = "'.$origen.'"');
      $origen_pedidos = $origen_pedidos[0];
      define('DEBUG', false);
      define('PS_SHOP_PATH', $origen_pedidos->web );
      define('PS_WS_AUTH_KEY', $origen_pedidos->api_key);
      //require_once('PSWebServiceLibrary/PrestaShopWebservice.php');
      // Obtencion del pedido
      try
      {

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

      return View::make('development/psapi', array('resources' => $resources ));

    }





}
