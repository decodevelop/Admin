<?php
use App\Pedidos_wix_importados;
?>
@extends('layouts.backend')
@section('titulo','Pedidos > listado')
@section('titulo_h1','Filtro por mes')

@section('estilos')
<!-- DataTables -->
<link rel="stylesheet" href="{{url('/plugins/datatables/dataTables.bootstrap.css')}}">
<link rel="stylesheet" href="/css/custom.css">
<style>
#dataTables_pedidos tr:hover {
    border-left: 1px solid #bcb83c;
    border-right: 1px solid #bcb83c;
    background-color: rgba(243,236,18,0.5);
    cursor: pointer;
}
#dataTables_pedidos .incidencia {
	/*background-color: rgba(255, 0, 0, 0.42);*/
    color: #ff0000;
	transition: all 0.5s;
}

#dataTables_pedidos .incidencia:hover {
	border-left:2px solid #ff0000;
	border-right:2px solid #ff0000;
	background-color: rgba(255, 0, 0, 0.10) !important;
}
.productos > hr {
	margin: 2px;
    border-color: #f4f4f4;
}
.productos {
	font-size: 12px;
}
.productos > hr:last-child {
	display:none;
}
.subrallado {
    background-color: rgba(243,236,18,0.25);
    cursor: pointer;
}

.ver_pedido:nth-child(2n) {
	background-color: #f9fafc;
}

/* MEJORAS 24/7/2017 */

th {
   vertical-align: middle !important;

   text-align: center;
}

td {
   text-align: center;
   vertical-align: middle !important;
}
.filtro-admin{
   background: #deecf9;
}

th:nth-child(5) > div {
   display: block !important;
   width: 100% !important;
   text-align: left;
   margin-right: -80px !important;
}
.th-fecha, th:nth-child(6),th:nth-child(9){
   width: 10% !important;
}
.input-npedido{
   width: 6% !important;
}
th:nth-child(6) {
   width: 700px !important;
}


input.form-control.input-sm.filterProducts {
    background: #f1f8ff;
    border: 1px solid #b4d6ea;
    border-radius: 4px;
    margin: 5px;
    margin-left: 1px;
}
select.form-control.input-sm.filterProducts {
   width: 70px;
   border-radius: 4px !important;
background: #f1f8ff;
border: 1px solid #b4d6ea;
}
button#set-bultos_4433 {
margin: 9px 6px 0px 9px !important;
}
.botones-pedidos {
    min-width: 140px;
}

td.productos {
display: block;
text-align: center;
padding: 0px;
vertical-align: middle !important;
overflow: auto;
width: 250px !important;
height: 120px;
margin: auto;
}

.botones-pedidos button {
    width: 38px;
}
i.fa.fa-edit {
width: 12px;
}
.button-close {
    background: #f4f4f4 !important;
    color: red !important;
    width: 38px !important;
    height: 34px !important;
}
.button-close i {
    display: none;
}
.button-close::before{
  font-weight: bold;
  content: 'X';
}
#dataTables_pedidos tr:hover {
border-left: 1px solid #000000;
border-right: 1px solid #000000;
background-color: rgba(126, 135, 138, 0.16);
cursor: pointer;
}
button#mostrar_tr {
padding: 3px 14px;
font-size: 12px;
margin-bottom: 3px;
color: white;
background: #3c8dbc;
}
input#numero {
background: #f1f8ff;
border: 1px solid #b4d6ea;
border-radius: 4px;
margin: 5px;
margin-left: 1px;
width: 10%;
}
div.buscador {
text-align: left;
}
ul.pagination.pagination-sm.no-margin.pull-right {
text-align: right;
margin: auto !important;
}
div.buscador {
  margin: -3px 0px 10px;
  padding: 0px;
}
.pagination {
display: inline-block;
padding-left: 0;
margin: auto;
border-radius: 4px;
padding:0px;
font-size: 12px;
}
ul.pagination.pagination-sm.no-margin.pull-right {
text-align: right;
margin: auto !important;
padding: 0px;
}
.modal-body {
    height: 65px;
}
.modal-body > div {
    line-height: 34px;
}
</style>
@endsection

