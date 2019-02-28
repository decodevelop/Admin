<?php
use App\Pedidos_wix_importados;
?>
@extends('layouts.backend')
@section('titulo','Pedidos > listado')
@section('titulo_h1','Pedidos a enviar a '.$nombre_proveedor)

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
            <a href="/pedidos/aviso_proveedor/{{$nombre_proveedor}}" class="btn btn-success btn-md" >ENVIAR ALBARANES <i class="fa fa-envelope"></i></a>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">

					<table class="table table-bordered">
						<thead>

							<tr>
								<th style="width: 10px" class="table-check"></th>
								<th style="width: 10px">Origen</th>
								<th style="width: 10px">Nº Ped</th>
								<th style="width: 10px" class="th-fecha">Fecha (Inicio-Fin)</th>
								<th style="width: 10px">Cliente </th>
								<th style="width: 250px !important">Prod. </th>
								<th style="width: 10px">Total </th>
								<th style="width: 10px">Email </th>
								<th style="width: 10px">Dirección </th>
								<th class="text-center" style="width: 125px">Opciones </th>
							</tr>



						</thead>
						<tbody id="dataTables_pedidos">

							@forelse($listado_pedidos as $pedido)

							<tr class="ver_pedido num-{{ $pedido->id }}">
								<td class="table-check num-{{ $pedido->id }}">
									<input type="checkbox" class="flat-red" name='pedido' value='{{ $pedido->id }}'>
								</td>
								<td>{{ $pedido->origen->referencia }}</td>
								<td><a href="/pedidos/detalle/{{$pedido->id}}" target="ventana" class="iframe-ventana">{{ $pedido->numero_pedido }}</a></td>
								<td>{{ $pedido->fecha_pedido  }}</td>
								<td>{{ $pedido->cliente->nombre_apellidos }}</td>
								<td class="productos">
                    @foreach ($pedido->productos as $producto)
                      @if($producto->proveedor->nombre == $nombre_proveedor)
                        {{ $producto->nombre_esp }}({{ $producto->cantidad}})
                      @endif
                    @endforeach


								</td>
								<td class="text-center"><?php echo preg_replace('/([\d,]+.\d{2})\d+/', '$1', $pedido->total) ?></td>
								<td class="table-email" style="width: 10px">
                  @if($pedido->cliente->email_facturacion)
                  <a onclick="return false" href="" title="Correo electrónico" data-toggle="popover" data-placement="bottom" class="btn btn-default btn-md btn-pops" data-content="{{ $pedido->cliente->email_facturacion }}"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                  @endif
                  @if($pedido->cliente->telefono_facturacion)
                  <a onclick="return false" href="" title="Teléfono" data-toggle="popover" data-placement="top" class="btn btn-default btn-md btn-pops" data-content="{{ $pedido->cliente->telefono_facturacion }}"><i class="fa fa-phone-square" aria-hidden="true"></i></a>
                  @endif
                </td>
                @if($pedido->observaciones)
								<td><a href="#" onclick="return false" title="Observaciones" data-toggle="popover" data-trigger="hover" data-content="{{$pedido->observaciones}}"><div>{{ $pedido->cliente->direccion->pais_envio.' - '.$pedido->cliente->direccion->ciudad_envio.' - '.$pedido->cliente->direccion->direccion_envio }}</div></a></td>
                @else
                <td>{{ $pedido->cliente->direccion->pais_envio.' - '.$pedido->cliente->direccion->ciudad_envio.' - '.$pedido->cliente->direccion->direccion_envio }}</td>
                @endif

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
                      <button id="set-bultos_{{ $pedido->id }}" class="btn btn-default" type="submit"><i class="fa fa-archive" aria-hidden="true"></i></button>
                    </div>
                  </div>
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
  iframe {
    width: 100%;
    height: 100%;
  }
  </style>
@endsection

@section('scripts')
<!-- DataTables -->

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

  });
</script>
@endsection
