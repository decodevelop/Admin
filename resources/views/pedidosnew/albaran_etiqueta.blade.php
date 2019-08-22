<link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">
<style>
html {padding:0%; margin:0% 1%;}
*{
	font-size: 18px !important;
	font-family:Arial, sans-serif!important;
	font-weight: bold;
}
hr {
	display: block;
	height: 1px;
	border: 0;
	background-color: #f0f0f0;
	border-top: 1px solid #ccc;
	margin: 1em 0;
	padding: 0;
}
ul {
	list-style: none;
	margin: 0px;
	padding: 0px;
	margin-bottom: 20px;
}
td,th,p{
	font-weight: bold;
}
</style>
<div style="border-bottom: 1px #000 solid;height:98%">
	<div style="clear:both; margin-top: 10px;">
		<b>
			{{$pedido->origen->nombre}}
		</b>
		<span style="margin-left: 50%;">Nº Albarán:
			<span style="font-size: 20px!important;">{{$pedido->numero_albaran}}</span>
		</span>
		<p style="">Fecha: {{ $pedido->fecha_pedido }}</p>
		<hr>
	</div>

	<div style="width: 50%;float:left;text-align:left;">
		<ul>

			<li style="text-transform: uppercase; font-weight: bolder;">Transporte:

				{{$productos[0]->transportista->nombre}}
			</li>
			@if($pedido->origen->referencia=="DT")
				<li>URUPLUS MEDIA SL</li>
				<li>Manteo 23 trasera Guipúzcoa</li>
				<li>España</li>
				<li>Contacto: 943595028</li>
				<li>soporte_productos@deskontalia.es</li>
			@elseif($pedido->origen->referencia=="DA")
				<li>SIGRAS INVERSIONES, S.L.</li>
				<li>C/ Joaquin Febrer Carbó, 15</li>
				<li>Castellón, España</li>
				<li>Contacto: 622816418</li>
				<li>info@decoratualma.com</li>
			@else
				<li>Decowood Europa S.L</li>
				<li>Carretera de la noria 13, Nave 3</li>
				<li>08430, La Roca del Vallés</li>
				<li>Barcelona, España</li>
				<li>Contacto: 628867694</li>
			@endif
		</ul>
	</div>
	<div style="width: 50%;float:left;text-align:right;">
		<ul style="">
			<li> <b>Destinatario</b> </li>
			<li>{{ $pedido->cliente->nombre_envio}}</li>
			@if(is_null($pedido->direccion))
				<li>{{ $pedido->cliente->direcciones[0]->direccion_envio }}</li>
				<li>{{ $pedido->cliente->direcciones[0]->ciudad_envio }}, {{ $pedido->cliente->direcciones[0]->cp_envio }}</li>
			@else
				<li>{{ $pedido->direccion->direccion_envio }}</li>
				<li>{{ $pedido->direccion->ciudad_envio }}, {{ $pedido->direccion->cp_envio }}</li>
			@endif
			<li>{{ $pedido->cliente->email }}</li>
			<li>{{ $pedido->cliente->telefono }}</li>
		</ul>
	</div>
	<div style="width:100%; clear:both;">
		<style type="text/css">
		.tg  {border-collapse:collapse;border-spacing:0;border-color:#ccc;}
		.tg td{font-family:Arial, sans-serif;font-size:14px;padding:5px 2px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#fff;}
		.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:5px 2px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#f0f0f0;}
		.tg .tg-yw4l{vertical-align:top}
		</style>
		<table class="tg" style="width: 100%; clear:both;">
			<tr>
				<th class="tg-yw4l">Paquete</th>
				<th class="tg-yw4l">Cantidad</th>
			</tr>
			@foreach($productos as $producto)
				<tr>
					<td class="tg-yw4l">{{ $producto->nombre_esp }} - {{ $producto->SKU }} - {{$producto->variante}} </td>
					<td class="tg-yw4l">{{ $producto->cantidad }} </td>
				</tr>
			@endforeach
		</table>
		<p>Bultos: @if ($pedido->bultos == 0 || $pedido->bultos == '') 1 @else {{ $pedido->bultos }} @endif </p>
			<?php
			$obs = str_replace("\r\n","<br/>",$pedido->observaciones); ?>
			<p>Observaciones: {!! $obs !!}</p>
			@if(($pedido->precio_envio > 5) && ($pedido->precio_envio < 7) )
				<b>Por favor, enviar el pedido en un mismo transportista</b>
			@endif

		</div>
	</div>
