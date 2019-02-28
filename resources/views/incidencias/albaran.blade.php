
<style>
html {padding:0%; margin:0% 1%;}
*{ font-size: 12px !important;}
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
	@if($pedido["o_csv"]=="CA")
		Cajasdemadera.com
	@elseif($pedido["o_csv"]=="CB")
		Cabeceros.com
	@elseif($pedido["o_csv"]=="MA")
		Decowood > Milanuncios
	@elseif($pedido["o_csv"]=="DT")
		Descontalia
	@elseif($pedido["o_csv"]=="IC")
		Decowood > Icommerce
	@elseif($pedido["o_csv"]=="MV")
		Decowood > MiVinteriores
	@elseif($pedido["o_csv"]=="HT")
		hogarterapia
	@elseif($pedido["o_csv"]=="DA")
		Decoratualma
	@elseif($pedido["o_csv"]=="AS")
		Decowood > otros
	@elseif($pedido["o_csv"]=="CJ")
		Cojines.com
	@elseif($pedido["o_csv"]=="WL")
		Decowood > walapop
	@endif
</b>
<span style="margin-left: 50%;">Nº: {{ $pedido["o_csv"] }}{{ $pedido["numero_pedido"] }}</span>
<p style="">Fecha: {{ $pedido["fecha_pedido"] }}</p>
	<hr>
</div>

	<div style="width: 50%;float:left;text-align:left;">
		<ul>
			<li><b>Transporte:</b>
			<?php 
			if($pedido["o_csv"] == "CA" && $pedido["total"] <= "250"){
				echo "NACEX"; 
			} else if($pedido["o_csv"] == "CA" && $pedido["total"] >= "250"){
				echo "Transparets";
			} else if($pedido["o_csv"] == "CB") {
			echo "Tipsa";} else {
				echo $pedido["metodo_entrega"];
			}			
			?>
			</li>
			@if($pedido["o_csv"]=="DT")
				<li>URUPLUS MEDIA SL</li>
				<li>Manteo 23 trasera Guipúzcoa</li>
				<li>España</li>
				<li>Contacto: 943595028</li>
				<li>soporte_productos@deskontalia.es</li>
			@elseif($pedido["o_csv"]=="DA")
				<li>SIGRAS INVERSIONES, S.L.</li>
				<li>C/ Joaquin Febrer Carbó, 15</li>
				<li>Castellón, España</li>
				<li>Contacto: 622816418</li>
				<li>info@decoratualma.com</li>
			@else
				<li>Decowood Europa S.L</li>
				<li>Carretera de la noria 13, Nau 3</li>
				<li>0843, La Roca del Vallés</li>
				<li>Barcelona, España</li>
				<li>Contacto: 628867694</li>
			@endif
		</ul>
	</div>
	<div style="width: 50%;float:left;text-align:right;">
		<ul style="">
			<li> <b>Destinatario</b> </li>
			<li>{{ $pedido["cliente_envio"] }}</li>
			<li>{{ $pedido["direccion_envio"] }}</li>
			<li>{{ $pedido["ciudad_envio"] }}, {{ $pedido["cp_envio"] }}</li>
			<li>{{ $pedido["correo_comprador"] }}</li>
			<li>{{ $pedido["telefono_comprador"] }}</li>
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
	  @foreach($productos as $key => $producto)
	  <tr>
		<td class="tg-yw4l">{{ $producto["nombre_producto"] }} - {{ $producto["sku_producto"] }} - {{ $producto["variante_producto"] }} </td>
		<td class="tg-yw4l">{{ $producto["cantidad_producto"] }} </td>	
	  </tr>
	  @endforeach
	</table>
	</div>
</div>
<!-- copia 2 -->
<div style="height:49%; border-bottom: 1px #000 solid;">
<div style="clear:both; margin-top: 10px;">
<b>
	@if($pedido["o_csv"]=="CA")
		Cajasdemadera.com
	@elseif($pedido["o_csv"]=="CB")
		Cabeceros.com
	@elseif($pedido["o_csv"]=="MA")
		Decowood > Milanuncios
	@elseif($pedido["o_csv"]=="DT")
		Descontalia
	@elseif($pedido["o_csv"]=="IC")
		Decowood > Icommerce
	@elseif($pedido["o_csv"]=="MV")
		Decowood > MiVinteriores
	@elseif($pedido["o_csv"]=="HT")
		hogarterapia
	@elseif($pedido["o_csv"]=="DA")
		Decoratualma
	@elseif($pedido["o_csv"]=="AS")
		Decowood > otros
	@elseif($pedido["o_csv"]=="CJ")
		Cojines.com
	@elseif($pedido["o_csv"]=="WL")
		Decowood > walapop
	@endif
</b>
<span style="margin-left: 50%;">Nº: {{ $pedido["o_csv"] }}{{ $pedido["numero_pedido"] }}</span>
<p style="">Fecha: {{ $pedido["fecha_pedido"] }}</p>
	<hr>
</div>

	<div style="width: 50%;float:left;text-align:left;">
		<ul>
			<li><b>Transporte:</b>
			<?php 
			if($pedido["o_csv"] == "CA" && $pedido["total"] <= "250"){
				echo "NACEX"; 
			} else if($pedido["o_csv"] == "CA" && $pedido["total"] >= "250"){
				echo "Transparets";
			} else if($pedido["o_csv"] == "CB") {
			echo "Tipsa";} else {
				echo $pedido["metodo_entrega"];
			}			
			?>
			</li>
			@if($pedido["o_csv"]=="DT")
				<li>URUPLUS MEDIA SL</li>
				<li>Manteo 23 trasera Guipúzcoa</li>
				<li>España</li>
				<li>Contacto: 943595028</li>
				<li>soporte_productos@deskontalia.es</li>
			@elseif($pedido["o_csv"]=="DA")
				<li>SIGRAS INVERSIONES, S.L.</li>
				<li>C/ Joaquin Febrer Carbó, 15</li>
				<li>Castellón, España</li>
				<li>Contacto: 622816418</li>
				<li>info@decoratualma.com</li>
			@else
				<li>Decowood Europa S.L</li>
				<li>Carretera de la noria 13, Nau 3</li>
				<li>0843, La Roca del Vallés</li>
				<li>Barcelona, España</li>
				<li>Contacto: 628867694</li>
			@endif
		</ul>
	</div>
	<div style="width: 50%;float:left;text-align:right;">
		<ul style="">
			<li> <b>Destinatario</b> </li>
			<li>{{ $pedido["cliente_envio"] }}</li>
			<li>{{ $pedido["direccion_envio"] }}</li>
			<li>{{ $pedido["ciudad_envio"] }}, {{ $pedido["cp_envio"] }}</li>
			<li>{{ $pedido["correo_comprador"] }}</li>
			<li>{{ $pedido["telefono_comprador"] }}</li>
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
	  @foreach($productos as $key => $producto)
	  <tr>
		<td class="tg-yw4l">{{ $producto["nombre_producto"] }} - {{ $producto["sku_producto"] }} - {{ $producto["variante_producto"] }} </td>
		<td class="tg-yw4l">{{ $producto["cantidad_producto"] }} </td>	
	  </tr>
	  @endforeach
	</table>
	</div>
</div>