@section('contenido')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box DataTableBox">
				<div class="box-header with-border">
				  <h3 class="box-title">Listado de pedidos</h3>
				  <div class="box-tools pull-right">
					<a href="{{url('/importar_csv')}}" type="button" class="btn btn-block btn-default btn-sm"><i class="fa fa-upload"></i> Importar CSV</a>
          <button data-toggle="modal" data-target="#modal-filtro-mes" type="button" class="btn btn-block btn-default btn-sm"><i class="fa fa-calendar" aria-hidden="true"></i> Filtrar y exportar por mes</button>
				  </div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
				<p> Filtros aplicados:
				@foreach($_GET as $key => $filter)
				@if($filter!="")
				<span class="label bg-blue">{{$key .":".$filter}}</span>
				@endif
				@endforeach
				</p>
					<table class="table table-bordered">
						<thead>

							<tr>
								<th style="width: 10px" class="table-check"></th>
								<th style="width: 10px">Origen</th>
								<th style="width: 10px">Nº Ped</th>
								<th style="width: 10px" class="th-fecha">Fecha</th>
								<th style="width: 10px">Cliente </th>
								<th style="width: 250px !important">Prod. </th>
								<th style="width: 10px">Total </th>
								<th style="width: 10px">Email </th>
								<th style="width: 10px">Dirección </th>
								<th class="text-center" style="width: 10px">Incidencia </th>
								<th class="text-center" style="width: 10px">Enviado </th>
								<th class="text-center" style="width: 125px">Opciones </th>
							</tr>

              <tr class="filtro-admin">
							<form id="filtros_datatable" method="get">
							<input class="form-control input-sm" style="display:none;" type="text" name="page" placeholder="pagina" value="{{$listado_pedidos->currentPage()}}">
              <div class="buscador col-md-8">
                <input type="number" id="numero">
                <button id="mostrar_tr" class="btn">Ver</button>
              </div>
              <ul class="pagination pagination-sm no-margin pull-right col-md-4">
    						<!-- $paginacion->links('pedidos.pagination',["test" => "test"] ) -->
    						{!! $listado_pedidos->appends($_GET)->links() !!}

    					</ul>
                <th style="width: 10px" class="table-check"><input type="checkbox" class="flat-red check-all" name='check_all' value='all'></th>
								<th style="width: 10px">
                  <input name="o_csv" type="text" style="display:none">

									<select class="form-control input-sm filterProducts selectpicker" data-live-search="true" data-width="70px" name="o_o_csv" title="origen" multiple>
                    @foreach ($o_csv_array as $csv_key => $csv_font)
                      <option value="{{$csv_key}}" title="{{$csv_key}}" {{(@$_GET['o_csv']==$csv_key) ? 'selected': ''}}>{{$csv_font}}</option>
                    @endforeach
                    <option value="">Todos</option>
									</select>
								</th>
								<th style="width: 10px" class="input-npedido"><input class="form-control input-sm filterProducts" type="text" name="numero_pedido" placeholder="Nº" value="{{@$_GET['numero_pedido']}}"></th>
								<th style="width: 10px">
                  <div style="width: 49%; display:inline-block; display:none !important;">

										<input class="form-control input-sm filterProducts" type="date" name="fecha_pedido" placeholder="Desde" value="{{@$_GET['fecha_pedido']}}">
									</div>
									<div style="width: 49%; display:inline-block; display:none !important;">

										<input class="form-control input-sm filterProducts" type="date" name="fecha_pedido_fin" placeholder="Hasta" value="{{@$_GET['fecha_pedido_fin']}}">
									</div>

								</th>
								<th style="width: 10px"><input class="form-control input-sm filterProducts" type="text" name="cliente_facturacion" placeholder="Cliente" value="{{@$_GET['cliente_facturacion']}}"></th>
								<th style="width: 250px"></th>
								<th style="width: auto"></th>
								<th style="width: 10px"><input class="form-control input-sm filterProducts" type="text" name="correo_comprador" placeholder="Email" value="{{@$_GET['correo_comprador']}}"></th>
								<th style="width: 10px"><input class="form-control input-sm filterProducts" type="text" name="direccion_envio" placeholder="Dirección envío" value="{{@$_GET['direccion_envio']}}"></th>
								<th style="width: 10px">
									<select class="form-control input-sm filterProducts" type="text" name="estado_incidencia">
										<option value="1" {{(@$_GET['estado_incidencia']=='1') ? 'selected': ''}}>Abierta</option>
										<option value="0" {{(@$_GET['estado_incidencia']=='0') ? 'selected': ''}}>Cerrada</option>
										<option value="" {{(@$_GET['estado_incidencia']!='0' && (@$_GET['estado_incidencia']!='1')) ? 'selected': ''}}>Todas</option>
									</select>
								</th>
								<th style="width: 10px">


                  <select class="form-control input-sm filterProducts" type="text" name="enviado">
										<option value="1" {{(@$_GET['enviado']=='1') ? 'selected': ''}}>Enviado</option>
										<option value="0" {{(@$_GET['enviado']=='0') ? 'selected': ''}}>No enviado</option>
										<option value="" {{(@$_GET['enviado']!='0' && (@$_GET['enviado']!='1')) ? 'selected': ''}}>Todos</option>
									</select>

                </th>
								<th style="width: 10px">
									<button type="submit" class="btn btn-primary btn-sm">FILTRAR</button>
								</th>
							</form>
							</tr>

						</thead>
						<tbody id="dataTables_pedidos">

							@forelse($listado_pedidos as $key => $pedidos)
							<tr class="ver_pedido num-{{ $pedidos->id }} @if($pedidos->estado_incidencia==1)incidencia @endif" name="tr[]">
								<td class="table-check num-{{ $pedidos->id }}">
									<input type="checkbox" class="flat-red" name='pedido' value='{{ $pedidos->id }}'>
								</td>
								<td>{{ $pedidos->o_csv }}</td>
								<td>{{ $pedidos->numero_pedido }}</td>
								<td>{{ $pedidos->fecha_pedido  }}</td>
								<td>{{ $pedidos->cliente_facturacion }}</td>
								<td class="productos">
								<?php $productos = explode(',',$pedidos->nombre_producto);
									  $cantidad_productos = explode(',',$pedidos->cantidad_producto); ?>
								@foreach($productos as $key => $producto)
								{{ @$producto }}({{ @$cantidad_productos[$key] }})<hr>
								@endforeach
								</td>
								<td class="text-center"><?php echo preg_replace('/([\d,]+.\d{2})\d+/', '$1', $pedidos->total) ?></td>
								<td class="table-email" style="width: 10px">
                  @if($pedidos->correo_comprador)
                  <a onclick="return false" href="" title="Correo electrónico" data-toggle="popover" data-placement="bottom" class="btn btn-default btn-md btn-pops" data-content="{{ $pedidos->correo_comprador }}"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                  @endif
                  @if($pedidos->telefono_comprador)
                  <a onclick="return false" href="" title="Teléfono" data-toggle="popover" data-placement="top" class="btn btn-default btn-md btn-pops" data-content="{{ $pedidos->telefono_comprador }}"><i class="fa fa-phone-square" aria-hidden="true"></i></a>
                  @endif
                </td>
                @if($pedidos->observaciones)
								<td><a href="#" onclick="return false" title="Observaciones" data-toggle="popover" data-trigger="hover" data-content="{{$pedidos->observaciones}}"><div>{{ $pedidos->pais_envio.' - '.$pedidos->ciudad_envio.' - '.$pedidos->direccion_envio }}</div></a></td>
                @else
                <td>{{ $pedidos->pais_envio.' - '.$pedidos->ciudad_envio.' - '.$pedidos->direccion_envio }}</td>
                @endif

                <td class="text-center">{{ ($pedidos->estado_incidencia==1) ? 'Abierta': 'Cerrada' }}</td>
								@if(!$pedidos->fecha_envio)
									<td class="fecha-envio-{{ $pedidos->id }} text-center"><span class="label label-danger">no enviado</span></td>
									<td class="text-center botones-pedidos" style="width: 12%;">
										<button title="Aceptar envio" style="margin: 2px;" id="enviar-pedido_{{ $pedidos->id }}" type="button" class="btn btn-default btn-md"><i class="fa fa-truck" aria-hidden="true"></i></button>
										<a title="Ver detalles" style="margin: 2px;"  href="/pedidos/detalle/{{$pedidos->id }}" id="aceptar-{{ $pedidos->id }}" type="button" class="btn btn-default btn-md"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    <a title="Modificar" style="margin: 2px;"  href="/pedidos/modificar/{{$pedidos->id }}" type="button" class="btn btn-success btn-md modificar_pedido">  <i class="fa fa-edit"></i></a>
										<a title="Generar albaran" style="margin: 2px;"  href="/pedidos/albaran/{{$pedidos->id }}" id="aceptar-{{ $pedidos->id }}" type="button" class="btn btn-default btn-md"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
										<a title="Duplicar" style="margin: 2px;"  href="/pedidos/duplicar/simple/{{$pedidos->numero_pedido}}/{{$pedidos->o_csv}}" id="duplicar-pedido_{{ $pedidos->numero_pedido }}" type="button" class="btn btn-default btn-md"><i class="fa fa-files-o" aria-hidden="true"></i></a>
                    <button title="Eliminar"  style="margin: 2px;" id="eliminar-pedido_{{ $pedidos->id }}" type="button" class="btn btn-danger btn-md"><i class="fa fa-trash" aria-hidden="true"></i></button>

                  <hr style="margin-top: 5px;margin-bottom: 5px;">
                  <div class="input-group" style="width: 130px;margin: auto;margin-top: 5px;">
                    <input type="number" min="0" id="value-bultos_{{ $pedidos->id }}" class="form-control" placeholder="Bultos" name="bultos" value="{{$pedidos->bultos}}">
                    <div class="input-group-btn">
                      <button id="set-bultos_{{ $pedidos->id }}" class="btn btn-default" type="submit"><i class="fa fa-archive" aria-hidden="true"></i></button>
                    </div>
                  </div>
										<!--button id="set-bultos_{{ $pedidos->id }}" value="0" class="btn btn-default btn-md">Limpiar</button>
										<button id="set-bultos_{{ $pedidos->id }}" value="1" class="btn btn-default btn-md">1B</button>
										<button id="set-bultos_{{ $pedidos->id }}" value="2" class="btn btn-default btn-md">2B</button-->
									</td>
								@else
									<td class=" text-center"><span class="label label-success ">{{ $pedidos->fecha_envio }}</span></td>
									<td class="text-center botones-pedidos" style="width: 12%;">
										<button title="Aceptar envio" style="margin: 2px;" id="enviar-pedido_{{ $pedidos->id }}" type="button" class="btn btn-default btn-md"><i class="fa fa-truck" aria-hidden="true"></i></button>
										<a title="Ver detalles" style="margin: 2px;"  href="/pedidos/detalle/{{$pedidos->id }}" id="aceptar-{{ $pedidos->id }}" type="button" class="btn btn-default btn-md"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    <a title="Modificar" style="margin: 2px;"  href="/pedidos/modificar/{{$pedidos->id }}" type="button" class="btn btn-success btn-md">  <i class="fa fa-edit"></i></a>
										<a title="Generar albaran" style="margin: 2px;"  href="/pedidos/albaran/{{$pedidos->id }}" id="aceptar-{{ $pedidos->id }}" type="button" class="btn btn-default btn-md"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
										<a title="Duplicar" style="margin: 2px;"  href="/pedidos/duplicar/simple/{{$pedidos->numero_pedido}}/{{$pedidos->o_csv}}" id="duplicar-pedido_{{ $pedidos->numero_pedido }}" type="button" class="btn btn-default btn-md"><i class="fa fa-files-o" aria-hidden="true"></i></a>
                    <a title="Eliminar" style="margin: 2px;"  href="/pedidos/eliminar/{{$pedidos->id }}" id="eliminar-pedido_{{ $pedidos->id }}" type="button" class="btn btn-danger btn-md"><i class="fa fa-trash" aria-hidden="true"></i></a>

                  <hr style="margin-top: 5px;margin-bottom: 5px;">
                  <div class="input-group" style="width: 130px;margin: auto;margin-top: 5px;">
                    <input type="number" min="0" id="value-bultos_{{ $pedidos->id }}" class="form-control" placeholder="Bultos" name="bultos" value="{{$pedidos->bultos}}">
                    <div class="input-group-btn">
                      <button id="set-bultos_{{ $pedidos->id }}" class="btn btn-default" type="submit"><i class="fa fa-archive" aria-hidden="true"></i></button>
                    </div>
                  </div>
										<!--button id="set-bultos_{{ $pedidos->id }}" value="0" class="btn btn-default btn-md">Limpiar</button>
										<button id="set-bultos_{{ $pedidos->id }}" value="1" class="btn btn-default btn-md">1B</button>
										<button id="set-bultos_{{ $pedidos->id }}" value="2" class="btn btn-default btn-md">2B</button-->
									</td>
								@endif</td>

							</tr>
							@empty
								<p>No hay datos.</p>
							@endforelse
						</tbody>
					</table>
				</div>

				<!-- /.box-body -->
				<div class="box-footer clearfix">
					<ul class="pagination pagination-sm no-margin pull-right">
						<!-- $paginacion->links('pedidos.pagination',["test" => "test"] ) -->
						{!! $listado_pedidos->appends($_GET)->links() !!}

					</ul>
				</div>
				<div class="box-footer clearfix">
				<button title="Generar albaranes en pdf" id="generar_albaranes_pdf" type="button" class="btn btn-danger btn-xs"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Generar Albaranes</button>
				<button title="Generar Excel" id="generar_excel_pdf" type="button" class="btn btn-success btn-xs"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Generar Excel</button>
				<form id="generar_albaranes_pdf_form" method="post"  action="{{Url(''.'/pedidos/albaranes')}}">
				{{ csrf_field() }}
				<input type="hidden" id="ids" name="ids" value="empty"/>
				</form>
				<form id="generar_excel_form" method="post"  action="{{Url(''.'/pedidos/gen_excel')}}">
				{{ csrf_field() }}
				<input type="hidden" id="ids_e" name="ids" value="empty"/>
				<input type="hidden" id="filters_e" name="filterse" value="empty"/>
				</form>
				</div>

			</div>
        <!-- /.box -->
        </div>
    </div>
    <!-- /.row -->
