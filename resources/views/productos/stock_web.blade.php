<?php
use App\Clientes;
use App\Direcciones;
?>
@extends('layouts.backend')

@section('titulo', 'Subir CSV stock')
@section('titulo_h1', 'Subir CSV stock')

@section('contenido')
<section class="content">
	<div class="row">
	<!-- left column -->
		<div class="col-md-4 col-md-offset-0">
			<div class="box box-primary">
				<!-- /.box-header -->
				<!-- form start -->
				<form action="{{ url('productos/stock_web') }}" enctype="multipart/form-data" method="POST">
					{{ csrf_field() }}
					<div class="box-header with-border">
						<h3 class="box-title">Subir csv stock</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button-->
						</div>
					</div>

					<div class="box-body">

						<div class="form-group">
							<div for="csv">Seleccionar fichero CSV</div>
							<div class="add_import_files">
								<input type="hidden" name="MAX_FILE_SIZE" value="100000">
								<input type="file" name="archivo"/><br/>
							</div>

							<div class="help-block"> Formato aceptado .CSV</div>
						</div>
							@if (count($success) > 0)
								<div class="alert alert-success">
									<ul>
										@foreach ($success as $sucs)
											<li>{!! $sucs !!}</li>
										@endforeach
									</ul>
								</div>
							@endif
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

						@if ($message = Session::get('success'))
						<div class="alert alert-success alert-block">
						<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>{{ $message }}</strong><br>
							<p>{{ Session::get('ptime') }}<br>
							{{ Session::get('user') }}</p>
							<a href="/documentos/{{ Session::get('path') }}"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar documento cargado.</a>
						</div>

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
	</div>
	<a id="CA-web" href="https://Cajasdemadera.com/act_prod.php?codsecprod=856954123695" target="_blank">Cajasdemadera.com</a>
	<a id="CB-web" href="https://Cabeceros.com/act_prod.php?codsecprod=856954123695" target="_blank">Cabeceros.com</a>
	<a id="TT-web" href="https://latetedelit.fr/act_prod.php?codsecprod=856954123695" target="_blank">Latetedelit.fr</a>

</section>
@endsection
<style>
.remove_file:hover {
    opacity: 1;
}
.remove_file {
	transition: all 0.2s ease;
    margin-left: 30px;
    margin-top: 10px;
    opacity: 0.6;
    cursor: pointer;
}
.more_files:hover {
    opacity: 1;
    border: 1px solid #333333;
}
.more_files {
		transition: all 0.2s ease;
	  opacity: 0.6;
    cursor: pointer;
    margin: 20px 10px;
    background-color: #f1f1f1;
    color: #000000;
    display: inline-block;
    padding: 3px 10px;
    border-radius: 2px;
    border: 1px solid #939393;
}
input[type=file] {
    display: inline !important;
    float: left;
		margin-left: 10px;
		margin-top: 5px;
}
.new_file {
    display: block;
    float: left;
    margin-top: 12px;
}
.add_import_files {
    display: inline-block;
}

</style>
<script>

</script>
