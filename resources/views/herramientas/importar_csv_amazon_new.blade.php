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
				<form action="{{ url('amazon/importar_csv_amazon_subida_new') }}" id="form_csv" enctype="multipart/form-data" method="POST" target="_blank">
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
							<!-- div class="switch-content">
							<label class="switch">
	  						<input type="checkbox">
	  						<span class="slider"></span>
							</label>
						</div -->
								<div class="control-label" style="width:100%;font-weight: bold">Procedencia del CSV a importar: </div>
								<div class="" style="width:50%;float:left">
								<select class="form-control" name="o_csv">
									<option value='AMV'>Amazon Vendor</option>
								</select>
								</div>
								<div class="" style="width:50%;float:left">
								<select class="form-control" name="explorer">
									<option value=';'>Explorer</option>
									<option value=','>Chrome</option>
								</select>
								</div>
								<div class="" style="display: inline-block;width: 100%;margin:10px 0px">
								<div class="" style="width:50%;float:left">
										<label>Transporte</label>
								<select class="form-control" name="transporte">
									<option value="tipsa" >Tipsa</option>
									<option value="nacex" >Nacex</option>
									<option value="asm" >ASM</option>
									<option value="seur" >Seur</option>
									<option value="mrw" >MRW</option>
									<option value="mailboxes" >MailBoxes</option>
									<option value="logicgreen" >LogicGreen</option>
									<option value="transparets">Transparets</option>
									<option value="transporte interno">Transporte Interno</option>
									<option value="solucioneslogisticas" >SOLUCIONES LOGÍSTICAS</option>
									<option value="recogida">Recogida</option>
								</select>
								</div>
								<div style="width:50%;float:left">
									<label>Bultos</label>
									<input type="number" class="form-control" name="bultos" value="1" min="1">
								</div>
								</div>
						</div>
						<div class="form-group">
							<div for="csv">Seleccionar fichero CSV</div>
							<div class="add_import_files">
								<input type="file" name="csv[]"/><br/>
							</div>
							<br/><div class="more_files"><i class="fa fa-plus"></i> Añadir más (solo para mismo cliente ) </div>

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
							<button id="gen_etiquetas" class="btn btn-primary pull-right">Generar etiquetas</button>
							<button id="gen_albaran" onclick="return false" class="btn btn-primary pull-left">Generar Albaranes e importar</button>

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
switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.switch-content {
    right: 10px;
    top: 40px;
    position: absolute;
    height: 34px;
    width: 62px;
}
</style>
