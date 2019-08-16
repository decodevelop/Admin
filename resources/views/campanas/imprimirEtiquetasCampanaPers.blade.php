@extends('layouts.frontend')
@section('titulo','Amazon > imprimir')
@section('titulo_h1','Amazon')

@section('contenido')
	<style>
	body{
		background-color: white!important;
	}
	.containerImgBarcode{
		text-align: center;
		display: inline-block;
		width: 380px;
		height:7cm;
		padding: 2.5cm 0.5cm 0 0.5cm;
	}

	.imgBarcode{
		width: 200px;
	}
	.nombreProductoBarcode{
		font-size: 14px;
	}
	.referenciaProductoBarcode{
		height: 20px;
	}
	.nombreProductoBarcode{
		height: 25px;
	}
	@media print {
		.page-break{
			display:block;
			page-break-before:always;
		}
		@page {
			margin: 0.3cm;
			margin-top: 0cm;
			margin-bottom: 0px;
		}
		body {
			margin-top: 0cm;
			margin-bottom: 0cm; }
		}
		</style>
		<div>
			@php ($contadorEtiquetas = 0)
			@forelse ($productos as $producto)
				@for ($i=0; $i < $producto['anzahl']; $i++)
					@php ($contadorEtiquetas = $contadorEtiquetas+1)
					<div class="containerImgBarcode">
						<p class="text-center" style="height: 15px">{{$producto['artnr_limango']}}</p>
						<p class="text-center" style="height: 15px">{{$producto['artnr_hersteller']}}</p>
						<p class="nombreProductoBarcode">{{$producto['nombre']}}</p>
						<p class="nombreProductoBarcode">{{$producto['produkt']}}</p>
						<!--<p class="referenciaProductoBarcode">REF: {{$producto['artnr_hersteller']}}</p>-->
					</div>
					@if($contadorEtiquetas>=8)
						<div class="page-break"></div>
						@php ($contadorEtiquetas = 0)
					@endif
				@endfor
			@empty
				<p>No hay datos.</p>
			@endforelse
		</div>
		<script src="{{url('/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
		<script>
		$(document).ready(function(){
			window.print();
		});

	</script>
@endsection
