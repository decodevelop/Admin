@extends('layouts.backend')
@section('titulo','Pedidos > listado')
@section('titulo_h1','Pedidos')

@section('estilos')
<!-- DataTables -->
<link rel="stylesheet" href="{{url('/plugins/datatables/dataTables.bootstrap.css')}}">
<link rel="stylesheet" href="/css/custom.css">
<style>
  table.tabla_producto {
      width: 100%;
  }
  td.nombre-producto {
      width: 54%;
  }
  td.estado-incidencia, td.estado-envio{
    width: 22%;
  }
  .tabla_producto tr {
      border-bottom: 1px solid #d8cfcf;
  }
  table.tabla_producto td {
      padding: 8px 5px;
  }
    th.th-filtro {
        /*width: 9%;*/
    }
    th.th-filtro.th-fecha, th.th-filtro.th-direccion, th.th-filtro.th-cliente {
      width: 9%;
  }
  th.th-filtro.th-total {
      width: 8%;
  }
  th.th-filtro.th-npedido {
      width: 6%;
  }
  .estado-envio span.label .popover-content {
    text-transform: uppercase;
  }
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


    select.form-control.input-sm.filterProducts {
       width: 100% !important;
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
    /*display: block;*/
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
    .modal-body {
        height: 100px;
    }
    .modal-body > div {
        line-height: 34px;
    }
    span.label a {
        color: white !important;
    }
    span.label .popover {
        color: black !important;
    }
    .incidencia i.fa.fa-exclamation-triangle {
        font-size: 10px;
        color: #ab0000;
    }
    #dataTables_pedidos .incidencia:hover {

    /*background-color: rgba(152, 152, 152, 0.1)!important;*/
    color: black;
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
    a.btn.btn-default.btn-md.btn-mrw img {
      -webkit-filter: grayscale(90%);
      -moz-filter: grayscale(90%);
      -ms-filter: grayscale(90%);
      -o-filter: grayscale(90%);
      filter: grayscale(90%);
      filter: Gray();

      -webkit-transition: all 0.2s ease;
      -moz-transition: all 0.2s ease;
      -ms-transition: all 0.2s ease;
      -o-transition: all 0.2s ease;
      transition: all 0.2s ease;
    }
    a.btn.btn-default.btn-md.btn-mrw:hover img {
      -webkit-filter: grayscale(0%);
      -moz-filter: grayscale(0%);
      -ms-filter: grayscale(0%);
      -o-filter: grayscale(0%);
      filter: none;
    }
    div#servicio-mrw {
        font-size: 20px;
        font-weight: bold;
    }
    div#referencia-mrw {
        font-size: 16px;
        font-weight: bold;
        color: #595959;
        padding-top: 4px;
        text-align: right;
    }
</style>
@endsection

