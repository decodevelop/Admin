<?php

namespace App\Http\Controllers ;

use Illuminate\Http\Request ;
use Auth ;
use View ;
use Illuminate\Support\Facades\DB ;
use App\Transportistas ;
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

class TransportistasController extends Controller
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
    $transportistas = Transportistas::orderBy( 'id', 'ASC' )->get() ;

    return View::make( 'transportistas/inicio', array( 'transportistas' => $transportistas ) ) ;
  }

  public function crear()
  {
    $transportistas = Transportistas::orderBy( 'id', 'ASC' )->get() ;

    return View::make( 'transportistas/crear', array( 'transportistas' => $transportistas ) ) ;
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
      array_push( $errors, 'Error: El campo "Transportista" no puede estar vacío.' ) ;
    }

    // Validamos que el nombre no este repetido.
    if ( Transportistas::where( 'nombre', '=', $nombre)->exists() )
    {
      $ok = false ;
      array_push( $errors,'Error: Ya existen transportistas con el nombre indicado.' ) ;
    }

    $transportista = new Transportistas ;
    $transportista->nombre                     = $request[ 'nombre' ] ;

    if ( $ok )
    { // Si todo está ok, igualamos los atributos y guardamos, por último volvemos a la vista de Acabados.
      if ( $transportista->save() )
      {
        array_push( $success, 'Transportista creado correctamente.' ) ;
      }

      $vaciar_form = new Transportistas ;
      Session::put( 'transportistaErr', $vaciar_form ) ;
      Session::put( 'success', $success ) ;

      return redirect( 'transportistas' ) ;

    }
    else
    { //Si algo no és correcto, enviamos los errores, el acabado erróneo y volvemos al formulario.
      Session::put( 'transportistaErr', $transportista ) ;

      return back()->with( array( 'errors' => $errors ) ) ;
    }
  }

  public function editar( $id )
  {
    $transportista = Transportistas::find( $id ) ;

    return View::make( 'transportistas/editar', array( 'transportista' => $transportista ) ) ;
  }

  public function editar_POST( $id, Request $request )
  {
    $ok = true ;
    $errors = array() ;
    $success = array() ;
    $transportista = Transportistas::find( $id ) ;

    // Validamos que el nombre no sea nulo.
    $nombre = $request->input( 'nombre' ) ;
    if ( strlen( $nombre ) == 0 )
    {
      $ok = false ;
      array_push( $errors, 'Error: El campo "Transportista" no puede estar vacío.' ) ;
    }

    // Validamos que el nombre no este repetido.
    if ( Transportistas::where( 'nombre', '=', $nombre )
                       ->where( 'nombre', '!=', $transportista->nombre )
                       ->exists() )
    {
      $ok = false ;
      array_push( $errors, 'Error: Ya existen transportistas con el nombre indicado.' ) ;
    }

    if ( $ok ) // Si todo está ok, igualamos los atributos y guardamos, por último volvemos a la vista de Acabados.
    {

      $transportista->nombre                     = $request[ 'nombre' ] ;

      if ( $transportista->save() )
      {
        array_push( $success, 'Cambios guardados correctamente.' ) ;
      }

      Session::put( 'success', $success ) ;
      Session::put( 'transportista', $transportista ) ;

      return redirect( 'transportistas' ) ;
    }
    else //Si algo no es correcto, enviamos los errores, el acabado erroneo y volvemos al formulario.
    {
      return back()->with( array( 'errors' => $errors ) ) ;
    }
  }

  public function eliminar( $id )
  {
    $transportista = Transportistas::find( $id ) ;
    $transportista->delete() ;

    return redirect( 'transportistas' ) ;
  }

}
