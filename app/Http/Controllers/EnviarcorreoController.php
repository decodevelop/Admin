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
use Illuminate\Mail\Message;
use App\Mail\Welcome as WelcomeEmail;

class EnviarcorreoController extends Controller{


  /**
   * Constructor y middleware
   * @return void(true/false auth)
   */
  public function __construct()
  {
      $this->middleware('auth');
  }

    public function enviar(){
      Mail::to('developer@decowood.es', 'Developer')
        ->send(new WelcomeEmail());
    }



}

 ?>
