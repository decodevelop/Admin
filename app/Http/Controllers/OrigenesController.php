<?php

namespace App\Http\Controllers ;

use Illuminate\Http\Request ;
use Auth ;
use View ;
use Illuminate\Support\Facades\DB ;
use App\Origen_pedidos ;
use App\User ;
Use Validator ;
use Input ;
use DateTime ;
use Mail ;
use App ;
use PDF ;
use Dompdf\Dompdf ;
use Excel ;
use \Milon\Barcode\DNS1D ;
use Session ;
use PHPExcel_Worksheet_Drawing ;

class OrigenesController extends Controller
{
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware( 'auth' ) ;
  }

  /**
  * Show the application dashboard.
  *
  * @return \Illuminate\Http\Response
  */

  public function inicio( Request $request )
  {
    $origenes = Origen_pedidos::orderBy( 'id', 'ASC' )->get() ;

    return View::make( 'origenes/inicio', array( 'origenes' => $origenes ) ) ;
  }

  public function crear()
  {
    $origenes = Origen_pedidos::orderBy( 'id', 'ASC' )->get() ;

    return View::make( 'origenes/crear', array( 'origenes' => $origenes ) ) ;
  }

  public function crear_POST( Request $request )
  {
    $ok = true ;
    $errors = array() ;
    $success = array() ;
    //dd($request);

    // Validamos que el nombre no sea nulo.
    $nombre = $request->input( 'nombre' ) ;
    if ( strlen( $nombre ) == 0 )
    {
      $ok = false ;
      array_push( $errors, 'Error: El campo "Origen" no puede estar vacío.' ) ;
    }

    // Validamos que el nombre no este repetido.
    if ( Origen_pedidos::where( 'nombre', '=', $nombre)->exists() )
    {
      $ok = false ;
      array_push( $errors,'Error: Ya existen orígenes con el nombre indicado.' ) ;
    }

    $origen = new Origen_pedidos ;
    $origen->nombre                     = $request[ 'nombre' ] ;
    $origen->referencia                 = $request[ 'referencia' ] ;
    $origen->color                      = $request[ 'color' ] ;
    $origen->transportista_principal    = $request[ 'transportista_principal' ] ;
    $origen->web                        = $request[ 'web' ] ;
    $origen->api_key                    = $request[ 'api_key' ] ;
    $origen->seguimiento                = $request[ 'seguimiento' ] ;

    if ( $ok )
    { // Si todo está ok, igualamos los atributos y guardamos, por último volvemos a la vista de Acabados.
      if ( $origen->save() )
      {
        array_push( $success, 'Origen creado correctamente.' ) ;
      }

      $vaciar_form = new Origen_pedidos ;
      Session::put( 'origenErr', $vaciar_form ) ;
      Session::put( 'success', $success ) ;

      return redirect( 'origenes' ) ;

    }
    else
    { //Si algo no és correcto, enviamos los errores, el acabado erróneo y volvemos al formulario.
      Session::put( 'origenErr', $origen ) ;

      return back()->with( array( 'errors' => $errors ) ) ;
    }
  }

  public function editar( $id )
  {
    $origen = Origen_pedidos::find( $id ) ;

    return View::make( 'origenes/editar', array( 'origen' => $origen ) ) ;
  }

  public function editar_POST( $id, Request $request )
  {
    $ok = true ;
    $errors = array() ;
    $success = array() ;
    $origen = Origen_pedidos::find( $id ) ;

    // Validamos que el nombre no sea nulo.
    $nombre = $request->input( 'nombre' ) ;
    if ( strlen( $nombre ) == 0 )
    {
      $ok = false ;
      array_push( $errors, 'Error: El campo "Origen" no puede estar vacío.' ) ;
    }

    // Validamos que el nombre no este repetido.
    if ( Origen_pedidos::where( 'nombre', '=', $nombre )
                       ->where( 'nombre', '!=', $origen->nombre )
                       ->exists() )
    {
      $ok = false ;
      array_push( $errors, 'Error: Ya existen orígenes con el nombre indicado.' ) ;
    }

    if ( $ok ) // Si todo está ok, igualamos los atributos y guardamos, por último volvemos a la vista de Acabados.
    {

      $origen->nombre                     = $request[ 'nombre' ] ;
      $origen->referencia                 = $request[ 'referencia' ] ;
      $origen->color                      = $request[ 'color' ] ;
      $origen->transportista_principal    = $request[ 'transportista_principal' ] ;
      $origen->web                        = $request[ 'web' ] ;
      $origen->api_key                    = $request[ 'api_key' ] ;
      $origen->seguimiento                = $request[ 'seguimiento' ] ;

      if ( $origen->save() )
      {
        array_push( $success, 'Cambios guardados correctamente.' ) ;
      }

      Session::put( 'success', $success ) ;
      Session::put( 'origen', $origen ) ;

      return redirect( 'origenes' ) ;
    }
    else //Si algo no es correcto, enviamos los errores, el acabado erroneo y volvemos al formulario.
    {
      return back()->with( array( 'errors' => $errors ) ) ;
    }
  }

  public function eliminar( $id )
  {
    $origen = Origen_pedidos::find( $id ) ;
    $origen->delete() ;

    return redirect( 'origenes' ) ;
  }

}
