<?php
use App\Pedidos_wix_importados;
?>
@extends('layouts.backend')
@section('titulo','Pedidos > listado')
@section('titulo_h1','Pedidos')

@section('estilos')
<!-- DataTables -->
<link rel="stylesheet" href="{{url('/plugins/datatables/dataTables.bootstrap.css')}}">
<link rel="stylesheet" href="/css/custom.css">
<style>
  .label-custom {
      background-color: #f4f4f4;
      color: #444;
      border: 1px solid #dddddd;
  }
  #dataTables_pedidos tr:hover {
      border-left: 1px solid #bcb83c;
      border-right: 1px solid #bcb83c;
      background-color: rgba(243,236,18,0.5);
      cursor: pointer;
  }
  #dataTables_pedidos .incidencia {
  	/*background-color: rgba(255, 0, 0, 0.42);*/
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
  .th-fecha, th:nth-child(7),th:nth-child(9){
     width: 10% !important;
  }
  .input-npedido{
     width: 6% !important;
  }
  th:nth-child(7) {
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
      color: #fb3434 !important;
      width: 38px !important;
      height: 34px !important;
  }
  .button-close i {
      display: none;
  }
  .button-close::before{
    font-weight: normal;
    font-family: 'FontAwesome';
    content: '\f00d';
  }
  #dataTables_pedidos tr:hover {
  border-left: 1px solid #000000;
  border-right: 1px solid #000000;
  background-color: rgba(126, 135, 138, 0.16);
  cursor: pointer;
  }
  .modal-body {
      height: 65px;
  }
  .modal-body > div {
      line-height: 34px;
  }
  #dataTables_pedidos .espera:hover {
      border-left: 1px solid #00a65a;
      border-right: 1px solid #00a65a;
  }
  #dataTables_pedidos .espera {
      color: #006f00;
      background: #d4e4d4;
  }
  textarea[name=mensaje_incidencia], textarea[name=gestion_incidencia] {
  width: 100%;
  margin: 0px !important;
  }
</style>
@endsection

