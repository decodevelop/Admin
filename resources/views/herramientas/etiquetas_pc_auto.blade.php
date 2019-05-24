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
	    height: 2.5cm;
	    margin: 0cm 0.7cm 0cm 0cm;
	    align-items: center;
			float: left;
			/*border-radius: 50%;
			border: 1px solid black;*/
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
	 .nombreProducto {
		 position: relative;
		 bottom: -44px;
		 font-size: 10px;
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

			@for ($i = 0; $i < 33; $i++)

				@if($contadorEtiquetas==0)
					<div class="" style="margin:1cm 0 0 0.7cm;display: block;float: left;">

				@endif
				@php ($contadorEtiquetas = $contadorEtiquetas+1)
				<div class="containerImgBarcode">
					<div class="container-qr">

            <p>PC1</p>

					</div>

			    </div>
				@if($contadorEtiquetas>=33)
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
