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
		    width: 255px;
    		height:3.7cm;
		    padding-top: 0.5cm;

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
			height: 30px;
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
		@forelse($productos_imprimir as $keyProductos => $producto)
			@for ($i = 0; $i < $producto['cantidad_producto']; $i++)
				@php ($contadorEtiquetas = $contadorEtiquetas+1)
				<div class="containerImgBarcode">
				    <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($producto['codigo_ean'], 'EAN13')}}" class="imgBarcode" alt="barcode" />
				    <p class="nombreProductoBarcode">{{$producto['nombre_producto']}}</p>
				    <p class="referenciaProductoBarcode">REF: {{$producto['referencia_producto']}}</p>
			    </div>
				@if($contadorEtiquetas>=24)
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
