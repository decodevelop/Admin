<?php
use App\Clientes;
use App\Direcciones;
?>
@extends('layouts.backend')

@section('titulo', 'Importar CSV Amazon')
@section('titulo_h1', 'Importar CSV Amazon')

@section('contenido')
<section class="content">
	<div class="row">
	<!-- left column -->
		<div class="col-md-4 col-md-offset-0">
			<div class="box box-primary">
				<!-- /.box-header -->
				<!-- form start -->
				<form action="{{ url('amazon/importar_csv_amazon_subida') }}" enctype="multipart/form-data" method="POST" target="_blank">
					{{ csrf_field() }}
					<div class="box-header with-border">
						<h3 class="box-title">Importador de ficheros CSV Amazon</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button-->
						</div>
					</div>

					<div class="box-body">

						<div class="form-group">
								<label class="control-label">Procedencia del CSV a importar: </label>
								<div class="">
								<select class="form-control" name="o_csv">
									<option value='AMV'>Amazon Vendor</option>
								</select>
								</div>
						</div>
						<div class="form-group">
							<div for="csv">Seleccionar fichero CSV</div>
							<div class="add_import_files">
								<input type="file" name="csv[]"/><br/>
							</div>
							<br/><div class="more_files"><i class="fa fa-plus"></i> Añadir más </div>

							<div class="help-block"> Formato aceptado .CSV</div>
						</div>

						@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Error:</strong> Hay algún problema con el fichero.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
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
