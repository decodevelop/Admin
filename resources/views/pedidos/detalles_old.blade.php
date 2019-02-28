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
<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i> Detalles de pedido seleccionado <small class="pull-right">Fecha pedido: {{ $detalles_pedido->fecha_pedido }}</small>
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
				<br>
				<b>Nº Pedido:</b> {{ $detalles_pedido->numero_pedido }}<br>
				<b>Estado pago:</b> {{ $detalles_pedido->pago }}<br>
				<b>Metodo:</b> {{ $detalles_pedido->forma_de_pago }}<br>
				<b>Estado pedido:</b> {{ $detalles_pedido->orden_completada }}
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
						<th><input type="checkbox" class="flat-red" name='check_all' value='all'></th>
						<th>Nombre Producto</th>
						<th>Variante</th>
						<th>SKU</th>
						<th>Cantidad</th>
						<th>Subtotal</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($productos_pedido as $key => $producto)
						<tr>
							<td><input type="checkbox" class="flat-red" name='pedido' value='{{ $producto->id }}'/></td>
							<td>{{$producto['nombre_producto']}}</td>
							<td>{{$producto['variante_producto']}}</td>
							<td>{{$producto['sku_producto']}}</td>
							<td>{{$producto['cantidad_producto']}}</td>
							<td>{{($producto['cantidad_producto']*$producto['precio_producto'])}}</td>
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
			<div class="col-xs-3">
				<p class="lead">Historial incidencia:</p>
				<form id="form_incidencia">
				 {{ csrf_field() }}
				<label>Estado</label>
					<select name="estado_incidencia">
					  <option value="1" {{ ($detalles_pedido->estado_incidencia==1) ? 'selected' : '' }}>Abierta</option>
					  <option value="0" {{ ($detalles_pedido->estado_incidencia==0) ? 'selected' : '' }}>Cerrada</option>
					</select>
					<textarea class="callout @if($detalles_pedido->estado_incidencia==1) callout-danger  @else  callout-default  @endif" name="mensaje_incidencia" form="form_incidencia" style="width: 100%;margin-top:10px;margin-bottom:0px;" placeHolder="No hay ningún mensaje en la db.">@if($detalles_pedido->estado_incidencia==1) {{$detalles_pedido->mensaje_incidencia}} @endif</textarea>
					<button class="btn btn-block btn-default btn-sm" type="submit">Actualizar incidencia</button>
				</form>
			</div>
			<div class="col-xs-3">
				<p class="lead">Observaciónes:</p>
				<form id="form_observaciones">
				 {{ csrf_field() }}
				<label>@if($detalles_pedido->observaciones!=NULL || $detalles_pedido->observaciones!='') Activa @else No hay Inactiva @endif</label>
					<textarea class="callout @if($detalles_pedido->observaciones!=NULL || $detalles_pedido->observaciones!='') callout-info  @else  callout-default  @endif" name="mensaje_observacion" form="form_observaciones" style="width: 100%;margin-top:10px;margin-bottom:0px;" placeHolder="No hay ningún mensaje en la db.">@if($detalles_pedido->observaciones!=NULL || $detalles_pedido->observaciones!='') {{$detalles_pedido->observaciones}} @endif</textarea>
					<button class="btn btn-block btn-default btn-sm" type="submit" >Actualizar Observación </button>
				</form>
			</div>
			<!-- /.col -->
			<div class=" col-xs-offset-2 col-xs-4">
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
		<!-- /.col -->
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
@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{url('/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script>
$(document).ready(function(e){
	// OnSubmit - Actualizar mediante jquery la incidencia
	$("#form_incidencia").submit(function(e){
		e.preventDefault();
		$.ajax({
			method: "POST",
			url: "",
			data: $("#form_incidencia").serialize()
		}).done(function(msg) {
			apprise(msg);
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
//Mostrar text area cuando se selecciona Otros

if($("#desplegable_gestion_incidencia").val()== 'Otros'){
	$("#gestion_incidencia").css('display','block');
}
if($("#desplegable_mensaje_incidencia").val()== 'Otros'){
	$("#mensaje_incidencia").css('display','block');
}
$("#desplegable_gestion_incidencia").change(function(){
	/*
	Values:
	1 = Devolución
	2 = Reposición
	3 = Descuento por tara
	*/
	var gest_value = $("#desplegable_gestion_incidencia").val();
	switch(gest_value){
		case 'Otros':
				$("#gestion_incidencia").val('Otros: ');
				$("#gestion_incidencia").css('display','block');
			break;
		default:
				$("#gestion_incidencia").val(gest_value);
				$("#gestion_incidencia").css('display','block');

	}

});
$("#desplegable_mensaje_incidencia").change(function(){
	/*
	Values:
	1 = Rotura en transporte
	2 = Rotura en transporte por mal embalaje
	3 = Error de referencia
	4 = Producto incompleto
	5 = Error de producción
	6 = Fallo de documentación
	7 = Entrega fuera de plazo
	8 = No se ajusta a las necesidades del cliente
	9 = Error de compra
	*/
	var msg_value = $("#desplegable_mensaje_incidencia").val();
	switch(msg_value){
		case 'Otros':
				$("#mensaje_incidencia").val('Otros: ');
				$("#mensaje_incidencia").css('display','block');
			break;
		default:
				$("#mensaje_incidencia").val(msg_value);
				$("#mensaje_incidencia").css('display','none');

	}

});
</script>
@endsection
