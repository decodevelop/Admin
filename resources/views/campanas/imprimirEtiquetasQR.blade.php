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
    		height:7cm;
		    padding: 2cm 0.5cm 0 0.5cm;
		}
    p {
      margin-bottom: 0px;
    }
		.imgBarcode{
			/*width: 200px;*/
		}
		.nombreProductoBarcode{
			font-size: 14px;
		}
		.referenciaProductoBarcode{
			height: 20px;
		}
		.nombreProductoBarcode{
			height: 20px;
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
	<div style="padding-top:0.3cm">
		@php ($contadorEtiquetas = 0)
		 @forelse ($palet->productos_palets as $productos_palets)
			@for ($i = 0; $i < $productos_palets->cantidad; $i++)
				@php ($contadorEtiquetas = $contadorEtiquetas+1)
				<div class="containerImgBarcode">
            <img src="/img/logodecopeque.jpg" class="logo" alt="logo" style="width: 2.7cm;margin-bottom: 0.3cm;" />
            <p>¿AMOR A PRIMERA VISTA?</p>
            <p style="margin-bottom: 10px;"><strong>¡ESCANÉAME!</strong></p>

            <img src="data:image/png;base64,{{DNS2D::getBarcodePNG("https://decowood.es/", 'QRCODE')}}" class="imgBarcode" alt="barcode" />
						{{--<p class="nombreProductoBarcode">{{$productos_palets->producto->producto->ean}}</p>
				    <p class="nombreProductoBarcode">{{$productos_palets->producto->producto->nombre}}</p>
				    <p class="referenciaProductoBarcode">REF: {{$productos_palets->producto->producto->referencia}}</p>--}}
			    </div>
				@if($contadorEtiquetas>=12)
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
