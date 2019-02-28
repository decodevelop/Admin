@extends('layouts.backend')
@section('titulo','Detalles pedido número ('.$detalles_pedido->id.")")
@section('contenido')
@if (\Session::has('mensaje'))
	<div class="pad margin no-print">
      <div class="callout callout-info" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-info"></i> OK!</h4>
        {!! \Session::get('mensaje') !!}
      </div>
    </div>
@endif
<link rel="stylesheet" href="/css/custom.css">
<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i> Detalles de pedido seleccionado  <small class="pull-right">Fecha pedido: {{ $detalles_pedido->fecha_pedido }}</small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">
			<div class="col-sm-4 invoice-col">
				Datos Facturación
				<address>
				<strong>{{ $detalles_pedido->cliente_facturacion }}</strong><br>
				{{ $detalles_pedido->direccion_facturacion }}<br>
				{{ $detalles_pedido->ciudad_facturacion }}, {{ $detalles_pedido->pais_facturacion }} {{ $detalles_pedido->cp_facturacion }}<br>
				Teléfono: {{ $detalles_pedido->telefono_comprador }}<br>
				Correo: {{ $detalles_pedido->correo_comprador }}
				</address>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				Datos envío
				<address>
				<strong>{{ $detalles_pedido->cliente_envio }}</strong><br>
				{{ $detalles_pedido->direccion_envio }}<br>
				{{ $detalles_pedido->ciudad_envio }}, {{ $detalles_pedido->pais_envio }} {{ $detalles_pedido->cp_envio }}<br>
				Teléfono: {{ $detalles_pedido->telefono_comprador }}<br>
				Correo: {{ $detalles_pedido->correo_comprador }}
				</address>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				<b>Factura Nº {{ $detalles_pedido->codigo_factura }}</b><br>
				<b>Nº Pedido PS:</b> {{ $detalles_pedido->numero_pedido_ps }}<br>
				<b>Nº Pedido:</b> {{ $detalles_pedido->numero_pedido }}<br>
				<b>Estado pago:</b> {{ ( strtolower($detalles_pedido->pago) == 'paid') ? 'Pagado' : 'No pagado' }}<br>
				<b>Metodo entrega:</b> {{ $detalles_pedido->metodo_entrega }}<br>
				<b>Forma de pago:</b> {{ $detalles_pedido->forma_de_pago }}<br>
				<b>Estado pedido:</b> {{ ($detalles_pedido->orden_completada=='fullfilled') ? 'Completado' : 'No completado' }}
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->

		<!-- Table row -->
		<div class="row">
		<div class="col-xs-12 table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="table-check"><input type="checkbox" class="flat-red check-all" name='check_all' value='all'></th>
						<th>Nombre Producto</th>
						<th>Variante</th>
						<th>SKU</th>
						<th>Cantidad</th>
						<th>Subtotal</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($productos_pedido as $key => $producto)
						<tr class="select-prd num-{{ $producto->id }} ">
							<td class="table-check num-{{ $producto->id }}"><input type="checkbox" class="flat-red input_id_producto" name='pedido' value='{{ $producto->id }}'/></td>
							<td>{{$producto['nombre_producto']}}</td>
							<td>{{$producto['variante_producto']}}</td>
							<td>{{$producto['sku_producto']}}</td>
							<td>{{$producto['cantidad_producto']}}</td>
							<td>@if ($detalles_pedido->o_csv == 'AM' )
								{{$producto['precio_producto']}}
							@else
								{{($producto['cantidad_producto']*$producto['precio_producto'])}}
							@endif</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<form id="generar_pdf_productos_form" method="post"  action="{{Url(''.'/pedidos/albaran/'.$detalles_pedido->id)}}">
				{{ csrf_field() }}
				<input type="hidden" id="ids" name="ids" value="empty"/>
			</form>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->

		<div class="row">
			<!-- accepted payments column -->

			<div class="col-xs-4">
				<p class="lead">Observaciónes:</p>
				<form id="form_observaciones">
				 {{ csrf_field() }}
				<label>@if($detalles_pedido->observaciones!=NULL || $detalles_pedido->observaciones!='') Activa @else No hay Inactiva @endif</label>
					<textarea class="callout @if($detalles_pedido->observaciones!=NULL || $detalles_pedido->observaciones!='') callout-info  @else  callout-default  @endif" name="mensaje_observacion" form="form_observaciones" style="width: 100%;margin-top:10px;margin-bottom:0px;" placeHolder="No hay ningún mensaje en la db.">@if($detalles_pedido->observaciones!=NULL || $detalles_pedido->observaciones!='') {{$detalles_pedido->observaciones}} @endif</textarea>
					<button class="btn btn-block btn-default btn-sm" type="submit" >Actualizar Observación </button>
				</form>
			</div>
			<div class="col-xs-3">
				<div class="col-xs-12">
				 	<p class="lead"><strong>Bultos<div class="input-group">
						<input type="number" min="0" id="value-bultos_{{$detalles_pedido->id }}" class="form-control" placeholder="Bultos" name="bultos" value="{{$detalles_pedido->bultos}}">
						<div class="input-group-btn">
							<button id="set-bultos_{{ $detalles_pedido->id }}" class="btn btn-default" type="submit"><i class="fa fa-archive" aria-hidden="true"></i></button>
						</div>
					</div></p>
				</div>
				<div class="col-xs-12">
					<a class="btn btn-default" style="display:none" href="/pedidos/duplicar/reposicion/{{$detalles_pedido->numero_pedido}}/{{$detalles_pedido->o_csv}}" id="generar-reposicion" name="generar-reposicion">Reposición</a>
				</div>
			</div>
			<!-- /.col -->
			<div class="col-xs-5">
				<p class="lead">Importe:</p>

				<div class="table-responsive">
					<table class="table">
					<tbody>
						<tr>
							<th style="width:50%">Subtotal:</th>
							<td>{{$detalles_pedido->total-($detalles_pedido->total/100*21)}} €</td>
						</tr>
						<tr>
							<th>IVA aprox.(21%)</th>
							<td>{{$detalles_pedido->total/100*21}} €</td>
						</tr>
						<tr>
							<th>Envio:</th>
							<td>Gratuito</td>
						</tr>
						<tr>
							<th>Total:</th>
							<td>{{$detalles_pedido->total}} €</td>
						</tr>
					</tbody>
					</table>
				</div>
			</div>
			<div class="col-xs-6 " style="margin-bottom: 20px;">

				<div class="panel-group">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" href="#collapse-seguimiento"><p class="lead">Seguimiento:</p></a>
							</h4>
						</div>
						<div id="collapse-seguimiento" class="col-xs-12 panel-collapse collapse in">
							@if (count($seguimiento) > 0)
								@foreach ($seguimiento as $seg)
								<div class="fila-seguimiento col-xs-8 @if($seg->id_usuario == Auth::user()->id) propietario  @endif">
									<div class="seg-left col-xs-3">
										<div class="usuario"> <i class="fa fa-user" aria-hidden="true"></i> {{$usuarios[$seg->id_usuario-1]->apodo}}</div>
										<div class="fecha">{{$seg->created_at}}</div>
									</div>
									<div class="seg-right col-xs-5">
											<div class="comentario">
												<div class="mensaje"> {{$seg->mensaje}} </div>
											</div>
									</div>
								</div>
								@endforeach
							@endif
							<div class="fila-seguimiento col-xs-8">
								<div class="seg-left col-xs-3">
									<div class="usuario"> <i class="fa fa-commenting" aria-hidden="true"></i> {{Auth::user()->apodo}}:</div>
									<div class="fecha"><?php echo date('Y-m-d H:i:s'); ?></div>
								</div>
								<div class="seg-right col-xs-5">
									<form id="form_seguimiento">
										{{ csrf_field() }}
										<div class="comentario">
											<textarea class="callout callout-default" name="comentario_seguimiento" id="comentario_seguimiento" style=""></textarea>
										</div>
										<div class="enviar"><button type="submit" id="enviar_seguimiento">Enviar</button></div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>



			</div>

			<div class="col-xs-6 " style="margin-bottom: 20px;">

				<div class="panel-group">
				  <div class="panel panel-default">
				    <div class="panel-heading {{ ($detalles_pedido->estado_incidencia==1) ? 'warning' : '' }}">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" href="#collapse-incidencia"><p class="lead">Historial incidencia:</p></a>
				      </h4>
				    </div>
				    <div id="collapse-incidencia" class="panel-collapse collapse">

							<form id="form_incidencia">
							 {{ csrf_field() }}


								 	<label>Producto afectado</label>
							 <div style="margin-bottom:10px">
								 <select class="productos_incidencia selectpicker" name="productos_incidencia[]" title="Productos afectados" multiple>
									 @foreach ($productos_pedido as $key => $producto)
										 <option value="{{ $producto->id }}" {{ ($producto->estado_incidencia==1) ? 'selected' : '' }}>{{$producto['nombre_producto']}} ({{$producto['sku_producto']}})</option>
									 @endforeach
								 </select>
							 </div>

							 <div>
							<label>Estado</label>
								<select name="estado_incidencia">
								  <option value="1" {{ ($detalles_pedido->estado_incidencia==1) ? 'selected' : '' }}>Abierta</option>
								  <option value="0" {{ ($detalles_pedido->estado_incidencia==0) ? 'selected' : '' }}>Cerrada</option>
									<option value="2" {{ ($detalles_pedido->estado_incidencia==2) ? 'selected' : '' }}>Resuelta</option>
								</select>
							</div>
								<div>
								<label>Motivo de la incidencia</label>
								<select name="desplegable_mensaje_incidencia" id="desplegable_mensaje_incidencia">
									<option value='0'> --- </option>
								  <option value="1" {{ (preg_match('/^1/' , $detalles_pedido->mensaje_incidencia)) ? 'selected' : '' }}>Rotura en transporte</option>
								  <option value="2" {{ (preg_match('/^2/' , $detalles_pedido->mensaje_incidencia)) ? 'selected' : '' }}>Rotura en transporte por mal embalaje</option>
									<option value="3" {{ (preg_match('/^3/' , $detalles_pedido->mensaje_incidencia)) ? 'selected' : '' }}>Error de referencia</option>
									<option value="4" {{ (preg_match('/^4/' , $detalles_pedido->mensaje_incidencia)) ? 'selected' : '' }}>Producto incompleto</option>
									<option value="5" {{ (preg_match('/^5/' , $detalles_pedido->mensaje_incidencia)) ? 'selected' : '' }}>Error de producción</option>
									<option value="6" {{ (preg_match('/^6/' , $detalles_pedido->mensaje_incidencia)) ? 'selected' : '' }}>Fallo de documentación</option>
									<option value="7" {{ (preg_match('/^7/' , $detalles_pedido->mensaje_incidencia)) ? 'selected' : '' }}>Entrega fuera de plazo</option>
									<option value="8" {{ (preg_match('/^8/' , $detalles_pedido->mensaje_incidencia)) ? 'selected' : '' }}>No se ajusta a las necesidades del cliente</option>
									<option value="9" {{ (preg_match('/^9/' , $detalles_pedido->mensaje_incidencia)) ? 'selected' : '' }}>Error de compra</option>
									<option value="Otros" {{  (preg_match('/^Otros/' , $detalles_pedido->mensaje_incidencia)) ? 'selected' : '' }}>Otros</option>
								</select>
							</div>
								<textarea class="callout callout-default" name="mensaje_incidencia" id="mensaje_incidencia" form="form_incidencia" style="width: 100%;margin-top:10px;margin-bottom:0px;display:block">{{$incidencia['mensaje']}}</textarea>
							<div>
								<label>Gestión</label>
								<select name="desplegable_gestion_incidencia" id="desplegable_gestion_incidencia">
									<option value='0'> --- </option>
									<option value="1" {{ (preg_match('/^1/' , $detalles_pedido->gestion_incidencia)) ? 'selected' : '' }}>Devolución</option>
									<option value="2" {{ (preg_match('/^2/' , $detalles_pedido->gestion_incidencia)) ? 'selected' : '' }}>Reposición</option>
									<option value="3" {{ (preg_match('/^3/' , $detalles_pedido->gestion_incidencia)) ? 'selected' : '' }}>Descuento por tara</option>
									<option value="Otros" {{ (preg_match('/^Otros/' , $detalles_pedido->gestion_incidencia)) ? 'selected' : '' }}>Otros</option>
								</select>
								<textarea class="callout callout-default" name="gestion_incidencia" id="gestion_incidencia" form="form_incidencia" style="width: 100%;margin-top:10px;margin-bottom:0px;display:block" >{{$incidencia['gestion']}}</textarea>
							</div>
							<div style="margin-top:10px">
								<label>Cantidad a descontar</label>
								<input type="number" name="historial_incidencia" id="historial_incidencia" placeholder="Cantidad a descontar" step="any" value="{{$detalles_pedido->historial_incidencia}}" />
							</div>

								<button class="btn btn-default btn-sm" type="submit">Actualizar incidencia</button>
							</form>

				    </div>
				  </div>
				</div>



			</div>

		<!-- /.row -->

		<!-- this row will not appear when printing -->
		<div class="row no-print">
			<div class="col-xs-12">
				<a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</a>
				<button type="button" id="modificar_pedido" class="btn btn-success pull-right" onclick="window.location.href='{{Url('/pedidos/modificar/'.$detalles_pedido->id)}}'"><i class="fa fa-edit"></i> modificar</button>
				<button type="button" id="generar_albaranes_pdf" class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generar albaran PDF</button>
			</div>
		</div>
