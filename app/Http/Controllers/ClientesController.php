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

class ClientesController extends Controller
{
    /**
    * Constructor y middleware
    * @return void(true/false auth)
    */
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function adaptar(){
      
    }

}
