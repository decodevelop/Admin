<?php
use App\Clientes;
use App\Direcciones;
?>
@extends('layouts.backend')

@section('titulo', 'Importar OBSERVACIONES')
@section('titulo_h1', 'Importar OBSERVACIONES')

@section('contenido')
<section class="content">
	<div class="row">
	<!-- left column -->
		<div class="col-md-4 col-md-offset-0">
			<div class="box box-primary">
				<!-- /.box-header -->
				<!-- form start -->
				<form action="{{ url('importar_observaciones') }}" enctype="multipart/form-data" method="POST">
					{{ csrf_field() }}
					<div class="box-header with-border">
						<h3 class="box-title">Importador OBSERVACIONES <i class="fa fa-commenting-o" aria-hidden="true"></i></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<div class="box-body">

						<div class="form-group">
								<label class="control-label">Procedencia del CSV a importar: </label>
								<div class="">
								<select class="form-control" name="o_csv">
                  <option value='NONE'> --- </option>
									<option value='CA'>Cajasdemadera.com (Prestashop)</option>
									<option value='CB'>Cabeceros.com</option>
									<option value='CJ'>Cojines.es</option>
									<option value="TT">Latetedelit.fr (Cabeceros Francia)</option>
									<option value="FS">Foxandsocks.es</option>
								</select>
								</div>
						</div>
						<div class="form-group">
							<label for="csv">Seleccionar fichero CSV</label>
							<input type="file" name="csv" />
							<p class="help-block"> Formato aceptado .CSV</p>
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
              <a class="button-back" href="/importar_csv">Volver</a>
							<button type="submit" id='button_submit_import' class="btn btn-primary pull-right">Importar observaciones <i class="fa fa-commenting-o" aria-hidden="true"></i></button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<style>
  form[method="POST"] {
     background: #f7f7b7;
  }

  .box.box-primary {
     border-top-color: #000000;
  }

  .box-footer, select.form-control {
     background: #ffffd5;
  }

  h3.box-title {
     font-weight: bolder;
  }

  form[method="POST"] {
     background: #f7f7b7;
     box-shadow: 0px 4px 30px 5px #cacaca;
  }
  a.button-back:hover {
    box-shadow: inset 1px 0px 8px 20px #222d32;
}
a.button-back {
    transition: all 0.2s ease 0.1s;
    background: #d09d04;
    padding: 8px 20px;
    line-height: 35px;
    color: white;
    border-radius: 3px;
}
#button_submit_import.desactivate {
    pointer-events: none;
    cursor: default;
    opacity: 0.5;
  }
</style>
@endsection