@section('contenido')
<section class="content">
  @if (\Session::has('mensaje'))
  	<div class="pad margin no-print">
        <div class="callout callout-info" style="margin-bottom: 0!important;">
          <h4><i class="fa fa-info"></i> OK!</h4>
          {!! \Session::get('mensaje') !!}
        </div>
      </div>
  @endif
	<div class="row">
		<div class="col-md-12">
			<div class="box DataTableBox">
				<div class="box-header with-border">
				  <h3 class="box-title">Listado de pedidos</h3>
				  <div class="box-tools pull-right">
					<a href="{{url('/importar_csv')}}" type="button" class="btn btn-block btn-default btn-sm"><i class="fa fa-upload"></i> Importar CSV</a>
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
                <th style="width: 10px">Cliente </th>
								<th style="width: 250px !important">Producto afectado </th>
								<th style="width: 10px">Datos </th>
								<th class="text-center" style="width: auto">Opciones </th>
							</tr>

              <tr class="filtro-admin">
							<form id="filtros_datatable" method="get">
							<input class="form-control input-sm" style="display:none;" type="text" name="page" placeholder="pagina" value="{{$listado_pedidos->currentPage()}}">
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

                <th style="width: 10px"><input class="form-control input-sm filterProducts" type="text" name="cliente_facturacion" placeholder="Cliente" value="{{@$_GET['cliente_facturacion']}}"></th>


								</th>
								<th style="width: auto">
                  <div style="display:inline-block;">

										<input class="form-control input-sm filterProducts" type="date" name="fecha_pedido" placeholder="Desde" value="{{@$_GET['fecha_pedido']}}">
									</div>
									<div style="display:inline-block;">

										<input class="form-control input-sm filterProducts" type="date" name="fecha_pedido_fin" placeholder="Hasta" value="{{@$_GET['fecha_pedido_fin']}}">
									</div>
                </th>
								<th style="width: 10px"><input class="form-control input-sm filterProducts" type="text" name="correo_comprador" placeholder="Email" value="{{@$_GET['correo_comprador']}}"></th>
								<th style="width: auto">
									<button type="submit" class="btn btn-primary btn-sm">FILTRAR</button>
								</th>
							</form>
							</tr>

						</thead>
						<tbody id="dataTables_pedidos">

							@forelse($listado_pedidos as $key => $pedidos)
              <?php
                $id_productos = explode(',',$pedidos->id);
              ?>
							<tr class="ver_pedido num-{{ $id_productos[0] }} @if($pedidos->estado_incidencia==1)incidencia @elseif($pedidos->estado_incidencia==2) espera @endif ">
								<td class="table-check num-{{ $id_productos[0] }}">
									<input type="checkbox" class="flat-red" name='pedido' value='{{ $id_productos[0] }}'>
								</td>
								<td>{{ $pedidos->o_csv }}</td>
								<td><a href="/pedidos/detalle/{{$id_productos[0]}}" target="ventana" class="iframe-ventana">{{ $pedidos->numero_pedido }}</a></td>

                <td>{{$pedidos->cliente_facturacion}}</td>

								<td class="productos">
								<?php $productos = explode(',',$pedidos->nombre_producto);
									  $cantidad_productos = explode(',',$pedidos->cantidad_producto); ?>
								@foreach($productos as $key => $producto)
								{{ @$producto }}({{ @$cantidad_productos[$key] }})<hr>
								@endforeach
								</td>
								<td class="table-email" style="width: 10px">
                  <div style="width:100%;margin-bottom: 10px;">
                  <span class="label label-custom">{{$pedidos->fecha_pedido}}</span>
                  </div>
                  @if($pedidos->correo_comprador)
                  <a onclick="return false" href="" title="Correo electrónico" data-toggle="popover" data-placement="bottom" class="btn btn-default btn-md btn-pops" data-content="{{ $pedidos->correo_comprador }}"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                  @endif
                  @if($pedidos->telefono_comprador)
                  <a onclick="return false" href="" title="Teléfono" data-toggle="popover" data-placement="top" class="btn btn-default btn-md btn-pops" data-content="{{ $pedidos->telefono_comprador }}"><i class="fa fa-phone-square" aria-hidden="true"></i></a>
                  @endif
                </td>
                <!--@if($pedidos->observaciones)
								<td><a href="#" onclick="return false" title="Observaciones" data-toggle="popover" data-trigger="hover" data-content="{{$pedidos->observaciones}}"><div>{{ $pedidos->pais_envio.' - '.$pedidos->ciudad_envio.' - '.$pedidos->direccion_envio }}</div></a></td>
                @else
                <td>{{ $pedidos->pais_envio.' - '.$pedidos->ciudad_envio.' - '.$pedidos->direccion_envio }}</td>
              @endif-->


									<td class="text-center botones-pedidos" style="width: auto;">
                    <form id="form_incidencia" method="post">
      							 {{ csrf_field() }}
      							 <div style="display:none">
      							<label>Estado</label>
      								<select name="estado_incidencia" id="desplegable_estado_{{$id_productos[0]}}">
      								  <option value="1" {{ ($pedidos->estado_incidencia==1) ? 'selected' : '' }}>Abierta</option>
      								  <option value="0" {{ ($pedidos->estado_incidencia==0) ? 'selected' : '' }}>Cerrada</option>
                        <option value="2" {{ ($pedidos->estado_incidencia==2) ? 'selected' : '' }}>Resuelta</option>
      								</select>
      							</div>
                    <div style="display:none">
                      <input type="text" name="productos_incidencia" id="productos_incidencia_{{$id_productos[0]}}" value="{{$pedidos->id}}">
                    </div>
      								<div class="col-xs-4 motivo-incidencia">
                        <div class="col-xs-12">
                          <label>Motivo de la incidencia</label>
                          <label class="hidden">Motivo inciden.</label>
                        </div>
      								  <div class="col-xs-12">
                          <select name="desplegable_mensaje_incidencia" id="desplegable_mensaje_incidencia_{{$id_productos[0]}}">
          									<option value='0'> --- </option>
                            <option value="1" {{ (preg_match('/^1/' , $pedidos->mensaje_incidencia)) ? 'selected' : '' }}>Rotura en transporte</option>
          								  <option value="2" {{ (preg_match('/^2/' , $pedidos->mensaje_incidencia)) ? 'selected' : '' }}>Rotura en transporte por mal embalaje</option>
          									<option value="3" {{ (preg_match('/^3/' , $pedidos->mensaje_incidencia)) ? 'selected' : '' }}>Error de referencia</option>
          									<option value="4" {{ (preg_match('/^4/' , $pedidos->mensaje_incidencia)) ? 'selected' : '' }}>Producto incompleto</option>
          									<option value="5" {{ (preg_match('/^5/' , $pedidos->mensaje_incidencia)) ? 'selected' : '' }}>Error de producción</option>
          									<option value="6" {{ (preg_match('/^6/' , $pedidos->mensaje_incidencia)) ? 'selected' : '' }}>Fallo de documentación</option>
          									<option value="7" {{ (preg_match('/^7/' , $pedidos->mensaje_incidencia)) ? 'selected' : '' }}>Entrega fuera de plazo</option>
          									<option value="8" {{ (preg_match('/^8/' , $pedidos->mensaje_incidencia)) ? 'selected' : '' }}>No se ajusta a las necesidades del cliente</option>
          									<option value="9" {{ (preg_match('/^9/' , $pedidos->mensaje_incidencia)) ? 'selected' : '' }}>Error de compra</option>
          									<option value="Otros" {{  (preg_match('/^Otros/' , $pedidos->mensaje_incidencia)) ? 'selected' : '' }}>Otros</option>
          								</select>
                        </div>
                        <?php


                          if($pedidos->mensaje_incidencia != ''){
                            $i_mens = explode(": ",$pedidos->mensaje_incidencia);
                          }else{
                            $i_mens = array();
                            $i_mens[0]='';
                            $i_mens[1]='';
                          }

                          if(sizeof($i_mens)<2){
                            $i_mens[1]='';
                          }


                        ?>
							          <div class="col-xs-12">
                          <textarea class="callout callout-default" name="mensaje_incidencia" id="mensaje_incidencia_{{$id_productos[0]}}" style="width: 100%;margin-top:10px;margin-bottom:0px;display:block">{{$i_mens[1]}}</textarea>
                        </div>
      							  </div>
      								<div class="col-xs-4">
                        <div class="col-xs-12">
                          <label>Gestión</label>
                        </div>
        								<div class="col-xs-12">
                          <select name="desplegable_gestion_incidencia" id="desplegable_gestion_incidencia_{{$id_productos[0]}}">
          									<option value='0'> --- </option>
                            <option value="1" {{ (preg_match('/^1/' , $pedidos->gestion_incidencia)) ? 'selected' : '' }}>Devolución</option>
          									<option value="2" {{ (preg_match('/^2/' , $pedidos->gestion_incidencia)) ? 'selected' : '' }}>Reposición</option>
          									<option value="3" {{ (preg_match('/^3/' , $pedidos->gestion_incidencia)) ? 'selected' : '' }}>Descuento por tara</option>
          									<option value="Otros" {{ (preg_match('/^Otros/' , $pedidos->gestion_incidencia)) ? 'selected' : '' }}>Otros</option>
          								</select>
                        </div>
                        <?php
                        if($pedidos->gestion_incidencia != ''){
                          $i_ges = explode(": ",$pedidos->gestion_incidencia);
                        }else{
                          $i_ges = array();
                          $i_ges[0]='';
                          $i_ges[1]='';
                        }

                        if(sizeof($i_ges)<2){
                          $i_ges[1]='';
                        }

                        ?>
        								<div class="col-xs-12">
                          <textarea class="callout callout-default" name="gestion_incidencia" id="gestion_incidencia_{{$id_productos[0]}}" style="width: 100%;margin-top:10px;margin-bottom:0px;display:block">{{$i_ges[1]}}</textarea>
                        </div>
      								</div>
                      <div class="descuento col-xs-4">
                        <div class="col-xs-12">
                          <label>Cantidad a descontar</label>
                          <label class="hidden">Cant. descontar</label>
                        </div>
                        <div class="col-xs-12">
                          <input type="number" style="width: 50%; text-align: right; margin-top: 10px; margin-bottom: 0px;" name="historial_incidencia" id="historial_incidencia_{{$id_productos[0]}}" placeholder="Cantidad a descontar" step="any" value="{{$pedidos->historial_incidencia}}" />
                        </div>

                      <div class="col-md-6 col-xs-12">
      								    <button title="Actualizar" class="btn btn-info btn-sm btn-actualizar" id="btn_actualizar_{{$id_productos[0]}}" onclick="return false">Actualizar
                            <!--  <i class="fa fa-save"></i> -->
                          </button>
      								    <button title="Actualizar" class="hidden btn btn-info btn-sm btn-actualizar" id="btn_actualizar_{{$id_productos[0]}}" onclick="return false">
                             <i class="fa fa-save"></i>
                          </button>

                      </div>
                      <div class="col-md-6 col-xs-12">
                        <button title="Resolver" class="btn btn-success btn-sm btn-resolver" onclick="return false" id="btn_resolver_{{$id_productos[0]}}">Resolver
                          <!--  <i class="fa fa-check"></i> -->
                        </button>
                        <button title="Resolver" class="hidden btn btn-success btn-sm btn-resolver" onclick="return false" id="btn_resolver_{{$id_productos[0]}}">
                          <i class="fa fa-check"></i>
                        </button>
                      </div>
                    </div>
                      <!--button class="btn btn-danger btn-sm" id="btn_marcar_{{$id_productos[0]}}" type="submit">Marcar incidencia</button-->
                      <button class="btn btn-info btn-sm" style="display:none" id="btn_enviar_{{$id_productos[0]}}" type="submit">enviar incidencia</button>

                    </form>
									</td>
								</td>

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
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#iframe-pedido" id="abrir-iframe" tyle="display:none">abrir iframe</button>
<div class="modal fade" id="iframe-pedido" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <iframe name=ventana></iframe>
        </div>
      </div>
    </div>
  </div>

