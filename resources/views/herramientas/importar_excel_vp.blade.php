<?php
use App\Clientes;
use App\Direcciones;
?>
@extends('layouts.backend')

@section('titulo', 'Importar EXCEL ventee prive')
@section('titulo_h1', 'Importar EXCEL ventee prive')

@section('contenido')
<section class="content">
	<div class="row">
	<!-- left column -->
		<div class="col-md-6 col-md-offset-0">
			<div class="box box-primary">
				<!-- /.box-header -->
				<!-- form start -->
				<form action="" enctype="multipart/form-data" method="POST">
					{{ csrf_field() }}
					<input type="hidden" name="inputSeguridad" value="565dsad4874#@3sfasf">
					<div class="box-header with-border">
						<h3 class="box-title">Importador de ficheros EXCEL vp</h3>
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

					@if ($repetidos = Session::get('vpRepetidos'))

					<div class="panel-group">
					  <div class="panel panel-default">
					    <div class="panel-heading">
					      <h4 class="panel-title">
					        <a data-toggle="collapse" href="#Repetidos">Ver repetidos</a>
					      </h4>
					    </div>
					    <div id="Repetidos" class="panel-collapse collapse">

								<ul class="list-group">
									@foreach ($repetidos as $repes)
					        <li class="list-group-item">{{ $repes }}</li>
									@endforeach
					      </ul>
					    </div>
					  </div>
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
