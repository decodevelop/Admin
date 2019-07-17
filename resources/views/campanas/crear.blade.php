@extends('layouts.backend')
@if (isset($acabadoAEditar))
	@section('titulo','Editando ('.$acabadoAEditar->nombre.")")
@else
	@section('titulo','Nueva Campaña')
@endif
@section('titulo_h1','Campañas')

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
	@if (\Session::has('campanaErr'))
		@php($campanaErr = \Session::pull('campanaErr'))
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
					<i class="fa fa-globe"></i>&nbsp; Crear nueva campaña<small class="pull-right"></small>
				</h2>
			</div>
		</div>
		<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">
			<form id="crear_nueva_campana_form" action="" method="post">
				{{ csrf_field() }}
				<div class="col-sm-3 invoice-col">
					Referencia: <input type="text" class="form-control input-sm form-margin" name="referencia" value="@if(isset($campanaErr)){{$campanaErr->referencia}}@endif"/>
					Nombre de la campaña: <input type="text" class="form-control input-sm form-margin" name="nombre" value="@if(isset($campanaErr)){{$campanaErr->nombre}}@endif"/>
					Fecha de inicio: <input type="date" class="form-control input-sm form-margin" name="fecha_inicio" value="@if(isset($campanaErr)){{$campanaErr->fecha_inicio}}@endif"/>
					Fecha de finalización: <input type="date" class="form-control input-sm form-margin" name="fecha_fin" value="@if(isset($campanaErr)){{$campanaErr->fecha_fin}}@endif"/>
					Total: <input data-type="number" step="any" class="form-control input-sm form-margin" name="total" value="@if(isset($campanaErr)){{$campanaErr->total}}@endif"/>
					Origen:
					<select class="form-control selectpicker form-margin" data-live-search="true" data-width="100%" name="selectpickmult_origen" title="origen">
						@foreach ($origenes as $atributo)
							<option value="{{$atributo->id}}" title="{{$atributo->nombre}}"
								@if(isset($campanaErr))
									@if (!is_null($campanaErr->origen_id))
  									@if ($campanaErr->origen_id == $atributo->id )
											selected
										@endif
									@endif
								@endif>
								{{$atributo->nombre}}
							</option>
						@endforeach
					</select>
					<input name="origen" type="text" style="display:none">

					Nombre de envío: <input type="text" class="form-control input-sm form-margin" name="nombre_envio" value="@if(isset($campanaErr)){{$campanaErr->nombre_envio}}@endif"/>
					Dirección de envío: <input type="text" class="form-control input-sm form-margin" name="direccion_envio" value="@if(isset($campanaErr)){{$campanaErr->direccion_envio}}@endif"/>
					Ciudad: <input type="text" class="form-control input-sm form-margin" name="ciudad_envio" value="@if(isset($campanaErr)){{$campanaErr->ciudad_envio}}@endif"/>
					Estado: <input type="text" class="form-control input-sm form-margin" name="estado_envio" value="@if(isset($campanaErr)){{$campanaErr->estado_envio}}@endif"/>
					País: <input type="text" class="form-control input-sm form-margin" name="pais_envio" value="@if(isset($campanaErr)){{$campanaErr->pais_envio}}@endif"/>
					Código Postal: <input type="text" class="form-control input-sm form-margin" name="cp_envio" value="@if(isset($campanaErr)){{$campanaErr->cp_envio}}@endif"/>

					<input type="submit" class="btn btn-info" value="Crear nueva campaña"> </input>
				</div>
				<!-- /.col -->
			</form>
		</div>
		<!-- /.row -->
	</section>
	<!-- /.box-body -->
@endsection