@section('contenido')
<section class="content">

  <div class="row">
    <div class="col-md-12">
      <a href="/pedidos/importar_csv" type="button" class="btn btn-block btn-default btn-sm"><i class="fa fa-upload"></i> Importar CSV</a>
      <div class="box DataTableBox">

        <div class="box-body">
          <table class="table table-bordered">
            <thead>

              <tr>
                <th class="th-filtro table-check"></th>
                <th class="th-filtro th-origen">Origen</th>
                <th class="th-filtro th-npedido">Nº Ped</th>
                <th class="th-filtro th-fecha">Fecha (Inicio-Fin)</th>
                <th class="th-filtro th-cliente">Cliente </th>
                <th class="th-filtro th-total">Total </th>
                <th class="th-filtro th-email">Email/Telefono </th>
                <th class="th-filtro th-direccion">Dirección </th>
                <th class="th-filtro th-productos">Productos </th>
                <th class="th-filtro th-enviado text-center" style="width: 10px">Enviado </th>
                <th class="th-filtro th-incidencia text-center" style="width: 10px">Incidencia </th>
                <th class="th-filtro th-opciones text-center" style="width: 125px">Opciones </th>
              </tr>

              <!-- FILTRO -->
              <tr class="filtro-admin">

                <form id="filtros_datatable" method="get">
                  <input class="form-control input-sm" style="display:none;" type="text" name="page" placeholder="pagina" value="{{$listado_pedidos->currentPage()}}">
                  <th class="table-check"><input type="checkbox" class="flat-red check-all" name='check_all' value='all'></th>

                  <!-- FILTRO ORIGEN -->
                  <th>
                    <input name="origen_referencia" type="text" style="display:none">
  									<select class="form-control input-sm filterProducts selectpicker" data-live-search="true" data-width="70px" name="o_origen_referencia" title="origen" multiple>
                      @foreach ($origenes as $origen)
                        <option value="{{$origen->referencia}}" title="{{$origen->referencia}}" {{(@$_GET['o_origen_referencia']==$origen->referencia) ? 'selected': ''}}>{{$origen->nombre}}</option>
                      @endforeach
                      <option value="">Todos</option>
  									</select>
  								</th>
                  <!-- END FILTRO ORIGEN -->

                  <!-- FILTRO N PEDIDO -->
                  <th class="input-npedido"><input class="form-control input-sm filterProducts" type="text" name="numero_pedido" placeholder="Nº" value=""></th>
                  <!-- END FILTRO N PEDIDO -->

                  <!-- FILTRO FECHA INICIO FIN -->
                  <th>
                    <div style="width: 100%; display:inline-block;">
  										<input class="form-control input-sm filterProducts" type="date" name="fecha_pedido" placeholder="Desde" value="">
  									</div>
  									<div style="width: 100%; display:inline-block;">
  										<input class="form-control input-sm filterProducts" type="date" name="fecha_pedido_fin" placeholder="Hasta" value="">
  									</div>
                  </th>
                  <!-- END FILTRO FECHA INICIO FIN -->

                  <!-- FILTRO CLIENTE  -->
                  <th>
                    <input class="form-control input-sm filterProducts" type="text" name="cliente" placeholder="Cliente" value="">
                  </th>
                  <!-- END FILTRO CLIENTE -->

                  <!-- FILTRO PRECIO -->
                  <th><input class="form-control input-sm filterProducts" type="number" name="precio" placeholder="precio" step="any" value=""></th>
                  <!-- END FILTRO PRECIO -->

                  <!-- FILTRO PRECIO -->
                  <th>
                    <input class="form-control input-sm filterProducts" type="text" name="correo_comprador" placeholder="Email" value="">
                    <input class="form-control input-sm filterProducts" type="text" name="telefono_comprador" placeholder="teléfono" value="">
                  </th>
                  <!-- END FILTRO PRECIO -->

                  <!-- FILTRO DIRECCION -->
                  <th>
                    <input class="form-control input-sm filterProducts" type="text" name="direccion_envio" placeholder="Dirección envío" value="">
                  </th>
                  <!-- END FILTRO DIRECCION -->

                  <!-- FILTRO PRODUCTO -->
                  <th>
                    <input class="form-control input-sm filterProducts" type="text" name="nombre_producto" placeholder="nombre producto" value="">
                  </th>
                  <!-- END FILTRO PRODUCTO -->

                  <!-- FILTRO ENVIADOS -->
                  <th>
  									<select class="form-control input-sm filterProducts estado_envio" name="estado_envio">
                      <option value="">Todas</option>
                      <option value="1" {{(@$_GET['estado_envio']=='1') ? 'selected': ''}}>Enviado</option>
                      <option value="0" {{(@$_GET['estado_envio']=='0') ? 'selected': ''}}>No enviado</option>
                      <option value="5" {{(@$_GET['estado_envio']=='5') ? 'selected': ''}}>+5 días</option>
                      <option value="10" {{(@$_GET['estado_envio']=='10') ? 'selected': ''}}>+10 días</option>
                      <option value="20" {{(@$_GET['estado_envio']=='20') ? 'selected': ''}}>+20 días</option>
                      <option value="30" {{(@$_GET['estado_envio']=='30') ? 'selected': ''}}>+30 días</option>
                    </select>
  								</th>
                  <!-- END FILTRO ENVIADOS -->

                  <!-- FILTRO INCIDENCIAS -->
                  <th>
  								  <select class="form-control input-sm filterProducts estado_incidencia" name="estado_incidencia">
                      <option value="">Todas</option>
                      <option value="1" {{(@$_GET['estado_incidencia']=='1') ? 'selected': ''}}>Abierta</option>
                      <option value="2" {{(@$_GET['estado_incidencia']=='2') ? 'selected': ''}}>Cerrada</option>
                    </select>
  								</th>
                  <!-- END FILTRO INCIDENCIAS -->

                  <th>
  									<button type="submit" class="btn btn-default btn-sm">FILTRAR</button>
  								</th>
                </form>
              </tr>
              <!-- END FILTRO -->

            </thead>

            <tbody id="dataTables_pedidos">

              @forelse($listado_pedidos as $key => $pedido)
                @php
                  $incidencia = array();
                @endphp

                @foreach ($pedido->productos as $producto)

                  @forelse ($producto->productos_incidencias as $incidencia_p)
                    @if ($incidencia_p->incidencia->estado == 1)
                      @php
                        array_push($incidencia, 1);
                      @endphp
                      @break
                    @elseif ($incidencia_p->incidencia->estado == 2)
                      @php
                        array_push($incidencia, 2);
                      @endphp
                    @endif

                  @empty

                  @endforelse

                @endforeach


              <tr class="ver_pedido num-{{$pedido->id}} @if(in_array(1,$incidencia))incidencia @endif">

                <!-- CHECKBOX PEDIDO -->
                <td class="table-check num-{{ $pedido->id }} ">
                  <input type="checkbox" class="flat-red" name='pedido' value='{{ $pedido->id }}'>
                </td>
                <!-- END CHECKBOX PEDIDO -->

                <!-- Origen del pedido -->
                <td><span><a href="#" onclick="return false" title="" data-toggle="popover" data-trigger="hover" data-content="{{$pedido->origen->nombre}}">
                    {{ $pedido->origen->referencia }}
                </a></span></td>
                <!-- END Origen del pedido -->

                <!-- NUMERO PEDIDO -->
                <td>{{ $pedido->numero_pedido }}</td>
                <!-- END NUMERO PEDIDO -->

                <!-- FECHA PEDIDO -->
                <td>{{ $pedido->fecha_pedido}}</td>
                <!-- END FECHA PEDIDO -->

                <!-- CLIENTE -->
                <td>{{ $pedido->cliente->nombre_apellidos }}</td>
                <!-- END CLIENTE -->

                <!-- TOTAL PRODUCTO -->
                <td class="text-center">{{$pedido->total}}</td>
                <!-- END TOTAL PRODUCTO -->

                <!-- TELEFONO-EMAIL -->
                <td class="table-email" style="width: 10px">
                  @if($pedido->cliente->email)
                    <a onclick="return false" href="" title="Correo electrónico" data-toggle="popover" data-placement="bottom" class="btn btn-default btn-md btn-pops" data-content="{{$pedido->cliente->email}}"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                  @endif
                  @if($pedido->cliente->telefono)
                  <a onclick="return false" href="" title="Teléfono" data-toggle="popover" data-placement="top" class="btn btn-default btn-md btn-pops" data-content="{{ $pedido->cliente->telefono }}"><i class="fa fa-phone-square" aria-hidden="true"></i></a>
                  @endif
                </td>
                <!-- END TELEFONO-EMAIL -->

                <!-- Direccion y observaciones -->
                @if($pedido->observaciones)
  								<td><a href="#" onclick="return false" title="Observaciones" data-toggle="popover" data-trigger="hover" data-content="{{$pedido->observaciones}}">
                    <div>{{ $pedido->cliente->direccion->pais_envio.' - '.$pedido->cliente->direccion->ciudad_envio.' - '.$pedido->cliente->direccion->direccion_envio }}</div>
                  </a></td>
                @else
                  <td>{{ $pedido->cliente->direccion->pais_envio.' - '.$pedido->cliente->direccion->ciudad_envio.' - '.$pedido->cliente->direccion->direccion_envio }}</td>
                @endif
                <!-- END Direccion y observaciones -->

                <!-- PRODUCTOS PEDIDOS -->
                <td class="productos" colspan="3">
                  <table class="tabla_producto">
                  @foreach ($pedido->productos as $producto)
                  @if(($producto->transportista->nombre == $paginaTransportista) || is_null($paginaTransportista))
                      <tr class="producto-pedido-{{$producto->id}}">
                        <td class="nombre-producto">{{ $producto->nombre_esp }}({{ $producto->cantidad }})
                          @if ($producto->albaran_generado==1)
                            <i class="fa fa-file-pdf-o" aria-hidden="true" style="background: #c3c3c3;color: #000000;padding: 3px;border-radius: 17%;border: 1px solid #00a65a;"></i>
                          @endif
                        </td>

                <!-- END PRODUCTOS PEDIDOS -->

                <!-- ESTADO ENVIO -->
                    <td  class="estado-envio fecha-envio-{{$pedido->id}} fecha-envio-producto-{{$producto->id}}">
                      @if ($producto->estado_envio > 1)
                        <span class="label label-danger">
                          <a href="#" onclick="return false" title="Transportista" data-toggle="popover" data-trigger="hover" data-content="{{$producto->transportista->nombre}}">
                            +{{$producto->estado_envio}} Días
                          </a>
                        </span>
                      @elseif ($producto->estado_envio == 0)
                        <span class="label label-danger">
                          <a href="#" onclick="return false" title="Transportista" data-toggle="popover" data-trigger="hover" data-content="{{$producto->transportista->nombre}}">
                            no enviado
                          </a>
                        </span>
                      @elseif ($producto->estado_envio == 1)
                        <span class="label label-success">
                          <a href="#" onclick="return false" title="Transportista" data-toggle="popover" data-trigger="hover" data-content="{{$producto->transportista->nombre}}">
                            @if($producto->fecha_envio)
                              {{$producto->fecha_envio}}
                            @else
                              enviado
                            @endif
                          </a>
                        </span>
                      @endif
                      </td>
                      <!-- END ESTADO ENVIOO -->

                <!-- INCIDENCIA -->
                    <td class="estado-incidencia">
                      @forelse ($producto->productos_incidencias as $incidencia_p)
                        @if ($incidencia_p->incidencia->estado == 1)
                          <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                        @elseif ($incidencia_p->incidencia->estado == 2)
                          <i class="fa fa-wrench" aria-hidden="true"></i>
                        @endif
                      @empty
                      @endforelse
                    </td>

                <!-- END INCIDENCIA -->


                    </tr>
                  @endif
                  @endforeach
                  </table>
                  @if(!is_null($paginaTransportista))

                      <a title="Generar {{$paginaTransportista}}" style="margin-top: 10px;"  id="boton-mrw_{{$pedido->id}}" type="button" class="btn btn-default btn-md btn-mrw"><img src="/img/pedidos/{{$paginaTransportista}}.png" alt="{{$paginaTransportista}}" style="width: 60px;"></a>

                  @endif
                </td>


                <!-- OPCIONES -->
                <td class="text-center botones-pedidos" style="width: 12%;">
                  <button title="Aceptar envio" style="margin: 2px;" id="enviar-pedido_{{ $pedido->id }}" type="button" class="btn btn-default btn-md"><i class="fa fa-truck" aria-hidden="true"></i></button>
                  <a title="Ver detalles" style="margin: 2px;"  href="/pedidos/detalle/{{$pedido->id }}" id="aceptar-{{ $pedido->id }}" type="button" class="btn btn-default btn-md"><i class="fa fa-eye" aria-hidden="true"></i></a>
                  <a title="Modificar" style="margin: 2px;"  href="/pedidos/modificar/{{$pedido->id }}" type="button" class="btn btn-primary btn-md modificar_pedido">  <i class="fa fa-edit"></i></a>
                  <a title="Generar albaran" style="margin: 2px;"  href="/pedidos/albaran/{{$pedido->id }}" id="albaran-{{ $pedido->id }}" type="button" class="btn btn-default btn-md"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                  <a title="Duplicar" style="margin: 2px;"  href="/pedidos/duplicar/{{$pedido->id }}" id="duplicar-pedido_{{ $pedido->numero_pedido }}" type="button" class="btn btn-default btn-md"><i class="fa fa-files-o" aria-hidden="true"></i></a>
                  <button title="Eliminar"  style="margin: 2px;" id="eliminar-pedido_{{ $pedido->id }}" type="button" class="btn btn-github btn-md"><i class="fa fa-trash" aria-hidden="true"></i></button>

                  <hr style="margin-top: 5px;margin-bottom: 5px;">
                  <div class="input-group" style="width: 130px;margin: auto;margin-top: 5px;">
                    <input type="number" min="0" id="value-bultos_{{ $pedido->id }}" class="form-control" placeholder="Bultos" name="bultos" value="{{$pedido->bultos}}">
                    <div class="input-group-btn">
                      <button id="set-bultos_{{ $pedido->id }}" class="btn btn-default" title="Bultos" type="submit"><i class="fa fa-archive" aria-hidden="true"></i></button>
                    </div>
                  </div>
                </td>
                <!-- END OPCIONES -->
              </tr>
              @empty
                <p>No hay datos.</p>
              @endforelse
            </tbody>
          </table>
        </div>
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

        @include('pedidosnew.form_mrw')
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script src="{{url('/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{url('/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script>