</section>
<style>
.loader-dw {
  background: rgba(0, 0, 0, 0.68);
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  }
  .load-modal {
    width: 100%;
    height: 100%;
    position: fixed;
}
.load-modal > h1 {
    color: white;
    text-align: center;
    margin-top: -35%;
}
.load-modal > img{
    top: 0;
    bottom: 0;
    position: fixed;
    left: 0;
    right: 0;
    margin: auto;
}

</style>
<div class="loader-dw" style="display:none;">
  <div class="load-modal">
    <img src="/img/loader/loading.gif" alt="Cargando...">
    <h1>Por favor, espere...</h1>
  </div>
</div>
<div class="modal fade" id="modal-filtro-mes" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Filtrar por mes</h4>
        </div>
        <div class="modal-body">
          <div class="col-md-3">
            Mes
            <select id="filtro-mes">
              <option value="01">Enero</option>
              <option value="02">Febrero</option>
              <option value="03">Marzo</option>
              <option value="04">Abril</option>
              <option value="05">Mayo</option>
              <option value="06">Junio</option>
              <option value="07">Julio</option>
              <option value="08">Agosto</option>
              <option value="09">Septiembre</option>
              <option value="10">Octubre</option>
              <option value="11">Noviembre</option>
              <option value="12">Diciembre</option>
            </select>
          </div>
          <div class="col-md-2">
            Año
            <select id="filtro-ano">
               <option value="2017">2017</option>
               <option value="2016">2016</option>
               <option value="2015">2015</option>
               <option value="2014">2014</option>
               <option value="2013">2013</option>
               <option value="2012">2012</option>
            </select>
          </div>
          <div class="col-md-5">
            Origen (solo exportar)
            <select id="filtro-origen">
              <option value="CA">Cajasdemadera.com</option>
              <option value="CB">Cabeceros.com</option>
              <option value="TT">Tetedelit.fr </option>
              <option value="CM">Cajasdemadera.com (manual)</option>
              <option value="CC">Cabeceros.com (manual)</option>
              <option value="TL">Latetedelit.fr (manual)</option>
              <option value="MA">Milanuncios</option>
              <option value="DT">Descontalia</option>
              <option value="IC">Icommerce</option>
              <option value="MV">MiVinteriores</option>
              <option value="HT">Hogarterapia</option>
              <option value="DA">Decoratualma</option>
              <option value="CJ">Cojines.es</option>
              <option value="CO">Cojines.es (Manual)</option>
              <option value="WL">Wallapop</option>
              <option value="AR">Areas</option>
              <option value="AM">Amazon</option>
              <option value="MM">Mercado de María</option>
              <option value="CR">Carrefour</option>
              <option value="AS">Otros</option>
              <option value="">Todos</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <a class="btn btn-default" id="filtro-enviar" href="/pedidos/filtro/2017/01">Filtrar</a>
          <a class="btn btn-success" id="exportar-enviar" href="/pedidos/exportar/2017/01/CA"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar</a>
        </div>
      </div>

    </div>
  </div>