<style>
  iframe {
      width: 100%;
      height: 100%;
  }
  #iframe-pedido .modal-body {
      height: 100%;
  }
  #iframe-pedido .modal-content {
      height: 100%;
  }
  #iframe-pedido .modal-dialog.modal-lg {
      height: 100%;
      width: 90%;
  }
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

  td.productos {
    display: table-cell;
    width: 20%!important;
  }

  .botones-pedidos button {
    margin-bottom: 14px;
    width: 100%!important;
    min-width: max-content!important;
    font-size: 10.5pt;
    padding-top: 5px;
    padding-bottom: 5px;
  }

  .descuento.col-xs-4 .col-md-6.col-xs-12 {
    padding-left: 5px;
    padding-right: 5px;
  }
  #form_incidencia label {
  float: left;
  color: black;
  /* display: inline; */
  margin-left: 0%;
  /* margin: auto; */
  }
  #form_incidencia select {
  width: 100%;
  margin-bottom: 10px;
  color: black;
  }
  form#form_incidencia {
  padding-top: 13px;
  }
  button.btn.btn-default.btn-sm, .btn-info, .btn-success, .btn-danger {
  width: 150px !important;
  margin: 10px;
  }
  textarea[name=gestion_incidencia] , textarea[name=mensaje_incidencia] {
    color: black;
    margin-bottom: 5px !important;
    margin-left: 17%;
    padding: 0px;
    margin-top: 0px !important;
  }
  input[name=historial_incidencia] {
      color: black;
      margin-bottom: 5px !important;
      float: left;
      padding: 0px;
      margin-top: 0px !important;
  }
  th:nth-child(6) {
  width: 8% !important;
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
          <div class="col-md-4">
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
          <div class="col-md-4">
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
          <div class="col-md-4">
            <a class="btn btn-default" id="filtro-enviar" href="/pedidos/filtro/2017/01">Filtrar</a>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
$(document).ready(function(){
  $('[name="o_o_csv"]').change(function(){
    $('[name="o_csv"]').val($('[name="o_o_csv"]').val());
  });
  //Botón resolver insidencia
  $('[id*="btn_resolver_"]').click(function(){
    var id = $( this ).attr("id").split("_")[2];
    var productos_incidencia = $('#productos_incidencia_'+id).val();
    var estado_incidencia = '2';
    var mensaje_incidencia = $('#desplegable_mensaje_incidencia_'+id).val()+': '+$('#mensaje_incidencia_'+id).val();
    var gestion_incidencia = $('#desplegable_gestion_incidencia_'+id).val()+': '+$('#gestion_incidencia_'+id).val();
    var historial_incidencia = $('#historial_incidencia_'+id).val();

    //$('#desplegable_estado_'+id).val('1');

    //$('#btn_enviar_'+id).click();
    $('.loader-dw').show();
    $.ajax({
			url: "/incidencias/actualizar/"+id,
			method: "POST",
		  data: { "_token": "{{ csrf_token() }}", productos_incidencia: productos_incidencia, estado_incidencia: estado_incidencia, mensaje_incidencia: mensaje_incidencia, gestion_incidencia: gestion_incidencia, historial_incidencia: historial_incidencia }
		}).done(function(mensaje){
			//alert( "Pedido enviado: " + mensaje );
      $('.loader-dw').hide();
      $('.num-'+id).addClass('espera');
			apprise(mensaje);
		});
  });
  //Botón actualizar insidencia
  $('[id*="btn_actualizar_"]').click(function(){
    var id = $( this ).attr("id").split("_")[2];
    var productos_incidencia = $('#productos_incidencia_'+id).val();
    var estado_incidencia = '1';
    var mensaje_incidencia = $('#desplegable_mensaje_incidencia_'+id).val()+': '+$('#mensaje_incidencia_'+id).val();
    var gestion_incidencia = $('#desplegable_gestion_incidencia_'+id).val()+': '+$('#gestion_incidencia_'+id).val();
    var historial_incidencia = $('#historial_incidencia_'+id).val();
    //$('#desplegable_estado_'+id).val('1');

    //$('#btn_enviar_'+id).click();
    $('.loader-dw').show();
    $.ajax({
			url: "/incidencias/actualizar/"+id,
			method: "POST",
		  data: { "_token": "{{ csrf_token() }}", productos_incidencia: productos_incidencia, estado_incidencia: estado_incidencia, mensaje_incidencia: mensaje_incidencia, gestion_incidencia: gestion_incidencia, historial_incidencia: historial_incidencia }
		}).done(function(mensaje){
			//alert( "Pedido enviado: " + mensaje );
      $('.loader-dw').hide();
      $('.num-'+id).removeClass('espera');
			apprise(mensaje);
		});


  });

  /* Generamos la ruta para el filtro de meses cuando se seleccione un mes o dia */
  $('#filtro-mes').change(function(){
    var filtroAno = $('#filtro-ano').val();
    var filtroMes = $('#filtro-mes').val();
    var urlFiltro = "/pedidos/filtro/"+ filtroAno + "/" + filtroMes;
    $('#filtro-enviar').attr('href', urlFiltro)
  });
  $('#filtro-ano').change(function(){
    var filtroAno = $('#filtro-ano').val();
    var filtroMes = $('#filtro-mes').val();
    var urlFiltro = "/pedidos/filtro/"+ filtroAno + "/" + filtroMes;
    $('#filtro-enviar').attr('href', urlFiltro)
  });
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


	/* Actualizar bultos */
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
				if(!$(this).is(":checked")) {
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

});
</script>
@endsection
