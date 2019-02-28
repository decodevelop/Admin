<?php
use App\Clientes;
use App\Direcciones;
?>
@extends('layouts.backend')

@section('titulo', 'Generador de consulta')
@section('titulo_h1', 'Generador de consulta')

@section('contenido')
<section class="content">
	<div class="row">
	<!-- left column -->
		<div class="col-md-6 col-md-offset-0">
			<div class="box box-primary">
				<!-- /.box-header -->
				<!-- form start -->
				<form action="{{ url('development') }}" enctype="multipart/form-data" method="POST">
					{{ csrf_field() }}
					<input type="hidden" name="inputSeguridad" value="565dsad4874#@3sfasf">
					<div class="box-header with-border">
						<h3 class="box-title">Generador de consulta asginación EANS para BDD Prestashop</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label for="csv">Seleccionar fichero EXCEL</label>
							<input type="file" name="csv" />
							<p class="help-block"> Formato aceptado .xls</p>
							<a href="pantilla" class="btn btn-default btn-sm">Descargar una plantilla del fichero</a>
							<a href="descargar" class="btn btn-success btn-sm">Descargar excel de todos los productos</a>
						</div>

						@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Error:</strong> Hay algún problema con el fichero.<br><br>
							<ul>
								@foreach ($errors as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
						@endif

					@if (isset($success))

						@if (count($success) > 0)

							<div class="alert alert-success alert-block">
								<button type="button" class="close" data-dismiss="alert">×</button>
								@foreach ($success as $suc)
									<li>{{ $suc }}</li>
								@endforeach

							</div>

						@endif
					@endif


						@if ($message = Session::get('info'))
						<div class="alert alert-info alert-block">
						<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>{{ $message }}</strong><br>
							<p>{{ Session::get('ptime') }}<br>
							{{ Session::get('user') }}</p>
							<a href="/documentos/{{ Session::get('path') }}"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar documento cargado.</a>
						</div>

						@endif
						@if ($message = Session::get('danger'))
						<div class="alert alert-danger alert-block">
						<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>{{ $message }}</strong><br>
							<p>{{ Session::get('ptime') }}<br>
							{{ Session::get('user') }}</p>
							<a href="/documentos/{{ Session::get('path') }}"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar documento cargado.</a>
						</div>

						@endif
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-primary pull-right">Iniciar importación</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="col-md-6">
			@if ($products = Session::get('productosConsulta'))
				@foreach ($products as $count => $product)
					@if (is_int($count/182))
						===============================================<br>
					@endif
					UPDATE ps_product_attribute SET ean13 = '{{$product['ean']}}' WHERE '{{$product['id']}}' like id_product_attribute;<br>

				@endforeach

			@endif
		</div>
	</div>
</section>
@endsection
