<link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">
<style>
html {padding:0%; margin:0% 1%;}
*{
	font-size: 12px !important;
	font-family:Arial, sans-serif!important;
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
</style>
<div style="height:49%; border-bottom: 1px #000 solid;">
<div style="clear:both; margin-top: 10px;">
<b>
	Amazon
</b>
<span style="margin-left: 50%;">Nº Albarán: {{$pedido[0]["numero_pedido"]}}</span>
<p style="">Fecha: {{$pedido[0]["fecha"]}}</p>
	<hr>
</div>

	<div style="width: 50%;float:left;text-align:left;">
		<ul>
			<li style="text-transform: uppercase; font-weight: bolder;">Transporte: {{$pedido[0]["transporte"]}}</li>
				<li>Decowood Europa S.L</li>
				<li>Carretera de la noria 13, Nave 3</li>
				<li>08430, La Roca del Vallés</li>
				<li>Barcelona, España</li>
				<li>Contacto: 628867694</li>
		</ul>
	</div>
	<div style="width: 50%;float:left;text-align:right;">
		<ul style="">
			<li> <b>Destinatario</b> </li>
			<li>{{ $pedido[0]["cliente_envio"] }}</li>
			<li>{{ $pedido[0]["calle"] }}</li>
			<li>{{ $pedido[0]["ciudad"] }}</li>

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
	  @foreach($pedido as $key => $producto)
	  <tr>
		<td class="tg-yw4l">{{ $producto["PO"] }}  </td>
		<td class="tg-yw4l">{{ $producto["cantidad_producto"] }} </td>
	  </tr>
	  @endforeach
	</table>
	<p style="font-weight: 100;">Bultos: @if ($pedido[0]["bultos"] == 0 || $pedido[0]["bultos"] == '') 1 @else {{ $pedido[0]["bultos"] }} @endif </p>
	<p style="font-weight: 100;">Observaciones: {{ $pedido[0]["observaciones"] }}</p>
	</div>
</div>
<!-- copia 2 --><!-- copia 2 --><!-- copia 2 --><!-- copia 2 --><!-- copia 2 --><!-- copia 2 -->
<div style="height:49%; border-bottom: 1px #000 solid;">
<div style="clear:both; margin-top: 10px;">
<b>
	Amazon
</b>
<span style="margin-left: 50%;">Nº Albarán: {{$pedido[0]["numero_pedido"]}}</span>
<p style="">Fecha: {{$pedido[0]["fecha"]}}</p>
	<hr>
</div>

	<div style="width: 50%;float:left;text-align:left;">
		<ul>
			<li style="text-transform: uppercase; font-weight: bolder;">Transporte: {{$pedido[0]["transporte"]}}</li>
				<li>Decowood Europa S.L</li>
				<li>Carretera de la noria 13, Nave 3</li>
				<li>08430, La Roca del Vallés</li>
				<li>Barcelona, España</li>
				<li>Contacto: 628867694</li>
		</ul>
	</div>
	<div style="width: 50%;float:left;text-align:right;">
		<ul style="">
			<li> <b>Destinatario</b> </li>
			<li>{{ $pedido[0]["cliente_envio"] }}</li>
			<li>{{ $pedido[0]["calle"] }}</li>
			<li>{{ $pedido[0]["ciudad"] }}</li>

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
	  @foreach($pedido as $key => $producto)
	  <tr>
		<td class="tg-yw4l">{{ $producto["PO"] }}  </td>
		<td class="tg-yw4l">{{ $producto["cantidad_producto"] }} </td>
	  </tr>
	  @endforeach
	</table>
	<p style="font-weight: 100;">Bultos: @if ($pedido[0]["bultos"] == 0 || $pedido[0]["bultos"] == '') 1 @else {{ $pedido[0]["bultos"] }} @endif </p>
	<p style="font-weight: 100;">Observaciones: {{ $pedido[0]["observaciones"] }}</p>
	</div>
</div>