/* bootstrap crear pops */
  $('[data-toggle="popover"]').popover();
  $(document).ready(function(){
    $('[name="o_origen_referencia"]').change(function(){
      $('[name="origen_referencia"]').val($('[name="o_origen_referencia"]').val());
      //$('#debuger').val($('[name="o_o_csv"]').val());
    });

    /* Eliminar pedido */
  	$('[id^="eliminar-pedido_"]').click(function(){
  		var idped = $( this ).attr("id").split("_")[1];
  		apprise('Eliminar definitivamente el pedido?', {'verify':true}, function(r){
  			if(r){
          $('.loader-dw').show();

  				$.ajax({
  					url: "/pedidos/eliminar/"+idped,
  					method: "POST",
  				data: { "_token": "{{ csrf_token() }}", id: idped}
  				}).done(function(mensaje){
  					//alert( "Pedido enviado: " + mensaje );
            $('.loader-dw').hide();
            $('.num-'+idped).fadeOut('slow','linear',function(){this.remove()});
  					apprise(mensaje);
  				});

  			} // final if
  		});
  	});

    /* Al clicar sobre enviar pedido, realizamos un ajax para mararlo como enviado, con previa confirmación. */
  	$('[id^="enviar-pedido_"]').click(function(){
  		var numped = $( this ).attr("id").split("_")[1];
  		apprise('Marcar pedido como enviado?', {'verify':true}, function(r){
  			if(r){
  				apprise('Deseas enviar notificación al cliente?', {'verify':true}, function(r){
            $('.loader-dw').show();
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
                $('.loader-dw').hide();
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


                $('.loader-dw').hide();
                apprise(mensaje);
  						});
  					}
  				});

  			} // final if
  		});
  	});

    /* cambiar bultos pedido */
    $('[id^="set-bultos_"]').click(function(){
      var idped = $( this ).attr("id").split("_")[1];
      var value = $( '#value-bultos_'+idped ).val();
        $('.loader-dw').show();
      $.ajax({
        url: "/pedidos/crear_observacion_bultos/"+idped,
        method: "POST",
      data: { "_token": "{{ csrf_token() }}", id: idped, bultos:value}
      }).done(function(mensaje){
        //alert( "Pedido enviado: " + mensaje );
        $('.loader-dw').hide();
        apprise(mensaje);
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

    $('[id^="boton-mrw_"]').click(function(){
      var idped = $( this ).attr("id").split("_")[1];
      //var value = $( '#value-bultos_'+idped ).val();
        $('.loader-dw').show();
      $.ajax({
        url: "/pedidos/transportista/{{$paginaTransportista}}/csv/"+idped+"/false",
        method: "GET",
      }).done(function(pedido){
        var a_csv = JSON.parse(pedido);
         //apprise(a_csv.bultos);
         $('#servicio-mrw').empty();
         $('#referencia-mrw').empty();

         if(a_csv.peso > 5){
           $('#servicio-mrw').append("Urgente 19 Expedición");
         }else{
            $('#servicio-mrw').append("Ecommerce");
         }
         $('#form-mrw').attr('action',"/pedidos/transportista/{{$paginaTransportista}}/csv/"+idped);
         $('#referencia-mrw').append(a_csv.referencia_envio);
          $("#nombre-mrw").val(a_csv.nombre_apellido);
          $("#direccion-mrw").val(a_csv.direccion);
          $("#ciudad-mrw").val(a_csv.poblacion);
          $("#cp-mrw").val(a_csv.cp);
          //$("#provincia-mrw").val(a_csv.);
          $("#telefono-mrw").val(a_csv.telefono);
          $("#pais-mrw").val(a_csv.codigo_pais);
          $("#bultos-mrw").val(a_csv.bultos);

          $("#kg-mrw").val(a_csv.peso);
          $("#fecha-mrw").val(a_csv.fecha_recogida);
         $('.loader-dw').hide();
         $('#modal-mrw').modal();
      });

    });
  });
</script>
@endsection
