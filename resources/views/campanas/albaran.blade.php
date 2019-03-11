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
<div style="margin:0px 15px">
  <div style="clear:both; margin-top: 31px;text-align:center">
    <h1 style="font-size: 20px !important;">PACKING LIST</h1>
  <hr>
  </div>

  <div style="clear:both; margin-top: 10px;">
    <span>Nº packing list
    	{{$palet->referencia}}
    </span>
    <p style="">{{$campana->referencia}}</p>
    <hr>
  </div>

	<div style="width: 50%;float:left;text-align:left;">
		<ul>

			<li style="text-transform: uppercase; font-weight: bolder;">Dirección:</li>


				<li>{{$campana->nombre_envio}}</li>
				<li>{{$campana->direccion_envio}}</li>
				<li>{{$campana->cp_envio}}, {{$campana->ciudad_envio}}</li>
				<li>{{$campana->estado_envio}}, {{$campana->pais_envio}}</li>

		</ul>

	</div>

  <div style="clear:both; margin-top: 10px;">
    <hr>
    <p style="">Cantidad articulos: {{$palet->productos_palets->sum('cantidad')}} </p>
    <p style="">Cantidad bultos: {{$palet->productos_palets->sum('cantidad')}} </p>
		<p style="">Tamaño palet: {{$palet->tamano}}m </p>
    <hr>
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
		<th class="tg-yw4l">Referencia proveedor</th>
		<th class="tg-yw4l">Descripcion</th>
    <th class="tg-yw4l">Codigo EAN</th>
		<th class="tg-yw4l">Cantidad</th>
	  </tr>
	  @foreach ($palet->productos_palets as $productos_palets)
	  <tr>
    <td class="tg-yw4l">{{$productos_palets->producto->producto->referencia}} </td>
		<td class="tg-yw4l">{{$productos_palets->producto->producto->nombre}} </td>
    <td class="tg-yw4l">{{$productos_palets->producto->producto->ean}} </td>
		<td class="tg-yw4l">{{ $productos_palets->cantidad }} </td>
	  </tr>
	  @endforeach
	</table>


	</div>
</div>
