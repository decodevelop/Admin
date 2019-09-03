<?php
use App\Pedidos_wix_importados;
?>
@extends('layouts.backend')
@section('titulo','Pedidos > listado')
@section('titulo_h1','Pedidos')

@section('estilos')
<!-- DataTables -->
<link rel="stylesheet" href="{{url('/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('contenido')
<form id="form_nuevo_pedido" method="POST" action="">
	<section class="content">
		<div class="row">
		@if($mensaje = Session::get('mensaje'))
		<div class="col-md-12">
			<div class="alert alert-info alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-info"></i> Atención!</h4>
				{{ $mensaje }}
			</div>
		</div>
		@endif
			<div class="col-md-12">
				<div class="box DataTableBox">
					<div class="box-header with-border">
					  <h3 class="box-title">Detalles de pedido, envio y facturación</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
							{{ csrf_field() }}
							<div class="row">
								<div class="col-md-12" style="margin-bottom: 15px;">
									<h4></h4>
									<div class="row">
										<div class="form-group col-md-4">
											<label for="numero_pedido">Origen pedido</label>
											<select  class="form-control" name="o_csv">
												@foreach ($origenes as $ori)
													<option value="{{$ori->referencia}}" title="{{$ori->referencia}}">{{$ori->nombre}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group col-md-2">
											<label for="numero_pedido">Número pedido</label>
											<input type="text" class="form-control" name="numero_pedido" placeholder="Nº (automático)" value="{{ old('numero_pedido') }}" disabled />
										</div>
										<div class="form-group col-md-2">
											<label for="codigo_factura">Código Factura</label>
											<input type="text" class="form-control" name="codigo_factura" placeholder="codigo factura" value="{{ old('codigo_factura') }}">
										</div>
										<div class="form-group col-md-2">
											<label for="fecha_pedido">Fecha</label>
											<input type="date" class="form-control" name="fecha_pedido" placeholder="Fecha pedido" value="@if(null !== old('fecha_pedido')) {{ old('fecha_pedido') }} @else{{ (new \DateTime())->format('Y-m-d') }}@endif">
										</div>
										<div class="form-group col-md-2">
											<label for="hora">Hora</label>
											<input type="text" class="form-control" name="hora" placeholder="Hora pedido *Opcional" value="{{ old('hora') }}">
										</div>
									  </div>
									</div>
								<div class="col-md-4" style="border-right: 1px #337ab7 dashed;">
									<h4>Datos facturacion</h4>
									<div class="form-group">
										<label for="cliente_facturacion">Nombre y apellidos</label>
										<input type="text" class="form-control" name="nombre_apellidos" placeholder="Nombre y apellidos" value="@if(isset($cliente)){{$cliente->nombre_apellidos}}@else{{ old('nombre_apellidos') }}@endif">
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label for="nombre_apellidos">Teléfono</label>
											<input type="text" class="form-control" name="telefono_facturacion" placeholder="Teléfono" value="@if(isset($cliente)){{$cliente->telefono_facturacion}}@else{{ old('telefono_facturacion') }}@endif">
										</div>
										<div class="form-group  col-md-6">
											<label for="nombre_apellidos">Correo electrónico</label>
											<input type="email" class="form-control" name="email_facturacion" placeholder="Correo electronico" value="@if(isset($cliente)){{$cliente->email_facturacion}}@else{{ old('email_facturacion') }}@endif">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label for="nombre_apellidos">Pais</label>
											<input type="text" class="form-control" name="pais_facturacion" placeholder="Pais" value="@if(isset($direccion_cliente)){{$direccion_cliente->pais_facturacion}}@else{{ old('pais_facturacion') }}@endif">
										</div>
										<div class="form-group col-md-6">
											<label for="nombre_apellidos">Estado</label>
											<input type="text" class="form-control" name="estado_facturacion" placeholder="Estado" value="@if(isset($direccion_cliente)){{$direccion_cliente->estado_facturacion}}@else{{ old('estado_facturacion') }}@endif">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-4">
											<label for="nombre_apellidos">Ciudad</label>
											<input type="text" class="form-control" name="ciudad_facturacion" placeholder="Ciudad" value="@if(isset($direccion_cliente)){{$direccion_cliente->ciudad_facturacion}}@else{{ old('ciudad_facturacion') }}@endif">
										</div>
										<div class="form-group col-md-4">
											<label for="nombre_apellidos">Direccion</label>
											<input type="text" class="form-control" name="direccion_facturacion" placeholder="Direccion" value="@if(isset($direccion_cliente)){{$direccion_cliente->direccion_facturacion}}@else{{ old('direccion_facturacion') }}@endif">
										</div>
										<div class="form-group col-md-4">
											<label for="nombre_apellidos">Codigo Postal</label>
											<input type="text" class="form-control" name="cp_facturacion" placeholder="Codigo Postal" value="@if(isset($direccion_cliente)){{$direccion_cliente->cp_facturacion}}@else{{ old('cp_facturacion') }}@endif">
										</div>
									</div>
								</div>
								<div class="col-md-4" style="border-right: 1px #337ab7 dashed;">
									<h4>Datos envío</h4>
									<div class="form-group">
										<div class="checkbox">
											<label>
											  <input id="copiar_datos_facturacion" type="checkbox">
											  Copiar los datos de facturación.
											</label>
										  </div>
									</div>
									<div class="form-group">
										<label for="nombre_apellidos">Nombre y apellidos</label>
										<input type="text" class="form-control" name="nombre_envio" placeholder="Nombre y apellidos"  value="@if(isset($cliente)){{$cliente->nombre_envio}}@else{{ old('nombre_envio') }}@endif">
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label for="nombre_apellidos">Teléfono</label>
											<input type="text" class="form-control" name="telefono" placeholder="Teléfono" value="@if(isset($cliente)){{$cliente->telefono}}@else{{ old('telefono') }}@endif">
										</div>
										<div class="form-group  col-md-6">
											<label for="nombre_apellidos">Correo electrónico</label>
											<input type="email" class="form-control" name="email" placeholder="Correo electronico" value="@if(isset($cliente)){{$cliente->email}}@else{{ old('email') }}@endif">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label for="pais_envio">Pais</label>
											<input type="text" class="form-control" name="pais_envio" placeholder="Pais"  value="@if(isset($direccion_cliente)){{$direccion_cliente->pais_envio}}@else{{ old('pais_envio') }}@endif">
										</div>
										<div class="form-group col-md-6">
											<label for="estado_envio">Estado</label>
											<input type="text" class="form-control" name="estado_envio" placeholder="Estado"  value="@if(isset($direccion_cliente)){{$direccion_cliente->estado_envio}}@else{{ old('estado_envio') }}@endif">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-4">
											<label for="ciudad_envio">Ciudad</label>
											<input type="text" class="form-control" name="ciudad_envio" placeholder="Ciudad" value="@if(isset($direccion_cliente)){{$direccion_cliente->ciudad_envio}}@else{{ old('ciudad_envio') }}@endif">
										</div>
										<div class="form-group col-md-4">
											<label for="direccion_envio">Direccion</label>
											<input type="text" class="form-control" name="direccion_envio" placeholder="Direccion" value="@if(isset($direccion_cliente)){{$direccion_cliente->direccion_envio}}@else{{ old('direccion_envio') }}@endif">
										</div>
										<div class="form-group col-md-4">
											<label for="cp_envio">Codigo Postal</label>
											<input type="text" class="form-control" name="cp_envio" placeholder="Codigo Postal" value="@if(isset($direccion_cliente)){{$direccion_cliente->cp_envio}}@else{{ old('cp_envio') }}@endif">
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<h4>Extras compra</h4>
									<div class="row">
										<div class="form-group col-md-4">

										</div>
										<div class="form-group col-md-4">
											<label for="tasas">Importe total</label>
											<input type="number" class="form-control" step="0.01" name="total_noiva" placeholder="Importe" value="{{ old('total_noiva') }}">
										</div>
										<div class="form-group col-md-4">
											<label for="total">Importe IVA €</label>
											<input type="text" class="form-control" name="total" placeholder="Total + IVA" readonly="readonly" value="{{ old('total') }}">
										</div>
										<div class="form-group col-md-4">
											<label for="forma_de_pago">Forma de pago</label>
											<select  class="form-control" name="metodo_pago">
												@foreach ($metodos_pago as $metodo_pago)
													<option value="{{$metodo_pago->id}}">{{$metodo_pago->nombre}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="pago">Estado pago</label>
											<select  class="form-control" name="estado_pago">
												<option value="Paid">Pagado</option>
												<option value="notPaid">No pagado</option>
											</select>
										</div>
										<div class="form-group col-md-4">

										</div>
										<div class="form-group col-md-4">
											<label for="cupon">Cupon</label>
											<input type="text" class="form-control" name="cupon" placeholder="Cupon" value="{{ old('cupon') }}">
										</div>
									</div>
								</div>
							</div>

					</div>

				</div>
	        <!-- /.box -->
	        </div>
			<div class="col-md-12">
				<div class="box DataTableBox">
					<div class="box-header with-border">
					  <h3 class="box-title">Productos</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div id="productos" class="col-md-12" style="margin-bottom: 15px;">
								<h4>Inf. general del pedido</h4>
								<div class="row">
									<div class="form-group col-md-2">
										<label for="nombre_esp[]">Nombre* (ES)</label>
										<input type="text" class="form-control" name="nombre_esp[]" placeholder="Nombre producto">
									</div>
									<div class="form-group col-md-2">
										<label for="nombre[]">Nombre original</label>
										<input type="text" class="form-control" name="nombre[]" placeholder="Nombre producto">
									</div>
									<div class="form-group col-md-1">
										<label for="variante[]">Variante</label>
										<input type="text" class="form-control" name="variante[]" placeholder="variante">
									</div>
									<div class="form-group col-md-1">
										<label for="ean[]">EAN</label>
										<input type="text" class="form-control" name="ean[]" placeholder="ean">
									</div>
									<div class="form-group col-md-1">
										<label for="SKU[]">SKU</label>
										<input type="text" class="form-control" name="SKU[]" placeholder="SKU">
									</div>
									<div class="form-group col-md-1">
										<label for="cantidad[]">Cantidad</label>
										<input type="text" class="form-control" name="cantidad[]" placeholder="Cantidad">
									</div>
									<div class="form-group col-md-2">
										<label for="id_transportista[]">Transportista</label>
										<select class="form-control" name="id_transportista[]">
											@foreach ($transportistas as $transportista)
												<option value="{{$transportista->id}}" >{{$transportista->nombre}}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group col-md-2">
										<label for="id_proveedor[]">Proveedor</label>
										<select class="form-control" name="id_proveedor[]">
											@foreach ($proveedores as $proveedor)
												<option value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
											@endforeach
										</select>
									</div>

							  </div>
							  <div id="m_productos" class="row">
							  </div>
							  <button id="a_producto" class="btn bg-orange btn-flat margin pull-right"><i class="fa fa-plus"> </i> Añadir otro producto </button>
							</div>
						</div>
					</div>
				</div>

				<div class="box DataTableBox">
					<div class="box-header with-border">
					  <h3 class="box-title">Observaciones</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<textarea name="observaciones" rows="5" cols="80"></textarea>
					</div>
				</div>



	        <!-- /.box -->
			<button type="submit" class="btn btn-app pull-right">
	                <i class="fa fa-save"></i> Guardar
	        </button>
	    </div>
	    <!-- /.row -->
	</section>
</form>
@endsection

@section('scripts')
<!-- DataTables -->

<script src="{{url('/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{url('/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script>
$(document).ready(function(){
	$("#a_producto").click(function(e){
		e.preventDefault();
		$("#m_productos").append($("#productos > .row").html());
	});

	$("input[name='total_noiva']").change(function(){
		var importe = $("input[name='total_noiva']").val();
		var importeIva = parseFloat(((importe/100)*21)) + parseFloat(importe);
		$("input[name='total']").val(importeIva.toFixed(2));
	});
	$('#copiar_datos_facturacion').change(function(){
		if(this.checked){
			$("input[name*='nombre_envio']").val($("input[name*='nombre_apellidos']").val());
			$("input[name*='pais_envio']").val($("input[name*='pais_facturacion']").val());
			$("input[name*='estado_envio']").val($("input[name*='estado_facturacion']").val());
			$("input[name*='ciudad_envio']").val($("input[name*='ciudad_facturacion']").val());
			$("input[name*='direccion_envio']").val($("input[name*='direccion_facturacion']").val());
			$("input[name*='cp_envio']").val($("input[name*='cp_facturacion']").val());
			$("input[name*='telefono']").val($("input[name*='telefono_facturacion']").val());
			$("input[name*='email']").val($("input[name*='email_facturacion']").val());
		} else {
			$("input[name*='cliente_envio']").val("");
			$("input[name*='pais_envio']").val("");
			$("input[name*='estado_envio']").val("");
			$("input[name*='ciudad_envio']").val("");
			$("input[name*='direccion_envio']").val("");
			$("input[name*='cp_envio']").val("");
		}
	});
});
</script>
@endsection
