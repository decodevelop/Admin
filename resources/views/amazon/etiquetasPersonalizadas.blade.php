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
<!--
    <div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('0657968287938', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Talavera</p>
<p class="referenciaProductoBarcode">REF: 0657968287938 </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968104006', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Zorro</p>
<p class="referenciaProductoBarcode">REF:0657968104006  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968867802', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Tiburón</p>
<p class="referenciaProductoBarcode">REF:0657968867802  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968971608', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Indian</p>
<p class="referenciaProductoBarcode">REF:0657968971608  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968310469', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Topo</p>
<p class="referenciaProductoBarcode">REF:0657968310469  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968775107', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Geométrico</p>
<p class="referenciaProductoBarcode">REF:0657968775107  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968560598', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Monkey</p>
<p class="referenciaProductoBarcode">REF:0657968560598  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968742147', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Anclas Marineras</p>
<p class="referenciaProductoBarcode">REF:0657968742147  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968806108', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Anclas Burdeos</p>
<p class="referenciaProductoBarcode">REF:0657968806108  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968703933', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Pasley</p>
<p class="referenciaProductoBarcode">REF:0657968703933  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968788565', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Mapache</p>
<p class="referenciaProductoBarcode">REF:0657968788565  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968682788', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Rayo</p>
<p class="referenciaProductoBarcode">REF:0657968682788  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968623101', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Rayas Burdeos</p>
<p class="referenciaProductoBarcode">REF:0657968623101  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968969728', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Rayas Army</p>
<p class="referenciaProductoBarcode">REF:0657968969728  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968685147', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Flechas</p>
<p class="referenciaProductoBarcode">REF:0657968685147  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968306745', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Cuadros</p>
<p class="referenciaProductoBarcode">REF:0657968306745  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968251137', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín oso</p>
<p class="referenciaProductoBarcode">REF:0657968251137  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968194465', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Cactus</p>
<p class="referenciaProductoBarcode">REF:0657968194465  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968638600', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Flamingo</p>
<p class="referenciaProductoBarcode">REF:0657968638600  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968245983', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Pato</p>
<p class="referenciaProductoBarcode">REF:0657968245983  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968687660', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Constelación</p>
<p class="referenciaProductoBarcode">REF:0657968687660  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968957381', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Pingüino</p>
<p class="referenciaProductoBarcode">REF:0657968957381  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968478718', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Cruces</p>
<p class="referenciaProductoBarcode">REF:0657968478718  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968998094', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Cangrejo</p>
<p class="referenciaProductoBarcode">REF:0657968998094  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968232259', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Aguacate</p>
<p class="referenciaProductoBarcode">REF:0657968232259  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968923317', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Cuadrado</p>
<p class="referenciaProductoBarcode">REF:0657968923317  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968953628', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Cuadrado Fuxia</p>
<p class="referenciaProductoBarcode">REF:0657968953628  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968882324', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Cuadado Kaki</p>
<p class="referenciaProductoBarcode">REF:0657968882324  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968929258', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Cross</p>
<p class="referenciaProductoBarcode">REF:0657968929258  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968240490', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Abeto</p>
<p class="referenciaProductoBarcode">REF:0657968240490  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968531116', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Nautico</p>
<p class="referenciaProductoBarcode">REF:0657968531116  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968953796', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Rombos</p>
<p class="referenciaProductoBarcode">REF:0657968953796  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968698468', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Plátano</p>
<p class="referenciaProductoBarcode">REF:0657968698468  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968915640', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Trebol</p>
<p class="referenciaProductoBarcode">REF:0657968915640  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968947986', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Hojas</p>
<p class="referenciaProductoBarcode">REF:0657968947986  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968843455', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Espina</p>
<p class="referenciaProductoBarcode">REF:0657968843455  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968780750', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Reno</p>
<p class="referenciaProductoBarcode">REF:0657968780750  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968016149', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Topos Burdeos</p>
<p class="referenciaProductoBarcode">REF:0657968016149  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968119208', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Triangulos</p>
<p class="referenciaProductoBarcode">REF:0657968119208  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968603349', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Azul Vigoré</p>
<p class="referenciaProductoBarcode">REF:0657968603349  </p>
</div>
</div>

<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968367784', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Gris Vigoré</p>
<p class="referenciaProductoBarcode">REF:0657968367784  </p>
</div>
</div>
<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('657968367784', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Lunares</p>
<p class="referenciaProductoBarcode">REF:0657968689466  </p>
</div>
</div>


-->
<div>
<div class="containerImgBarcode">
<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('0657968687660', 'EAN13')}}" class="imgBarcode" alt="barcode" />
<p class="nombreProductoBarcode">Calcetín Abeto</p>
<p class="referenciaProductoBarcode">REF:0657968687660</p>
</div>
</div>
	</div>
<script src="{{url('/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<script>
	$(document).ready(function(){
		window.print();
	});

</script>
@endsection
