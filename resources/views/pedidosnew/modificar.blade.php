@extends('layouts.backend')
@section('titulo','Detalles pedido número ('.$pedido->id.")")
@section('contenido')
<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i> Detalles de pedido seleccionado <small class="pull-right"></small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">
		<form id="modificar_pedido_form" action="" method="post">
			{{ csrf_field() }}
			<div class="col-sm-4 invoice-col">
				<p class="lead">Datos Facturación</p>
				<address>
				<strong>Cliente Fact: <input type="text" class="form-control input-sm" name="nombre_apellidos" value="{{ $pedido->cliente->nombre_apellidos }}"/></strong><br>
				Dirección Fact: <input type="text" class="form-control input-sm" name="direccion_facturacion" value="{{ $pedido->cliente->direccion->direccion_facturacion }}"/><br>
				Ciudad Fact: <input type="text" class="form-control input-sm" name="ciudad_facturacion" value="{{ $pedido->cliente->direccion->ciudad_facturacion }}"/>
				Estado Fact: <input type="text" class="form-control input-sm" name="estado_facturacion" value="{{ $pedido->cliente->direccion->estado_facturacion }}"/>
				País Fact: <input type="text" class="form-control input-sm" name="pais_facturacion" value="{{ $pedido->cliente->direccion->pais_facturacion }}"/>
				CP Fact: <input type="text" class="form-control input-sm" name="cp_facturacion" value="{{ $pedido->cliente->direccion->cp_facturacion }}"/><br>
				Teléfono: <input type="text" class="form-control input-sm" name="telefono_facturacion" value="{{ $pedido->cliente->telefono_facturacion }}"/><br>
				Correo: <input type="text" class="form-control input-sm" name="email_facturacion" value="{{ $pedido->cliente->email_facturacion }}"/>
				</address>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				<p class="lead">Datos envío</p>
				<address>
				<strong>Cliente envío:<input type="text" class="form-control input-sm" name="nombre_envio" value="{{ $pedido->cliente->nombre_envio }}"/></strong><br>
				Dirección envío: <input type="text" class="form-control input-sm" name="direccion_envio" value="{{ $pedido->cliente->direccion->direccion_envio }}"/><br>
				Ciudad envío: <input type="text" class="form-control input-sm" name="ciudad_envio" value="{{ $pedido->cliente->direccion->ciudad_envio }}"/>
				Estado envío: <input type="text" class="form-control input-sm" name="estado_envio" value="{{ $pedido->cliente->direccion->estado_envio }}"/>
				País envío:<input type="text" class="form-control input-sm" name="pais_envio" value="{{ $pedido->cliente->direccion->pais_envio }}"/>
				CP envío:<input type="text" class="form-control input-sm" name="cp_envio" value="{{ $pedido->cliente->direccion->cp_envio }}"/><br>
				Teléfono: <input type="text" class="form-control input-sm" name="telefono" value="{{ $pedido->cliente->telefono }}"  /><br>
				Correo: <input type="text" class="form-control input-sm" name="email" value="{{ $pedido->cliente->email }}"  />
				</address>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				<p class="lead">Información</p>
				<b>Fecha pedido:</b> <input type="date" class="form-control" name="fecha_pedido" value="{{$pedido->fecha_pedido}}"> <br>
				<b>Factura Nº <input type="text" class="form-control input-sm" name="codigo_factura" value="{{ $pedido->codigo_factura }}"/></b><br>
				<br>
				<b>Nº Pedido:</b> {{ $pedido->numero_pedido }}<br>
				<b>Nº Pedido Prestashop:</b> <input type="text" class="form-control input-sm" name="numero_pedido_ps" value="{{ $pedido->numero_pedido_ps }}"/><br>
				<b>Forma de pago:</b> {{ $pedido->forma_de_pago }}<br>
				<b>Estado pedido:</b> {{ ($pedido->orden_completada=='fullfilled') ? 'Completado' : 'No completado' }}<br>
				<b>Estado pago:</b>
				<select class="form-control" name="pago">
					<option value="Paid" {{( strtolower($pedido->estado_pago) == 'paid') ? 'selected' : ''}}>Pagado</option>
					<option value="notPaid" {{( strtolower($pedido->estado_pago) != 'paid') ? 'selected' : ''}}>No pagado</option>
				</select> <br>
				<br>
				Bultos<input type="number" class="form-control input-sm" name="bultos" value="{{ $pedido->bultos }}"/></strong><br>


			</div>
			<input type="hidden" name="productos_serializados" value="" />
			<input type="hidden" name="observaciones" value="" />
			<input type="hidden" name="total" value="" />
			<!-- /.col -->
			</form>
		</div>
		<!-- /.row -->

		<!-- Table row -->
		<style>.table.table-striped td{	padding: auto 2px}</style>
		<div class="row">
		<div class="col-xs-12 table-responsive">
			<p class="lead">Productos</p>
			<table class="table table-striped">
				<thead>
					<tr>
						<th><input type="checkbox" class="flat-red" name='check_all' value='all'></th>
						<th>Nombre* (ES)</th>
						<th>Nombre original</th>
						<th>Variante</th>
						<th>SKU</th>
						<th>EAN</th>
						<th>Cantidad</th>
						<th>Fecha max envío</th>
						<th>Transportista</th>
						<th>Proveedor</th>
						<th>Subtotal</th>
						<th>Eliminar</th>
					</tr>
				</thead>
				<tbody id="productos">
					@foreach ($pedido->productos as $producto)
						<tr>
							<td><input type="checkbox" class="flat-red" name='pedido' value='{{ $producto->id }}'/><input type="hidden" class="flat-red" name='id' value='{{ $producto->id }}'/></td>
							<td><input type="text" class="form-control input-sm" name="nombre_esp" value="{{$producto->nombre_esp}}"/></td>
							<td><input type="text" class="form-control input-sm" name="nombre" value="{{$producto->nombre}}"/></td>
							<td><input type="text" class="form-control input-sm" name="variante" value="{{$producto->variante}}"/></td>
							<td><input type="text" class="form-control input-sm" name="SKU" value="{{$producto->SKU}}"/></td>
							<td><input type="text" class="form-control input-sm" name="ean" value="{{$producto->ean}}"/></td>
							<td><input  style="width: 65px;" type="number" class="form-control input-sm" name="cantidad" value="{{$producto->cantidad}}"/></td>
							<td><input type="date" class="form-control input-sm" name="fecha_max_salida" value="{{$producto->fecha_max_salida}}"/></td>
							<td>
								<select style="width: 120px;" class="form-control" name="id_transportista">
									@foreach ($transportistas as $transportista)
										<option value="{{$transportista->id}}" {{($producto->transportista->id == $transportista->id) ? 'selected' : ''}} >{{$transportista->nombre}}</option>
									@endforeach
								</select>
							</td>
							<td>
								<select class="form-control" name="id_proveedor">
									@foreach ($proveedores as $proveedor)
										<option value="{{$proveedor->id}}" {{($producto->proveedor->id == $proveedor->id) ? 'selected' : ''}} >{{$proveedor->nombre}}</option>
									@endforeach
								</select>
							</td>
							<td><input style="width: 100px;" type="text" class="form-control input-sm" name="precio_final" value="{{$producto->precio_final}}"/></td>
							<td style="width: 88px;">
								<select class="form-control" name="eliminar">
									<option value="NO">NO</option>
									<option value="SI">SI</option>
								</select>
							</td>
						</tr>
					@endforeach

				</tbody>
				<tr id="copia_producto" style="display:none;">
						<td><input type="checkbox" class="flat-red" name='pedido' value=''/><input type="hidden" class="flat-red" name='id' value='0'/></td>
						<td><input type="text" class="form-control input-sm" name="nombre_esp" value=""/></td>
						<td><input type="text" class="form-control input-sm" name="nombre" value=""/></td>
						<td><input type="text" class="form-control input-sm" name="variante" value=""/></td>
						<td><input type="text" class="form-control input-sm" name="SKU" value=""/></td>
						<td><input type="text" class="form-control input-sm" name="ean" value=""/></td>
						<td><input type="number" class="form-control input-sm" name="cantidad" value=""/></td>
						<td><input type="number" class="form-control input-sm" name="fecha_max_salida" value=""/></td>
						<td>
							<select class="form-control" name="id_transportista">
								@foreach ($transportistas as $transportista)
									<option value="{{$transportista->id}}" >{{$transportista->nombre}}</option>
								@endforeach
							</select>
						</td>
						<td>
							<select class="form-control" name="id_proveedor">
								@foreach ($proveedores as $proveedor)
									<option value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
								@endforeach
							</select>
						</td>
						<td><input type="text" class="form-control input-sm" name="precio_final" value=""/></td>
						<td>
							<select class="form-control" name="eliminar">
								<option value="NO">NO</option>
								<option value="SI">SI</option>
							</select>
						</td>
					</tr>
			</table>
			<button id="añadir_producto" class="pull-right btn btn-default"><i class="fa fa-plus"></i> Añadir más</button>
			<form id="generar_pdf_productos_form" method="post"  action="{{Url(''.'/pedidos/albaran/'.$pedido->id)}}">
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

				<p class="lead">Observaciones:</p>
				<form id="form_observaciones">
				 {{ csrf_field() }}
					<textarea class="callout @if($pedido->observaciones!=NULL || $pedido->observaciones!='') callout-info  @else  callout-default  @endif" name="mensaje_observacion" form="form_observaciones" style="width: 100%;margin-top:10px;margin-bottom:0px;" placeHolder="No hay ningún mensaje en la db.">@if($pedido->observaciones!=NULL || $pedido->observaciones!='') {{$pedido->observaciones}} @endif</textarea>
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
							<td>{{$pedido->total-($pedido->total/100*21)}} €</td>
						</tr>
						<tr>
							<th>IVA aprox.(21%)</th>
							<td>{{$pedido->total/100*21}} €</td>
						</tr>
						<tr>
							<th>Envío:</th>
							<td>Gratuito</td>
						</tr>
						<tr>
							<th>Total:</th>
							<td><input type="text" class="form-control input-sm" name="total_input" value="{{$pedido->total}}"/>  </td>
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
				<a class="btn btn-default" href="/pedidos/detalle/{{$pedido->id}}"><i class="fa fa-arrow-left" style="margin-right: 5px;"></i>Volver</a>
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
			url: "/pedidos/detalle/"+{{$pedido->id}},
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
		"nombre_esp":$("#productos [name='nombre_esp']").serializeArray(),
		"nombre":$("#productos [name='nombre']").serializeArray(),
		"variante": $("#productos [name='variante']").serializeArray(),
		"SKU":  $("#productos [name='SKU']").serializeArray(),
		"ean":  $("#productos [name='ean']").serializeArray(),
		"cantidad":  $("#productos [name='cantidad']").serializeArray(),
		"fecha_max_salida":  $("#productos [name='fecha_max_salida']").serializeArray(),
		"id_transportista": $("#productos [name='id_transportista']").serializeArray(),
		"id_proveedor": $("#productos [name='id_proveedor']").serializeArray(),
		"precio_final": $("#productos [name='precio_final']").serializeArray(),
		"eliminar": $("#productos [name='eliminar']").serializeArray()
		};
		$("[name='total']").val($("[name='total_input']").val());
		$("[name='observaciones']").val($("[name='mensaje_observacion']").val());
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
