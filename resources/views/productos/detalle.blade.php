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
		<div class="row">
			<div class="col-sm-12 invoice-col">
				<strong>{{ $detalles_producto->nombre }}</strong><br>
				{{ $detalles_producto->descripcion }}
			</div>
		</div>
		<br>

		<div class="row invoice-info">

			<div class="col-sm-4 invoice-col">
				<span class="titleDetalle">Datos Generales </span><br>
				
				<b>SKU Anterior:</b> {{ $detalles_producto->skuAnterior }}<br>
				<b>SKU Actual:</b> {{ $detalles_producto->skuActual }}<br> 
				<b>EAN:</b> {{ $detalles_producto->ean }}<br>
				<b>Stock:</b> {{ $detalles_producto->stock }}<br>
				<b>Material:</b> {{ $detalles_producto->material}}<br>
				<b>Color:</b> {{ $detalles_producto->color }} <br>
				<b>Acabado:</b> {{ $detalles_producto->acabado }}<br>
				<b>País origen:</b> {{ $detalles_producto->paisOrigen }}
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				<span class="titleDetalle">Medidas - Peso </span><br>
				<b>Largo:</b> {{ ($detalles_producto->largo!='' && $detalles_producto->largo!=NULL) ? $detalles_producto->largo.' cm' : '' }}<br>
				<b>Alto:</b> {{ ($detalles_producto->alto!='' && $detalles_producto->alto!=NULL) ? $detalles_producto->alto.' cm' : '' }} <br>
				<b>Ancho:</b> {{ ($detalles_producto->ancho!='' && $detalles_producto->ancho!=NULL) ? $detalles_producto->ancho.' cm' : '' }}<br>
				<b>Diámetro:</b> {{ ($detalles_producto->diametro!='' && $detalles_producto->diametro!=NULL) ? $detalles_producto->diametro.' cm' : '' }}<br>
				<b>Peso:</b> {{ ($detalles_producto->peso!='' && $detalles_producto->peso!=NULL) ? $detalles_producto->peso.' kg' : '' }}<br>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				<span class="titleDetalle">Proveedor </span><br>

				<b>Nombre:</b> {{ $detalles_producto->proveedor }}<br>
				<b>Tiempo entrega:</b> {{ ($detalles_producto->tiempoEntrega!='' && $detalles_producto->tiempoEntrega!=NULL) ? $detalles_producto->tiempoEntrega.' dia/s' : '' }}<br>
				<b>Ficha técnica:</b> <br>				
			</div>
		</div>
		<br>
		<div class="row invoice-info">
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				<span class="titleDetalle"> Packaging </span><br>
				<b>Packaging:</b> {{ $detalles_producto->packaging }}<br>
				<b>Proveedor Packaging:</b> {{ $detalles_producto->proveedorPackaging }}<br>
				<b>Número cajas:</b> {{ $detalles_producto->nCajas }}<br>
				<b>Productos por caja:</b> {{ $detalles_producto->productoxCaja }}<br>
				<b>Largo empaquetado:</b> {{ ($detalles_producto->largoEmpaquetado!='' && $detalles_producto->largoEmpaquetado!=NULL) ? $detalles_producto->largoEmpaquetado.' cm' : '' }}<br>
				<b>Alto empaquetado:</b> {{ ($detalles_producto->altoEmpaquetado!='' && $detalles_producto->altoEmpaquetado!=NULL) ? $detalles_producto->altoEmpaquetado.' cm' : '' }}<br>
				<b>Ancho empaquetado:</b> {{ ($detalles_producto->anchoEmpaquetado!='' && $detalles_producto->anchoEmpaquetado!=NULL) ? $detalles_producto->anchoEmpaquetado.' cm' : '' }}<br>
				<b>Peso empaquetado:</b> {{ ($detalles_producto->pesoEmpaquetado!='' && $detalles_producto->pesoEmpaquetado!=NULL) ? $detalles_producto->pesoEmpaquetado.' kg' : '' }}<br>
				<b>Montaje:</b> {{ ($detalles_producto->montaje==1) ? 'Sí' : 'No'  }}<br>
				<b>Instrucciones:</b> <br>
				<b>Lavado:</b> {{$detalles_producto->lavado}}<br>
			</div>
			<div class="col-sm-4 invoice-col">
				<span class="titleDetalle">Precios </span><br>
				<b>Precio coste:</b> {{ $detalles_producto->pcoste }} €<br>
				<b>Precio base:</b> {{ $detalles_producto->pbase }} €<br>
				<b>Precio base+5:</b> {{ $detalles_producto->pbase_5 }} €<br>
				<b>Precio base+6:</b> {{ $detalles_producto->pbase_6 }} €<br>
				<b>Precio base+10:</b> {{ $detalles_producto->pbase_10 }} €<br>
				<b>PVP recomendado:</b> {{ $detalles_producto->pvprecomendado }} €<br>
				<b>PVP web:</b> {{ $detalles_producto->pvpweb }} €<br>

			</div>
		</div>
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
						<img src="{{ asset('/imgProductos/'.$imgActual) }}" style="width: 150px; height: 150px; border: 1px solid rgba(128, 128, 128, 0.2);" alt="imgProducto"/>
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
							<img src="{{ asset('/imgProductos/'.$detalles_producto->skuActual.'/'.$foto_name) }}" style="width: 150px; height: 150px; border: 1px solid rgba(128, 128, 128, 0.2);" alt="imgProducto_{{$keyFS}}"/>
						@endforeach
					@else
						No hay fotos secundarias.
					@endif

			</div>
		</div>
		<br>
		<div class="row no-print">
			<div class="col-xs-12">
				<button type="button" id="modificar_pedido" class="btn btn-success pull-right" onclick="window.location.href='{{Url('/productos/modificar/'.$detalles_producto->id)}}'"><i class="fa fa-edit"></i> modificar</button>
				
			</div>
		</div>

</section>
<!-- /.box-body -->
@endsection

@section('scripts')

@endsection