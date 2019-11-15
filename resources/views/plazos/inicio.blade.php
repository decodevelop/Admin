@extends('layouts.backend')
@section('titulo','Plazos')
@section('titulo_h1','Plazos')

@section('estilos')
@endsection

@section('contenido')

  <section class="content">
  	<div class="row">
  	<!-- left column -->
  		<div class="col-md-4 col-md-offset-0">
  			<div class="box box-primary">
  				<!-- /.box-header -->
  				<!-- form start -->
  				<form action="" enctype="multipart/form-data" method="POST">
  					{{ csrf_field() }}
  					<div class="box-header with-border">
  						<h3 class="box-title">Seleccionar Fecha de salida</h3>
  						<div class="box-tools pull-right">
  							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
  							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
  						</div>
  					</div>
  					<div class="box-body">

  						<div class="form-group">

  								<div class="">
                    <input class="form-control input-sm filterProducts" type="date" name="fecha_pedido" placeholder="" value="">
  								</div>
  						</div>


  						<!-- /.box-body -->
  						<div class="box-footer">
  							<button type="submit" id='button_submit_import' class="btn btn-primary pull-right">Iniciar importación</button>
  						</div>
  					</div>
  				</form>
  			</div>
  		</div>
  	</div>

    <div class="row">
  	<!-- left column -->

    @php $i =0; @endphp

    @foreach ($fechas as $fecha => $productos)
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">{{date('d-m-y',$fecha)}}</h3>
            <div class="box-tools pull-right">
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                @foreach ($productos as $producto)
                  @if (isset($producto->pedido))
                    <tr data-ref="{{$producto->SKU}}" data-ean="{{$producto->ean}}">
                      <td>{{ $producto->pedido->numero_albaran }}</td>
                      <td>{{ $producto->nombre_esp }}</td>
                      <td>{{$producto->SKU}}</td>
                      <td>{{$producto->ean}}</td>
                    </tr>
                  @endif
                @endforeach
              </table>
            </div>
          </div>
        </div>

      </div>
      @php $i++; @endphp
      @if ($i == 2)
        <div style="width:100%;float:left"></div>
        @php $i= 0; @endphp
      @endif
    @endforeach
    </div>



  </section>

@endsection

@section('scripts')

@endsection
