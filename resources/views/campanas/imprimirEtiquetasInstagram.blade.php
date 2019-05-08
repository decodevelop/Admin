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
	    display: flex;
	    justify-content: center;
	    width: 6cm;
	    height: 6cm;
	    margin: 0cm 0.7cm 0.7cm 0cm;
	    align-items: center;
			float: left;
      /*border: 1px solid black;
      border-radius: 50%;*/

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
		.page-break{
			display:block;
			page-break-before:always;
			/*height: 1.8cm;
	    width: 100%;
	    float: left;*/
		}
    .small, small {
    font-size: 66%;
    }
	 .nombreProducto {
    position: relative;
    bottom: -47px;
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
	<div >
		@php ($contadorEtiquetas = 0)
			@for ($i = 0; $i < 12; $i++)
				@if($contadorEtiquetas==0)
					<div class="" style="margin:1.8cm 0 0 0.7cm;display: block;float: left;">

				@endif
				@php ($contadorEtiquetas = $contadorEtiquetas+1)
				<div class="containerImgBarcode" style="padding-top: 57px;">
					<div class="container-qr">
						<img src="/img/logodecopeque.jpg" class="logo" alt="logo" style="width: 3.5cm;margin-bottom: 0.6cm;" />
            <p>¡Síguenos en Instagram!</p>
            <p style="margin-bottom: -9px;"><strong>{{'@decowood.es'}}</strong></p>
            <p> <small>WWW.DECOWOOD.ES</small> </p>


					</div>

			    </div>
				@if($contadorEtiquetas>=12)
					</div>
					<div class="page-break"></div>
					@php ($contadorEtiquetas = 0)
				@endif
			@endfor
	</div>
<script src="{{url('/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<script>
	$(document).ready(function(){
		window.print();
	});

</script>
@endsection
