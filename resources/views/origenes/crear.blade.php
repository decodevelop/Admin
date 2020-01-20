@extends('layouts.backend')
@section('titulo','Nuevo Origen')
@section('titulo_h1','Orígenes')

@section('estilos')
	<style>
	.titleDetalle{
		font-size: 18px;
		border-bottom: 1px solid rgba(128, 128, 128, 0.45);
		display: inherit;
		padding-bottom: 6px;
		margin-bottom: -10px;
	}

	.form-margin{
		margin-bottom: 15px!important;
	}
</style>
<link href="{!! asset('css/Dropzone.css') !!}" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('contenido')
	@if (\Session::has('success'))
		@php($success = \Session::pull('success'))
	@endif

	@if (\Session::has('success'))
		@php($success = \Session::pull('success'))
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

	@if (\Session::has('origenErr'))
		@php($origenErr = \Session::pull('origenErr'))
	@endif
	@if (count($errors)>0)
		<div class="pad margin no-print">
			<div class="callout callout-danger" style="margin-bottom: 0!important;">
				<h4><i class="fa fa-info"></i> Han habido {{count($errors)}} errores </h4>
				<ul>
					@foreach ($errors as $error)
						<li>{{$error}}</li>
					@endforeach
				</ul>

			</div>
		</div>
	@endif
	<section class="invoice">
		<!-- title row -->
		<div class="row">
			<div class="col-xs-12">
				<h2 class="page-header">
					<i class="fa fa-globe"></i>&nbsp; Crear nuevo origen<small class="pull-right"></small>
				</h2>
			</div>
		</div>
		<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">
			<form id="crear_nuevo_origen_form" action="" method="post">
				{{ csrf_field() }}
				<div class="col-sm-3 invoice-col">

					Origen: <input type="text" class="form-control input-sm form-margin" name="nombre" value="@if(isset($origenErr)){{$origenErr->nombre}}@endif"/>
					Referencia: <input type="text" class="form-control input-sm form-margin" name="referencia" value="@if(isset($origenErr)){{$origenErr->referencia}}@endif"/>
					Color: <input type="text" class="form-control input-sm form-margin" name="color" value="@if(isset($origenErr)){{$origenErr->color}}@endif"/>
					Transportista Principal: <input type="text" class="form-control input-sm form-margin" name="transportista_principal" value="@if(isset($origenErr)){{$origenErr->transportista_principal}}@endif"/>
					Web: <input type="text" class="form-control input-sm form-margin" name="web" value="@if(isset($origenErr)){{$origenErr->web}}@endif"/>
					Api Key: <input type="text" class="form-control input-sm form-margin" name="api_key" value="@if(isset($origenErr)){{$origenErr->api_key}}@endif"/>
					Seguimiento: <input type="text" class="form-control input-sm form-margin" name="seguimiento" value="@if(isset($origenErr)){{$origenErr->seguimiento}}@endif"/>

					<input type="submit" class="btn btn-info" value="Crear nuevo origen"> </input>

				</div>
				<!-- /.col -->
			</form>
		</div>
		<!-- /.row -->
	</section>
	<!-- /.box-body -->
@endsection
