@extends('layouts.backend')
@section('titulo','Detalles producto número ('.$detalles_producto->id.")")
@section('estilos')
<style>
	.titleDetalle{
		font-size: 18px;
    	border-bottom: 1px solid rgba(128, 128, 128, 0.45);
    	display: inherit;
	    padding-bottom: 6px;
   		margin-bottom: -10px;
	}
</style>
<link href="{!! asset('css/Dropzone.css') !!}" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('contenido')
@if (\Session::has('mensaje'))
	<div class="pad margin no-print">
      <div class="callout callout-info" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-info"></i> OK!</h4>
        {!! \Session::get('mensaje') !!}
      </div>
    </div>
@endif
<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i> Detalles de producto seleccionado <small class="pull-right">Producto: {{ $detalles_producto->id }}</small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
		<!-- info row -->

	<form id="modificarDetalle" action="{{Url('/productos/actualizar_detalle/')}}" method="post">
		{{ csrf_field() }}
		<input type="hidden" name="id_detalle" value="{{$detalles_producto->id}}">
		<div class="row">
			<div class="col-sm-12 invoice-col">
				<strong><input type="text" name="nombre" class="form-control input-sm" value="{{ $detalles_producto->nombre }}" required></strong><br>
				<textarea name="descripcion" class="form-control input-sm" required>{{ $detalles_producto->descripcion }}</textarea> 	
			</div>
		</div>
		<br>

		<div class="row invoice-info">

			<div class="col-sm-4 invoice-col">
				<span class="titleDetalle">Datos Generales </span><br>
				
				<b>SKU Anterior:</b> 
				<input type="text" name="skuAnterior" class="form-control input-sm" value="{{ $detalles_producto->skuAnterior }}"><br>
				<b>SKU Actual:</b> 
				<input type="hidden" name="skuActual" class="form-control input-sm" value="{{ $detalles_producto->skuActual }}" required ><br> 
				<b>EAN:</b>
				<input type="text" name="ean" class="form-control input-sm" value="{{ $detalles_producto->ean }}"><br>
				<b>Stock:</b>
				<input type="number" name="stock" class="form-control input-sm" value="{{ $detalles_producto->stock }}"><br>
				<b>Material:</b>
				<input type="text" name="material" class="form-control input-sm" value="{{ $detalles_producto->material }}" required><br>
				<b>Color:</b>
				<input type="text" name="color" class="form-control input-sm" value="{{ $detalles_producto->color }}" required><br>
				<b>Acabado:</b>
				<input type="text" name="acabado" class="form-control input-sm" value="{{ $detalles_producto->acabado }}"><br>
				<b>País origen:</b>
				<select name="paisOrigen" class="form-control input-sm">
					<option value="ES" {{( $detalles_producto->paisOrigen=='ES' || $detalles_producto->paisOrigen=='es') ? 'Selected' : ''}}>España</option>
				</select>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				<span class="titleDetalle">Medidas - Peso </span><br>
				<b>Largo:</b>
				<input type="number" name="largo" class="form-control input-sm" value="{{ $detalles_producto->largo }}" required><br>
				<b>Alto:</b>
				<input type="number" name="alto" class="form-control input-sm" value="{{ $detalles_producto->alto }}" required><br>
				<b>Ancho:</b>
				<input type="number" name="ancho" class="form-control input-sm" value="{{ $detalles_producto->ancho }}"><br>
				<b>Diámetro:</b>
				<input type="number" name="diametro" class="form-control input-sm" value="{{ $detalles_producto->diametro }}"><br>
				<b>Peso:</b>
				<input type="number" name="peso" class="form-control input-sm" value="{{ $detalles_producto->peso }}" required> <br>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				<span class="titleDetalle">Proveedor </span><br>
				<b>Nombre:</b>
				<input type="text" name="proveedor" class="form-control input-sm" value="{{ $detalles_producto->proveedor }}"><br>
				<b>Tiempo entrega:</b>
				<input type="number" name="tiempoEntrega" class="form-control input-sm" value="{{ $detalles_producto->tiempoEntrega }}"><br>
				<b>Ficha técnica:</b> <br>				
			</div>
		</div>
		<br>
		<div class="row invoice-info">
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				<span class="titleDetalle"> Packaging </span><br>
				<b>Packaging:</b> 
				<input type="text" name="packaging" class="form-control input-sm" value="{{ $detalles_producto->packaging }}"><br>
				<b>Proveedor Packaging:</b>
				<input type="text" name="proveedorPackaging" class="form-control input-sm" value="{{ $detalles_producto->proveedorPackaging }}"><br>
				<b>Número cajas:</b> 
				<input type="number" name="nCajas" class="form-control input-sm" value="{{ $detalles_producto->nCajas }}"><br>
				<b>Productos por caja:</b> 
				<input type="number" name="productoxCaja" class="form-control input-sm" value="{{ $detalles_producto->productoxCaja }}"><br>
				<b>Largo empaquetado:</b>
				<input type="number" name="largoEmpaquetado" class="form-control input-sm" value="{{ $detalles_producto->largoEmpaquetado }}"><br>
				<b>Alto empaquetado:</b>
				<input type="number" name="altoEmpaquetado" class="form-control input-sm" value="{{ $detalles_producto->altoEmpaquetado }}"><br>
				<b>Ancho empaquetado:</b>
				<input type="number" name="anchoEmpaquetado" class="form-control input-sm" value="{{ $detalles_producto->anchoEmpaquetado }}"><br>
				<b>Peso empaquetado:</b> 
				<input type="number" name="pesoEmpaquetado" class="form-control input-sm" value="{{ $detalles_producto->pesoEmpaquetado }}"><br>
				<b>Montaje:</b>
				<select name="montaje" class="form-control input-sm">
					<option value="1" {{( $detalles_producto->montaje==1) ? 'Selected' : ''}}>Si</option>
					<option value="0" {{( $detalles_producto->montaje==0) ? 'Selected' : ''}}>No</option>
				</select><br>
				<b>Instrucciones:</b>
				<select name="instrucciones" class="form-control input-sm">
					<option value="1" {{( $detalles_producto->instrucciones==1) ? 'Selected' : ''}}>Si</option>
					<option value="0" {{( $detalles_producto->instrucciones==0) ? 'Selected' : ''}}>No</option>
				</select>
				<br>
				<b>Lavado:</b>
				<textarea name="lavado" class="form-control input-sm">{{$detalles_producto->lavado}}</textarea><br>
			</div>
			<div class="col-sm-4 invoice-col">
				<span class="titleDetalle">Precios </span><br>
				<b>Precio coste:</b> 
				<input type="number" name="pcoste" class="form-control input-sm" value="{{ $detalles_producto->pcoste }}" required><br>
				<b>Precio base:</b>
				<input type="number" name="pbase" class="form-control input-sm" value="{{ $detalles_producto->pbase }}" required><br>
				<b>Precio base+5:</b>
				<input type="number" name="pbase_5" class="form-control input-sm" value="{{ $detalles_producto->pbase_5 }}" required><br>
				<b>Precio base+6:</b>
				<input type="number" name="pbase_6" class="form-control input-sm" value="{{ $detalles_producto->pbase_6 }}" required><br>
				<b>Precio base+10:</b>
				<input type="number" name="pbase_10" class="form-control input-sm" value="{{ $detalles_producto->pbase_10 }}" required><br>
				<b>PVP recomendado:</b>
				<input type="number" name="pvprecomendado" class="form-control input-sm" value="{{ $detalles_producto->pvprecomendado }}" required><br>
				<b>PVP web:</b>
				<input type="number" name="pvpweb" class="form-control input-sm" value="{{ $detalles_producto->pvpweb }}" required><br>

			</div>
		</div>
		<div class="row no-print">
			<div class="col-xs-12">
				<!--<input id="modificar_pedido" class="btn btn-success pull-right" type="submit" name="" value="modificar">-->
				<button type="submit" id="modificar_pedido" class="btn btn-success pull-right" onclick="form.submit()"><i class="fa fa-edit"></i>Finalizar y guardar</button>
			</div>
		</div>
	</form>
		<!-- /.row -->
		<br>
		<br>
		<div class="row">
			<!-- accepted payments column -->
			<div class="col-xs-12 col-md-3">
				<p class="lead">Foto Principal:</p>
				{{-- SACAR LA FOTO PRINCIPAL --}}
					@if ($imgActual = '') @endif

					@if(File::exists(public_path('/imgProductos/'.$detalles_producto->skuActual.'.jpg')))
						@php ($imgActual = $detalles_producto->skuActual.'.jpg') 
					@elseif(File::exists(public_path('/imgProductos/'.$detalles_producto->skuActual.'.jpeg')))
						@php ($imgActual = $detalles_producto->skuActual.'.jpeg') 
					@elseif(File::exists(public_path('/imgProductos/'.$detalles_producto->skuActual.'.png')))
						@php ($imgActual = $detalles_producto->skuActual.'.png') 
					@endif

					@if ($imgActual != '')
						<div class="col-xs-12 col-md-3">
							<img src="{{ asset('/imgProductos/'.$imgActual) }}" style="width: 150px; height: 150px; border: 1px solid rgba(128, 128, 128, 0.2);" alt="imgProducto"/>
							<div class="botonesFotoPrincipal">
								<a href="/productos/eliminarPrincipal/{{$detalles_producto->id}}/{{$imgActual}}">Eliminar</a>
							</div>
						</div>
					@else
						No hay foto principal.
					@endif

			</div>

			<div class="col-xs-12 col-md-9">
				<p class="lead">Fotos Secundarias:</p>
					@if(!File::exists(public_path('/imgProductos/'.$detalles_producto->skuActual.'/')))
						@php (File::makeDirectory(public_path('/imgProductos/'.$detalles_producto->skuActual.'/'))) 
					@endif

					@php ($fotosSecundarias = File::files(public_path('/imgProductos/'.$detalles_producto->skuActual.'/')))

					@if(count($fotosSecundarias)>0)
						@foreach ($fotosSecundarias as $keyFS => $foto)
							@php ($foto_name = pathinfo($foto)["basename"])
							<div class="col-xs-12 col-md-2">
								<img src="{{ asset('/imgProductos/'.$detalles_producto->skuActual.'/'.$foto_name) }}" style="width: 150px; height: 150px; border: 1px solid rgba(128, 128, 128, 0.2);" alt="imgProducto_{{$keyFS}}"/>
								<div class="botonesFotosSecundarias">
									<a href="/productos/eliminarSecundaria/{{$detalles_producto->id}}/{{$detalles_producto->skuActual}}/{{$foto_name}}">Eliminar</a>
								</div>
							</div>
						@endforeach
					@else
						No hay fotos secundarias.
					@endif
			</div>
		</div>
		<br>
	<br>
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<form action="{{Url('/productos/subirFoto/'.$detalles_producto->id.'/'.$detalles_producto->skuActual.'/principal')}}" class="dropzone dz-clickable" method="post">
				{{ csrf_field() }}		
				<div class="dz-default dz-message">
					<span>Subir imagen principal</span>
				</div>
			</form>
		</div>
		<div class="col-xs-12 col-md-6">
			<form action="{{Url('/productos/subirFoto/'.$detalles_producto->id.'/'.$detalles_producto->skuActual.'/secundaria')}}" class="dropzone dz-clickable" method="post">
				{{ csrf_field() }}
				<div class="dz-default dz-message">
					<span>Subir foto secundaria</span>
				</div>
			</form>
		</div>
	</div>
</section>
<!-- /.box-body -->
@endsection

@section('scripts')
	<script type="text/javascript" src="{!! asset('js/Dropzone.js') !!}"></script>
	@if (isset($resultadoBorradoPrincipal) || isset($resultadoBorradoSecundario))
		<script type="text/javascript">
			window.location.href = "{{Url('/productos/modificar/'.$detalles_producto->id)}}";		
		</script>
	@endif
@endsection