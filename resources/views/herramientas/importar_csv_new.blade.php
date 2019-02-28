<?php
use App\Clientes;
use App\Direcciones;
?>
@extends('layouts.backend')

@section('titulo', 'Importar CSV')
@section('titulo_h1', 'Importar CSV')

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
						<h3 class="box-title">Importador de ficheros CSV</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<div class="box-body">

						<div class="form-group">
							<label for="csv">Seleccionar fichero CSV</label>
							<input type="file" name="csv" />
							<p class="help-block"> Formato aceptado .CSV</p>
							<!-- a href="/importar_observaciones"><p> <i class="fa fa-commenting-o" aria-hidden="true"></i> Importar observaciones </p></a -->

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
							<!--div style="margin-top:20px">
								<a href="/importar_observaciones" class="button-import"> <i class="fa fa-commenting-o" aria-hidden="true"></i> Importar observaciones </a>
							</div-->
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
							<button type="submit" id='button_submit_import' class="btn btn-primary pull-right">Iniciar importación</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<style>
#button_submit_import.desactivate {
		pointer-events: none;
		cursor: default;
		opacity: 0.5;
	}
	a.button-import {
    transition: all 0.2s ease 0.1s;
    font-size: 19px;
    background: #86c7aa;
    color: #222d32;
    font-weight: bold;
    padding: 5px 20px;
    border-radius: 4px;
    text-decoration: none;
}
a.button-import:hover {
	box-shadow: inset 1px 0px 8px 20px #d8cc75;
}
</style>
<script>



</script>
@endsection