</section>
<!-- /.box-body -->
<style>
input[name='bultos'] {
    width: 50% !important;
}
div#collapse-incidencia {
    padding: 20px 15px;
}
.lead {
margin-bottom: 20px;
font-size: 16px;
font-weight: 600;
line-height: 1.4;
}
.panel-default>.panel-heading {
color: #000;
background-color: #f4f4f4;
border-color: #480101;
}
.panel-default>.panel-heading.warning{
	background-color: #f9b0b0;
}
select[name=estado_incidencia], select[name=desplegable_mensaje_incidencia], select[name=desplegable_gestion_incidencia] {
width: 155px;
margin-right: 80%;
border-radius: 4px;
}
a[data-toggle=collapse]:hover{
color: black;
}
textarea#mensaje_incidencia, #gestion_incidencia {
margin-bottom: 15px !important;
}
a[data-toggle=collapse] {
color: black;
}
inspector-stylesheet:1
a[data-toggle=collapse]:hover {
color: white !important;
}
select[multiple], select[size] {
height: auto;
width: 20%;
margin: 6px 0px;
border-radius: 6px;
padding: 7px;
}
.panel-title p.lead {
    margin-bottom: 5px !important;
}
</style>
@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{url('/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script>
$(document).ready(function(e){
	$('#generar-reposicion').click(function(){
    $('.loader-dw').show();
  });
	//
	$("[name='pedido']").click(function(){
		if($(this).is(":checked")) {
			$(".num-"+$(this).val()).addClass("subrallado");
		} else {
			$(".num-"+$(this).val()).removeClass("subrallado");
		}
	});
	// cambiar bultos
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


	$("#form_seguimiento").submit(function(e){
		e.preventDefault();
		var mensaje_seguimiento = $('#comentario_seguimiento').val();
		$('.loader-dw').show();
    //ajax
    $.ajax({
      url: "",
      method: "POST",
      data: $("#form_seguimiento").serialize()
    }).done(function(msg){
        //$('.loader-dw').hide();
        //apprise(msg);
				location.reload(true);
    });
	});

	// OnSubmit - Actualizar mediante jquery la incidencia
	$("#form_incidencia").submit(function(e){
		e.preventDefault();
		$('.loader-dw').show();
		$.ajax({
			method: "POST",
			url: "",
			data: $("#form_incidencia").serialize()
		}).done(function(msg) {
			if($('#desplegable_gestion_incidencia').val()== '2'){
				location.href = '/pedidos/duplicar/reposicion/{{$detalles_pedido->numero_pedido}}/{{$detalles_pedido->o_csv}}';
			}else{
				$('.loader-dw').hide();
				apprise(msg);
			}
		});
	});

	// OnSubmit - Actualizar mediante jquery la observacion
	$("#form_observaciones").submit(function(e){
		e.preventDefault();
		$.ajax({
			method: "POST",
			url: "",
			data: $("#form_observaciones").serialize()
		}).done(function(msg) {
			apprise(msg);
		});
	});


	/* Al checkear el input global, marcamos todos y desmarcamos al uncheck. */
	$("[name='check_all']").click(function(){
		 if($(this).is(":checked")) {
			$("[name='pedido']").click();
		} else {
			$("[name='pedido']").click();
		}
	});

	/* Al clicar sobre el botón, importamos albaran marcados mediante ajax y retorna un pdf ( utilizado para definir los bultos ). */
	$("#generar_albaranes_pdf").click(function(){
		 var arrayPedidos = $("[name='pedido']").serializeArray();
		 $("#ids").val(JSON.stringify(arrayPedidos));
		 $("#generar_pdf_productos_form").submit();
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
