@extends('layouts.backend')
@section('titulo','Detalles pedido número ('.$detalles_pedido->id.")")
@section('contenido')
<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i> Detalles de pedido seleccionado <small class="pull-right">Fecha pedido: 2/10/2014</small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">
		<form id="modificar_pedido_form" action="{{Url('/pedidos/modificar/'.$detalles_pedido->id)}}" method="post">
			{{ csrf_field() }}
			<div class="col-sm-4 invoice-col">
				Datos Facturación
				<address>
				<strong>Cliente Fact: <input type="text" class="form-control input-sm" name="cliente_facturacion" value="{{ $detalles_pedido->cliente_facturacion }}"/></strong><br>
				Dirección Fact: <input type="text" class="form-control input-sm" name="direccion_facturacion" value="{{ $detalles_pedido->direccion_facturacion }}"/><br>
				Ciudad Fact: <input type="text" class="form-control input-sm" name="ciudad_facturacion" value="{{ $detalles_pedido->ciudad_facturacion }}"/> País Fact: <input type="text" class="form-control input-sm" name="pais_facturacion" value="{{ $detalles_pedido->pais_facturacion }}"/> CP Fact: <input type="text" class="form-control input-sm" name="cp_facturacion" value="{{ $detalles_pedido->cp_facturacion }}"/><br>
				Teléfono: <input type="text" class="form-control input-sm" name="telefono_comprador" value="{{ $detalles_pedido->telefono_comprador }}"/><br>
				Correo: <input type="text" class="form-control input-sm" name="correo_comprador" value="{{ $detalles_pedido->correo_comprador }}"/>
				</address>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				Datos envío
				<address>
				<strong>Cliente envio:<input type="text" class="form-control input-sm" name="cliente_envio" value="{{ $detalles_pedido->cliente_envio }}"/></strong><br>
				Dirección envío: <input type="text" class="form-control input-sm" name="direccion_envio" value="{{ $detalles_pedido->direccion_envio }}"/><br>
				Ciudad envío: <input type="text" class="form-control input-sm" name="ciudad_envio" value="{{ $detalles_pedido->ciudad_envio }}"/> País envío:<input type="text" class="form-control input-sm" name="pais_envio" value="{{ $detalles_pedido->pais_envio }}"/> CP envío:<input type="text" class="form-control input-sm" name="cp_envio" value="{{ $detalles_pedido->cp_envio }}"/><br>
				Teléfono: <input type="text" class="form-control input-sm" value="{{ $detalles_pedido->telefono_comprador }}" disabled /><br>
				Correo: <input type="text" class="form-control input-sm" value="{{ $detalles_pedido->correo_comprador }}" disabled />
				</address>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				<b>Factura Nº <input type="text" class="form-control input-sm" name="codigo_factura" value="{{ $detalles_pedido->codigo_factura }}"/></b><br>
				<br>
				<b>Nº Pedido:</b> {{ $detalles_pedido->numero_pedido }}<br>
				<b>Forma de pago:</b> {{ $detalles_pedido->forma_de_pago }}<br>
				<b>Estado pedido:</b> {{ ($detalles_pedido->orden_completada=='fullfilled') ? 'Completado' : 'No completado' }}<br>
				<b>Estado pago:</b>
				<select class="form-control" name="pago">
					<option value="Paid" {{( strtolower($detalles_pedido->pago) == 'paid') ? 'selected' : ''}}>Pagado</option>
					<option value="notPaid" {{( strtolower($detalles_pedido->pago) != 'paid') ? 'selected' : ''}}>No pagado</option>
				</select> <br>
				<b>Metodo entrega:</b>
				<select  class="form-control" name="metodo_entrega">
					<option value="default" {{( strtolower($detalles_pedido->metodo_entrega) == 'default') ? 'selected' : ''}}>Default</option>
					<option value="tipsa" {{( strtolower($detalles_pedido->metodo_entrega) == 'tipsa') ? 'selected' : ''}}>Tipsa</option>
					<option value="nacex" {{( strtolower($detalles_pedido->metodo_entrega) == 'nacex') ? 'selected' : ''}}>Nacex</option>
					<option value="asm" {{( strtolower($detalles_pedido->metodo_entrega) == 'asm') ? 'selected' : ''}}>ASM</option>
					<option value="seur" {{( strtolower($detalles_pedido->metodo_entrega) == 'seur') ? 'selected' : ''}}>Seur</option>
					<option value="mrw" {{( strtolower($detalles_pedido->metodo_entrega) == 'mrw' ) ? 'selected' : ''}}>MRW</option>
					<option value="ups" {{( strtolower($detalles_pedido->metodo_entrega) == 'ups' ) ? 'selected' : ''}}>UPS</option>
					<option value="Dachser" {{( strtolower($detalles_pedido->metodo_entrega) == 'dachser' ) ? 'selected' : ''}}>Dachser</option>
					<option value="mailboxes" {{( strtolower($detalles_pedido->metodo_entrega) == 'mailboxes') ? 'selected' : ''}}>MailBoxes</option>
					<option value="logicgreen" {{( strtolower($detalles_pedido->metodo_entrega) == 'logicgreen' ) ? 'selected' : ''}}>LogicGreen</option>
					<option value="transparets" {{( strtolower($detalles_pedido->metodo_entrega) == 'transparets' ) ? 'selected' : ''}}>Transparets</option>
					<option value="transporte interno" {{( strtolower($detalles_pedido->metodo_entrega) == 'transporte interno') ? 'selected' : ''}}>Transporte Interno</option>
					<option value="solucioneslogisticas" {{( strtolower($detalles_pedido->metodo_entrega) == 'solucioneslogisticas') ? 'selected' : ''}}>SOLUCIONES LOGÍSTICAS</option>
					<option value="transbarcelona {{( strtolower($detalles_pedido->metodo_entrega) == 'transbarcelona') ? 'selected' : ''}}">TRANSBARCELONA</option>
					<option value="BLD {{( strtolower($detalles_pedido->metodo_entrega) == 'BLD') ? 'selected' : ''}}">BLD</option>
					<option value="recogida" {{( strtolower($detalles_pedido->metodo_entrega) == 'recogida') ? 'selected' : ''}}>Recogida</option>
				</select><br>
				Bultos<input type="text" class="form-control input-sm" name="bultos" value="{{ $detalles_pedido->bultos }}"/></strong><br>


			</div>
			<input type="hidden" name="productos_serializados" value="" />
			<input type="hidden" name="total" value="" />
			<!-- /.col -->
			</form>
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
				<tbody id="productos">
					@foreach ($productos_pedido as $key => $producto)
						<tr>
							<td><input type="checkbox" class="flat-red" name='pedido' value='{{ $producto->id }}'/><input type="hidden" class="flat-red" name='id' value='{{ $producto->id }}'/></td>
							<td><input type="text" class="form-control input-sm" name="nombre_producto" value="{{$producto['nombre_producto']}}"/></td>
							<td><input type="text" class="form-control input-sm" name="variante_producto" value="{{$producto['variante_producto']}}"/></td>
							<td><input type="text" class="form-control input-sm" name="sku_producto" value="{{$producto['sku_producto']}}"/></td>
							<td><input type="text" class="form-control input-sm" name="cantidad_producto" value="{{$producto['cantidad_producto']}}"/></td>
							<td><input type="text" class="form-control input-sm" name="precio_producto" value="{{$producto['precio_producto']}}"/></td>
						</tr>
					@endforeach

				</tbody>
				<tr id="copia_producto" style="display:none;">
						<td><input type="checkbox" class="flat-red" name='pedido' value='{{ $producto->id }}'/><input type="hidden" class="flat-red" name='id' value='0'/></td>
						<td><input type="text" class="form-control input-sm" name="nombre_producto" value=""/></td>
						<td><input type="text" class="form-control input-sm" name="variante_producto" value=""/></td>
						<td><input type="text" class="form-control input-sm" name="sku_producto" value=""/></td>
						<td><input type="text" class="form-control input-sm" name="cantidad_producto" value=""/></td>
						<td><input type="text" class="form-control input-sm" name="precio_producto" value=""/></td>
					</tr>
			</table>
			<button id="añadir_producto" class="pull-right">Añadir más</button>
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
			<div class="col-xs-6">

				<p class="lead">Observaciónes:</p>
				<form id="form_observaciones">
				 {{ csrf_field() }}
				<label>@if($detalles_pedido->observaciones!=NULL || $detalles_pedido->observaciones!='') Activa @else No hay Inactiva @endif</label>
					<textarea class="callout @if($detalles_pedido->observaciones!=NULL || $detalles_pedido->observaciones!='') callout-info  @else  callout-default  @endif" name="mensaje_observacion" form="form_observaciones" style="width: 100%;margin-top:10px;margin-bottom:0px;" placeHolder="No hay ningún mensaje en la db.">@if($detalles_pedido->observaciones!=NULL || $detalles_pedido->observaciones!='') {{$detalles_pedido->observaciones}} @endif</textarea>
					<button class="btn btn-block btn-default btn-sm" type="submit" >Actualizar Observación </button>
				</form>
			</div>
			<!-- /.col -->
			<div class="col-xs-6">
				<p class="lead">Recuento:</p>

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
							<td><input type="text" class="form-control input-sm" name="total_input" value="{{$detalles_pedido->total}}"/>  </td>
						</tr>
					</tbody>
					</table>
				</div>
			</div>
		<!-- /.col -->
		</div>
		<!-- /.row -->
		<div class="row">
			<div class="col-xs-12">
				<a class="btn btn-default" href="/pedidos/detalle/{{$detalles_pedido->id}}">Volver</a>
				<button type="button" id="guardar_modificaciones" class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-save"></i> Finalizar y guardar</button>
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
			url: "/pedidos/detalle/"+{{$detalles_pedido->id}},
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
	$("#guardar_modificaciones").click(function(){
		var productos = {
		"id":$("#productos [name='id']").serializeArray(),
		"nombre_producto":$("#productos [name='nombre_producto']").serializeArray(),
		"variante_producto": $("#productos [name='variante_producto']").serializeArray(),
		"sku_producto":  $("#productos [name='sku_producto']").serializeArray(),
		"cantidad_producto": $("#productos [name='cantidad_producto']").serializeArray(),
		"precio_producto": $("#productos [name='precio_producto']").serializeArray()
		};
		$("[name='total']").val($("[name='total_input']").val());
		$("[name='productos_serializados']").val(JSON.stringify(productos));

		// Enviamos con el submit, también los productos para actualizarlos todos de una.
		$("#modificar_pedido_form").submit();
		 /*$.ajax({
			url: "/pedidos/albaranes",
			type:'POST',
			data:{ ids:arrayPedidos, "_token":"{{ csrf_token() }}" }
		}).done(function(pdf){
			window.open(pdf,'_blank');

		});*/
	});

	$("#añadir_producto").click(function(){
		$("#productos").append("<tr>"+$("#copia_producto").html()+"</tr>");
	});

});

</script>
@endsection
