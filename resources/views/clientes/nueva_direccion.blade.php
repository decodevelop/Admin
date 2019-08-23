@extends('layouts.backend')
@section('titulo','Nueva Direccion ')
@section('titulo_h1','Clientes')
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
          <i class="fa fa-tags"></i>&nbsp; Nueva Dirección de {{$cliente->nombre_apellidos}}<small class="pull-right"></small>
        </h2>
      </div>
    </div>
    <div class="row">
      <div class="box-body">
        <form class="" action="" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="product-combi col-xs-12">
              <div class="col-lg-6 col-xs-12">
                <table class="table">
                  <tbody>
                    <tr>
                      <td style="border-top: 0px; width: 20%;"></td>
                      <td style="border-top: 0px;"><strong>Facturación</strong></td>
                    </tr>
                    <tr>
                      <td>Dirección:</td>
                      <td><input name="direccion_facturacion" data-type="text" class="form-control"></td>
                    </tr>
                    <tr>
                      <td>Código Postal:</td>
                      <td><input name="cp_facturacion" type="number" class="form-control"></td>
                    </tr>
                    <tr>
                      <td>Ciudad:</td>
                      <td><input name="ciudad_facturacion" data-type="text" class="form-control"></td>
                    </tr>
                    <tr>
                      <td>Estado:</td>
                      <td><input name="estado_facturacion" data-type="text" class="form-control"></td>
                    </tr>
                    <tr>
                      <td>País:</td>
                      <td><input name="pais_facturacion" data-type="text" class="form-control"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-lg-6 col-xs-12">
                <table class="table">
                  <tbody>
                    <tr>
                      <td style="border-top: 0px; width: 20%;"></td>
                      <td style="border-top: 0px;"><strong>Envío</strong></td>
                    </tr>
                    <tr>
                      <td>Dirección:</td>
                      <td><input name="direccion_envio" data-type="text" class="form-control"></td>
                    </tr>
                    <tr>
                      <td>Código Postal:</td>
                      <td><input name="cp_envio" type="number" class="form-control"></td>
                    </tr>
                    <tr>
                      <td>Ciudad:</td>
                      <td><input name="ciudad_envio" data-type="text" class="form-control"></td>
                    </tr>
                    <tr>
                      <td>Estado:</td>
                      <td><input name="estado_envio" data-type="text" class="form-control"></td>
                    </tr>
                    <tr>
                      <td>País:</td>
                      <td><input name="pais_envio" data-type="text" class="form-control"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <button type="submit" name="enviar" class="btn btn-success pull-right">
            <i class="fa fa-save"></i> Añadir
          </button>

          <button style="margin-right: 10px" type="button" class="btn btn-primary pull-right" onclick="window.location.href='{{Url('/clientes/detalle/'.$cliente->id)}}'">
            <i class="fa fa-arrow-left"></i> Volver
          </button>

        </form>
      </div>

    </div>

  </section>
  <!-- /.box-body -->
@endsection

@section('scripts')

@endsection
