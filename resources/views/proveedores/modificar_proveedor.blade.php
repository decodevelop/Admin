@extends('layouts.backend')
@section('titulo','Modificando '.$proveedor->id.'. '.$proveedor->nombre)
@section('titulo_h1','Proveedores')
@section('estilos')

@endsection

@section('contenido')
  @if (\Session::has('success'))
    @php($success = \Session::pull('success'))
  @endif

  @if (\Session::has('alerts'))
    @php($alerts = \Session::pull('alerts'))
  @endif


  @if (\Session::has('mensaje'))
    <div class="pad margin no-print">
      <div class="callout callout-info" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-info"></i> OK!</h4>
        {!! \Session::get('mensaje') !!}
      </div>
    </div>
  @endif
  @if (count($errors)>0)
    <div class="pad margin no-print">
      <div class="callout callout-danger" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-danger"></i> Han habido {{count($errors)}} errores </h4>
        <ul>
          @foreach ($errors as $error)
            <li>{{$error}}</li>
          @endforeach
        </ul>

      </div>
    </div>
  @endif

  @if(isset($success) && count($success)>0)
    <div class="pad margin no-print">
      <div class="callout callout-success" style="margin-bottom: 0!important;">
        @foreach ($success as $s)
          <p><i class="fa fa-check"></i> {{$s}}</p>
        @endforeach
      </div>
    </div>
  @endif

  @if(isset($alerts) && count($alerts)>0)
    <div class="pad margin no-print">
      <div class="callout callout-info" style="margin-bottom: 0!important;">
        @foreach ($alerts as $a)
          <p><i class="fa fa-info"></i> {{$a}}</p>
        @endforeach
      </div>
    </div>
  @endif
  <section class="invoice">
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <i class="fa fa-briefcase"></i>&nbsp; Modificando {{$proveedor->id}}. {{$proveedor->nombre}}<small class="pull-right"></small>
        </h2>
      </div>
    </div>
    <div class="row">
      <div class="box-body">
        <form class="" action="" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="box-body">
            <div class="col-lg-6 col-xs-12">
              <table class="table">
                <tbody>
                  <tr>
                    <td>Nombre:</td>
                    <td><input name="nombre" class="form-control" value="{{$proveedor->nombre}}"></td>
                  </tr>
                  <tr>
                    <td>E-Mail:</td>
                    <td><input name="email" class="form-control" value="{{$proveedor->email}}"></td>
                  </tr>
                  <tr>
                    <td>Teléfono:</td>
                    <td><input name="telefono" class="form-control" value="{{$proveedor->telefono}}"></td>
                  </tr>
                  <tr>
                    <td>Plazo de entrega:</td>
                    <td><input name="plazo_entrega" class="form-control" value="{{$proveedor->plazo_entrega}}"></td>
                  </tr>
                  <tr>
                    <td>Plazo de entrega Web:</td>
                    <td><input name="plazo_entrega_web" class="form-control" value="{{$proveedor->plazo_entrega_web}}"></td>
                  </tr>
                  <tr>
                    <td>Envío:</td>
                    <td><input name="envio" class="form-control" value="{{$proveedor->envio}}"></td>
                  </tr>
                  <tr>
                    <td>Método de pago:</td>
                    <td><input name="metodo_pago" class="form-control" value="{{$proveedor->metodo_pago}}"></td>
                  </tr>
                  <tr>
                    <td>Precio especial campaña:</td>
                    <td><input name="precio_esp_campana" class="form-control" value="{{$proveedor->precio_esp_campana}}"></td>
                  </tr>
                  <tr>
                    <td>Logística:</td>
                    <td><input name="logistica" class="form-control" value="{{$proveedor->logistica}}"></td>
                  </tr>
                  <tr>
                    <td rowspan="2" style="vertical-align: top!important;padding-top: 15px;">Contrato:</td>
                    <td><input name="contrato" class="form-control" value="{{$proveedor->contrato}}"></td>
                  </tr>
                  <tr>
                    <td>
                      <input type="file" name="contrato_pdf" accept="application/pdf">
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="col-lg-6 col-xs-12">
              <table class="table">
                <tbody>
                  <tr>
                    <td>Última visita:</td>
                    <td><input type="date" name="ultima_visita" class="form-control" value="{{$proveedor->ultima_visita}}"></td>
                  </tr>
                  <tr>
                    <td>Vacaciones</td>
                    <td>
                      <div class="col-xs-6" style="padding-left: 0!important">
                        <span style="float: left;padding-top: 7px;">desde: </span><input style="width: 80%;float: right;" type="date" name="vacaciones_inicio" class="form-control" value="{{$proveedor->vacaciones_inicio}}">
                      </div>
                      <div class="col-xs-6" style="padding-right: 0!important">
                        <span style="float: left;padding-top: 7px;">hasta: </span><input style="width: 80%;float: right;" type="date" name="vacaciones_fin" class="form-control" value="{{$proveedor->vacaciones_fin}}">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td style="vertical-align: top!important;padding-top: 15px;">Observaciones:</td>
                    <td>
                      <div contenteditable="true" data-inputname="observaciones" class="textarea-transform-init textarea-observaciones" rows="3" cols="20" >
                        {!! $proveedor->observaciones !!}
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <p class="page-header" style="font-size: 18px;">
                  <i class="fa fa-calendar"></i> Horario
                </p>
              </div>
            </div>

            <div class="col-lg-6 col-xs-12">
              <table class="table">
                <tbody>
                  <tr>
                    <td>Lunes:</td>
                    <td><input name="hor_lunes" class="form-control" value="@if(isset($horario->lunes)){{$horario->lunes}}@endif"></td>
                  </tr>
                  <tr>
                    <td>Martes:</td>
                    <td><input name="hor_martes" class="form-control" value="@if(isset($horario->martes)){{$horario->martes}}@endif"></td>
                  </tr>
                  <tr>
                    <td>Miércoles:</td>
                    <td><input name="hor_miercoles" class="form-control" value="@if(isset($horario->miercoles)){{$horario->miercoles}}@endif"></td>
                  </tr>
                  <tr>
                    <td>Jueves:</td>
                    <td><input name="hor_jueves" class="form-control" value="@if(isset($horario->jueves)){{$horario->jueves}}@endif"></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="col-lg-6 col-xs-12">
              <table class="table">
                <tbody>
                  <tr>
                    <td>Viernes:</td>
                    <td><input name="hor_viernes" class="form-control" value="@if(isset($horario->viernes)){{$horario->viernes}}@endif"></td>
                  </tr>
                  <tr>
                    <td>Sábado:</td>
                    <td><input name="hor_sabado" class="form-control" value="@if(isset($horario->sabado)){{$horario->sabado}} @else Cerrado @endif"></td>
                  </tr>
                  <tr>
                    <td>Domingo:</td>
                    <td><input name="hor_domingo" class="form-control" value="@if(isset($horario->domingo)){{$horario->domingo}} @else Cerrado @endif"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div style="margin-right: 20px">
          <button type="submit" name="enviar" class="btn btn-success pull-right">
            <i class="fa fa-save"></i> Guardar cambios
          </button>

          <button style="margin-right: 10px" type="button" class="btn btn-primary pull-right" onclick="window.location.href='{{Url('/proveedores/detalle/'.$proveedor->id)}}'">
            <i class="fa fa-arrow-left"></i> Volver
          </button>
        </div>
      </div>
    </form>
  </div>

</div>


<style media="screen">
  .richText .richText-editor {
    height: 275px;
  }

  .table td {
    vertical-align: middle!important;
  }
</style>

</section>
<!-- /.box-body -->
@endsection

@section('scripts')
  <script type="text/javascript">
  jQuery(document).ready(function($){
    /*
    $('.input-transform').click(function(){
    input_transform(this);
  });

  $('.textarea-transform').click(function(){
  textarea_transform(this);


});

$('.textarea-transform-init').each(function(){
textarea_transform(this);
});
*/
$(".addCombi").click(function(){
  var clone = $(".product-combi").first().clone();

  clone.appendTo("#product-combis");
});
});
/*
function textarea_transform(thisInput){
$(thisInput).hide('fast');

$("<textarea>" ,{
'class': 'form-control textarea-'+$(thisInput).data('inputname'),
'style': 'display:none',
'text': $.trim($(thisInput).html()),
'name': $(thisInput).data('inputname'),
'rows': $(thisInput).data('rows'),
'cols': $(thisInput).data('cols'),

}).appendTo($(thisInput).parent()).delay(400).show('slow').richText();
}
*/
</script>

@endsection
