@extends('layouts.backend')
@section('titulo','Nuevo cliente ')
@section('titulo_h1','Clientes ')
@section('estilos')
  <style media="screen">
    .table td {
      vertical-align: middle!important;
    }
  </style>
@endsection

@section('contenido')

  @if (\Session::has('request'))
    @php($requestErr = \Session::pull('request'))
  @endif

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
    <!-- /.box-header -->
    <!-- info row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <i class="fa fa-user"></i> Nuevo cliente
        </h2>
      </div>
    </div>

    <div class="row">
      <div class="box-body">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#tab-cliente">Cliente</a></li>
          <li><a data-toggle="tab" href="#tab-direcciones">Direcciones</a></li>
        </ul>

        <form class="" action="" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="tab-content">
            <div id="tab-cliente" class="tab-pane fade in active">
              @includeIf('clientes.nuevo_cliente.cliente')
            </div>

            <div id="tab-direcciones" class="tab-pane fade in">
              @includeIf('clientes.nuevo_cliente.direcciones')
            </div>
          </div>

          <button type="submit" name="enviar" class="btn btn-success pull-right" style="position: fixed;bottom: 5%;right: 5%;font-size: 20px;">Añadir</button>

        </form>
      </div>
    </div>
  </section>
  <!-- /.box-body -->
@endsection



@section('scripts')
  <script type="text/javascript">

  </script>

@endsection
