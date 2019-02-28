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

class HomeController extends Controller
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
    public function index()
    {
        return view('inicio');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function administracion()
    {
		$ganancias = Pedidos_wix_importados::where("entrada_principal","=",1)->sum('total');
		$clientes = DB::table('pedidos_wix_importados')->distinct('correo_comprador')->where("entrada_principal","=",1)->count();
		/*$entregados =  DB::select("SELECT (
								concat(
								round(
									(( SELECT COUNT( b.id )  FROM pedidos_wix_importados AS b where b.enviado != 1)/
									( SELECT COUNT( A.id ) FROM pedidos_wix_importados AS A where A.enviado = 1)*100)
									,0),'%')
								) as porcentaje
								FROM pedidos_wix_importados
								group by porcentaje");*/
        $entregados_enviados =  DB::select("select count(*) as count from pedidos_wix_importados where entrada_principal = 1 and enviado=1");
        $entregados_enviados = $entregados_enviados[0];
        $entregados_total_pedidos =  DB::select("select count(*) as count from pedidos_wix_importados where entrada_principal = 1");
        $entregados_total_pedidos = $entregados_total_pedidos[0];

        $entregados = (($entregados_enviados->count * 100)/$entregados_total_pedidos->count);

		$total_pedidos =  DB::select("SELECT count(id) as id FROM pedidos_wix_importados where entrada_principal = 1");
        return view::make('administracion/administracion', array("ganancias" => $ganancias, "clientes" => $clientes, "pedidos_entregados" => round($entregados,"2"), "total_pedidos" => $total_pedidos[0]->id));
    }
	
	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function administrar_clientes()
    {
        return view('administrar_clientes');
    }

    /**
     * Barcode
     *
     * @return view
     */
    public function barcode()
    {
        return view('barcode');
    }
	
}
