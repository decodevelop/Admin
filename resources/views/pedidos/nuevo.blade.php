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
												@foreach ($origen_pedidos as $ori)
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
											<label for="nombre_apellidos">Hora</label>
											<input type="text" class="form-control" name="hora_pedido" placeholder="Hora pedido *Opcional" value="{{ old('hora_pedido') }}">
										</div>
									  </div>
									</div>
								<div class="col-md-4" style="border-right: 1px #337ab7 dashed;">
									<h4>Datos facturacion</h4>
									<div class="form-group">
										<label for="cliente_facturacion">Nombre y apellidos</label>
										<input type="text" class="form-control" name="cliente_facturacion" placeholder="Nombre y apellidos" value="{{ old('cliente_facturacion') }}">
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label for="nombre_apellidos">Teléfono</label>
											<input type="text" class="form-control" name="telefono_comprador" placeholder="Teléfono" value="{{ old('telefono_comprador') }}">
										</div>
										<div class="form-group  col-md-6">
											<label for="nombre_apellidos">Correo electrónico</label>
											<input type="email" class="form-control" name="correo_comprador" placeholder="Correo electronico" value="{{ old('correo_comprador') }}">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label for="nombre_apellidos">Pais</label>
											<input type="text" class="form-control" name="pais_facturacion" placeholder="Pais" value="{{ old('pais_facturacion') }}">
										</div>
										<div class="form-group col-md-6">
											<label for="nombre_apellidos">Estado</label>
											<input type="text" class="form-control" name="estado_facturacion" placeholder="Estado" value="{{ old('estado_facturacion') }}">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-4">
											<label for="nombre_apellidos">Ciudad</label>
											<input type="text" class="form-control" name="ciudad_facturacion" placeholder="Ciudad" value="{{ old('ciudad_facturacion') }}">
										</div>
										<div class="form-group col-md-4">
											<label for="nombre_apellidos">Direccion</label>
											<input type="text" class="form-control" name="direccion_facturacion" placeholder="Direccion" value="{{ old('direccion_facturacion') }}">
										</div>
										<div class="form-group col-md-4">
											<label for="nombre_apellidos">Codigo Postal</label>
											<input type="text" class="form-control" name="cp_facturacion" placeholder="Codigo Postal" value="{{ old('cp_facturacion') }}">
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
										<input type="text" class="form-control" name="cliente_envio" placeholder="Nombre y apellidos"  value="{{ old('cliente_envio') }}">
									</div>
									<div class="row">
										<div class="form-group col-md-6">
											<label for="pais_envio">Pais</label>
											<input type="text" class="form-control" name="pais_envio" placeholder="Pais"  value="{{ old('pais_envio') }}">
										</div>
										<div class="form-group col-md-6">
											<label for="estado_envio">Estado</label>
											<input type="text" class="form-control" name="estado_envio" placeholder="Estado"  value="{{ old('estado_envio') }}">
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-4">
											<label for="ciudad_envio">Ciudad</label>
											<input type="text" class="form-control" name="ciudad_envio" placeholder="Ciudad" value="{{ old('ciudad_envio') }}">
										</div>
										<div class="form-group col-md-4">
											<label for="direccion_envio">Direccion</label>
											<input type="text" class="form-control" name="direccion_envio" placeholder="Direccion" value="{{ old('direccion_envio') }}">
										</div>
										<div class="form-group col-md-4">
											<label for="cp_envio">Codigo Postal</label>
											<input type="text" class="form-control" name="cp_envio" placeholder="Codigo Postal" value="{{ old('cp_envio') }}">
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<h4>Extras compra</h4>
									<div class="row">
										<div class="form-group col-md-4">
											<label for="metodo_entrega">Metodo entrega</label>
											<select  class="form-control" name="metodo_entrega">
												<option value="tipsa">Tipsa</option>
												<option value="nacex">Nacex</option>
												<option value="asm">ASM</option>
												<option value="seur">Seur</option>
												<option value="mrw">MRW</option>
												<option value="ups">UPS</option>
												<option value="dachser">Dachser</option>
												<option value="mailboxes">MailBoxes</option>
												<option value="logicgreen">LogicGreen</option>
												<option value="transparets">Transparets</option>
												<option value="transporte interno">Transporte Interno</option>
												<option value="solucioneslogisticas">SOLUCIONES LOGÍSTICAS</option>
												<option value="transbarcelona">TRANSBARCELONA</option>
												<option value="BLD">BLD</option>
												<option value="recogida">Recogida</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="tasas">Importe total</label>
											<input type="text" class="form-control" name="tasas" placeholder="Importe" value="{{ old('tasas') }}">
										</div>
										<div class="form-group col-md-4">
											<label for="total">Importe IVA €</label>
											<input type="text" class="form-control" name="total" placeholder="Total + IVA" readonly="readonly" value="{{ old('total') }}">
										</div>
										<div class="form-group col-md-4">
											<label for="forma_de_pago">Forma de pago</label>
											<select  class="form-control" name="forma_de_pago">
												<option value="paypal">Paypal</option>
												<option value="stripe">Stripe</option>
												<option value="transerenciabancaria">Transf. Bancaria</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="pago">Estado pago</label>
											<select  class="form-control" name="pago">
												<option value="Paid">Pagado</option>
												<option value="notPaid">No pagado</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="orden_completada">Orden completada</label>
											<select  class="form-control" name="orden_completada">
												<option value="fullfilled">Completada</option>
												<option value="notFullfilled">No completada</option>
											</select>
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
									<div class="form-group col-md-3">
										<label for="numero_pedido[]">Nombre</label>
										<input type="text" class="form-control" name="nombre_producto[]" placeholder="Nombre producto">
									</div>
									<div class="form-group col-md-2">
										<label for="nombre_apellidos[]">Texto adicional</label>
										<input type="text" class="form-control" name="texto_especial_producto[]" placeholder="Texto adicional">
									</div>
									<div class="form-group col-md-2">
										<label for="numero_pedido[]">Variante</label>
										<input type="text" class="form-control" name="variante_producto[]" placeholder="Variante producto">
									</div>
									<div class="form-group col-md-2">
										<label for="fecha_pedido[]">SKU</label>
										<input type="text" class="form-control" name="sku_producto[]" placeholder="SKU">
									</div>
									<div class="form-group col-md-1">
										<label for="nombre_apellidos[]">Cantidad</label>
										<input type="text" class="form-control" name="cantidad_producto[]" placeholder="Cantidad">
									</div>
									<div class="form-group col-md-1" style="display:none;">
										<label for="nombre_apellidos[]">Peso</label>
										<input type="text" class="form-control" name="peso_producto[]" placeholder="Peso producto">
									</div>
									<div class="form-group col-md-2" style="display:none;">
										<label for="nombre_apellidos[]">Precio</label>
										<input type="text" class="form-control" name="precio_producto[]" placeholder="Precio producto">
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
	              </a>
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

	$("input[name='tasas']").change(function(){
		var importe = $("input[name='tasas']").val();
		var importeIva = parseFloat(((importe/100)*21)) + parseFloat(importe);
		$("input[name='total']").val(importeIva.toFixed(2));
	});
	$('#copiar_datos_facturacion').change(function(){
		if(this.checked){
			$("input[name*='cliente_envio']").val($("input[name*='cliente_facturacion']").val());
			$("input[name*='pais_envio']").val($("input[name*='pais_facturacion']").val());
			$("input[name*='estado_envio']").val($("input[name*='estado_facturacion']").val());
			$("input[name*='ciudad_envio']").val($("input[name*='ciudad_facturacion']").val());
			$("input[name*='direccion_envio']").val($("input[name*='direccion_facturacion']").val());
			$("input[name*='cp_envio']").val($("input[name*='cp_facturacion']").val());
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