@endsection

@section('scripts')
<!-- DataTables -->

<script src="{{url('/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{url('/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script>
function obtener_variables_fitro(){
  var filtroAno = $('#filtro-ano').val();
  var filtroMes = $('#filtro-mes').val();
  var filtroOrigen = $('#filtro-origen').val();
  var urlFiltro = "/pedidos/filtro/"+ filtroAno + "/" + filtroMes;
  var urlFiltroExportar = "/pedidos/exportar/"+ filtroAno + "/" + filtroMes + "/" + filtroOrigen;
  $('#filtro-enviar').attr('href', urlFiltro);
  $('#exportar-enviar').attr('href', urlFiltroExportar);
}
$(document).ready(function(){
  $('[name="o_o_csv"]').change(function(){
    $('[name="o_csv"]').val($('[name="o_o_csv"]').val());
    //$('#debuger').val($('[name="o_o_csv"]').val());
  });
  /* Generamos la ruta para el filtro de meses cuando se seleccione un mes o dia */
  $('#filtro-mes').change(obtener_variables_fitro);
  $('#filtro-ano').change(obtener_variables_fitro);
  $('#filtro-origen').change(obtener_variables_fitro);


	/* Al clicar sobre enviar pedido, realizamos un ajax para mararlo como enviado, con previa confirmación. */
	$('[id^="enviar-pedido_"]').click(function(){
		var numped = $( this ).attr("id").split("_")[1];
		apprise('Marcar pedido como enviado?', {'verify':true}, function(r){
			if(r){
				apprise('Deseas enviar notificación al cliente?', {'verify':true}, function(r){
					if(r){
						$(this).prop( "disabled", true );
						$.ajax({
							url: "/pedidos/enviar_pedido/"+numped+"?notificar=si",
							cache: false
						}).done(function(msg){
							var resultado = JSON.parse(msg);
							var mensaje = resultado[0];
							var fecha_envio = resultado[1];

							$(".fecha-envio-"+numped+" > span").removeClass("label-danger").addClass("label-success", {duration:500}).html(''+fecha_envio+'');
							//alert( "Pedido enviado: " + mensaje );
							apprise(mensaje);
						});
					} else {
						$(this).prop("disabled", true);
						$.ajax({
							url: "/pedidos/enviar_pedido/"+numped+"?notificar=no",
							cache: false
						}).done(function(msg){
							var resultado = JSON.parse(msg);
							var mensaje = resultado[0];
							var fecha_envio = resultado[1];

							$(".fecha-envio-"+numped+" > span").removeClass("label-danger").addClass("label-success", {duration:500}).html(''+fecha_envio+'');
							//alert( "Pedido enviado: " + mensaje );
							apprise(mensaje);
						});
					}
				});

			} // final if
		});
	});


	/* Eliminar pedido */
	$('[id^="set-bultos_"]').click(function(){
		var idped = $( this ).attr("id").split("_")[1];
		var value = $( '#value-bultos_'+idped ).val();
		$.ajax({
			url: "/pedidos/crear_observacion_bultos/"+idped,
			method: "POST",
		data: { "_token": "{{ csrf_token() }}", id: idped, bultos:value}
		}).done(function(mensaje){
			//alert( "Pedido enviado: " + mensaje );
			apprise(mensaje);
		});
	});

	/* Eliminar pedido */
	$('[id^="eliminar-pedido_"]').click(function(){
		var idped = $( this ).attr("id").split("_")[1];
		apprise('Eliminar definitivamente el pedido?', {'verify':true}, function(r){
			if(r){
				$.ajax({
					url: "/pedidos/eliminar/"+idped,
					method: "POST",
				data: { "_token": "{{ csrf_token() }}", id: idped}
				}).done(function(mensaje){
					//alert( "Pedido enviado: " + mensaje );
					apprise(mensaje);
				});

			} // final if
		});
	});

	/* Al checkear el input global, marcamos todos y desmarcamos al uncheck. */
	$("[name='check_all']").click(function(){
		 if($(this).is(":checked")) {
			//var checkarray = $("[name='pedido']:checked").serializeArray();
			$("[name='pedido']").each(function(){
				if(!$(this).is(":checked") && $(".num-"+$(this).val()).css('display') != 'none') {
					$(".num-"+$(this).val()).addClass("subrallado");
					$(this).click();
				}
            });
			//$("[name='pedido']").click();
		} else {
			$("[name='pedido']").each(function(){
				if($(this).is(":checked")){
					$(".num-"+$(this).val()).removeClass("subrallado");
					$(this).click();
				}
            });
		}
	});

	/* Al seleccionar cada pedido de forma independiente, marcamos y añadimos o eliminamos clase subrallado, */
	$("[name='pedido']").click(function(){
		if($(this).is(":checked")) {
			$(".num-"+$(this).val()).addClass("subrallado");
		} else {
			$(".num-"+$(this).val()).removeClass("subrallado");
		}
	});

	$.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
	/* Al clicar sobre el botón, importamos albaranes marcados ( si los hay ) mediante ajax y retorna un pdf. */
	$("#generar_albaranes_pdf").click(function(){

		 var arrayPedidos = $("[name='pedido']").serializeArray();
		 $("#ids").val(JSON.stringify(arrayPedidos));
		 $("#generar_albaranes_pdf_form").submit();
		 /*$.ajax({
			url: "/pedidos/albaranes",
			type:'POST',
			data:{ ids:arrayPedidos, "_token":"{{ csrf_token() }}" }
		}).done(function(pdf){
			window.open(pdf,'_blank');

		});*/
	});

	$("#generar_excel_pdf").click(function(){

		 var arrayPedidos = $("[name='pedido']").serializeArray();
		 var arrayFiltersE = $(".filterProducts").serializeArray();

		 $("#ids_e").val(JSON.stringify(arrayPedidos));
		 $("#filters_e").val(JSON.stringify(arrayFiltersE));

		 $("#generar_excel_form").submit();
		 /*$.ajax({
			url: "/pedidos/albaranes",
			type:'POST',
			data:{ ids:arrayPedidos, "_token":"{{ csrf_token() }}" }
		}).done(function(pdf){
			window.open(pdf,'_blank');

		});*/
	});

  /* bootstrap crear pops */
  $('[data-toggle="popover"]').popover();

  /**/

  $('.btn-pops').click(function(){

    if($(this).hasClass('button-close')){

      $(this).removeClass('button-close');

    }else{
      $(this).addClass('button-close');
    }

  });

  $("#mostrar_tr").click(function mostrar_num_tr(){

    var fila = $('tr[name="tr[]"]');
    var numero = document.getElementById('numero').value;

    if (numero<1) {
      for (var i = 0; i < fila.length; i++) {
        fila[i].style.display = ""; //si no has puesto un numero o es 0, te los mostrara todos
      }
    }else{
      for (var i = 0; i < fila.length; i++) {
        fila[i].style.display = "none"; //poner contador a 0 para luego poner las filas buscadas
      }
    }

      for (var i = 0; i < numero; i++) {
        fila[i].style.display = ""; //mostrar numero de filas
      }

      return false;
    });



});
</script>
@endsection
