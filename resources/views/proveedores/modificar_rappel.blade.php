@extends('layouts.backend')
@section('titulo','Modificando Rappel '.$rappel->id)
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
          <i class="fa fa-tags"></i>&nbsp; Modificando Rappel {{$rappel->id}} de {{$proveedor->nombre}}<small class="pull-right"></small>
        </h2>
      </div>
    </div>
    <div class="row">
      <div class="box-body">
        <form class="" action="" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="col-xs-12">
              <div class="col-lg-6 col-md-9 col-sm-12">
                <div class="col-sm-12">
                  <table class="table">
                    <tbody>

                      <tr>
                        <td>Mínimo:</td>
                        <td><input name="min" class="form-control" type="number" step="any" value="{{$rappel->min}}"></td>
                      </tr>
                      <tr>
                        <td>Máximo:</td>
                        <td><input name="max" class="form-control" type="number" step="any" value="{{$rappel->max}}"></td>
                      </tr>
                      <tr>
                        <td>Porcetaje:</td>
                        <td>

                          <input name="condiciones" class="form-control" type="number" step="any" value="{{$rappel->condiciones}}">

                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" name="enviar" class="btn btn-success pull-right">
            <i class="fa fa-save"></i> Guardar cambios
          </button>

          <button style="margin-right: 10px" type="button" class="btn btn-primary pull-right" onclick="window.location.href='{{Url('/proveedores/detalle/'.$proveedor->id)}}'">
            <i class="fa fa-arrow-left"></i> Volver
          </button>

        </form>
      </div>

    </div>

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
