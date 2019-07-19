@extends('layouts.backend')
@section('titulo','Detalles '.$proveedor->nombre)
@section('titulo_h1','Proveedores')
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
					<i class="fa fa-globe"></i> Detalles de  {{$proveedor->nombre}}
				</h2>
			</div>
		</div>

		<div class="col-sm-12">
			<table class="table">
				<tbody>
					<tr>
						<td style="width:20%"><strong>ID:</strong></td>
						<td><span>{{$proveedor->id}}</span></td>
					</tr>
					<tr>
						<td><strong>Nombre:</strong></td>
						<td><span>{{$proveedor->nombre}}</span></td>
					</tr>
					<tr>
						<td><strong>E-Mail:</strong></td>
						<td><span>{{$proveedor->email}}</span></td>
					</tr>
					<tr>
						<td><strong>Teléfono:</strong></td>
						<td><span>{{$proveedor->telefono}}</span></td>
					</tr>
					<tr>
						<td><strong>Plazo de entrega:</strong></td>
						<td><span>{{$proveedor->plazo_entrega}}</span></td>
					</tr>
					<tr>
						<td><strong>Envío:</strong></td>
						<td><span>{{$proveedor->envio}}</span></td>
					</tr>
					<tr>
						<td><strong>Método de pago:</strong></td>
						<td><span>{{$proveedor->metodo_pago}}</span></td>
					</tr>
					<tr>
						<td><strong>Precio especial campaña:</strong></td>
						<td><span>{{$proveedor->precio_esp_campana}}</span></td>
					</tr>
					<tr>
						<td><strong>Logística:</strong></td>
						<td><span>{{$proveedor->logistica}}</span></td>
					</tr>
					<tr>
						<td><strong>Contrato:</strong></td>
						<td><span>{{$proveedor->contrato}}</span></td>
					</tr>
					<tr>
						<td><strong>Observaciones:</strong></td>
						<td><span>{{$proveedor->observaciones}}</span></td>
					</tr>
					<tr>
						<td><strong>Listo para vender:</strong></td>
						@if ($proveedor->listo_para_vender)
							<td><span style="color:green; font-size:20px;"><i class="fa fa-check"></i></span></td>
						@else
							<td><span style="color:#d80101; font-size:20px;"><i class="fa fa-times"></i></span></td>
						@endif
					</tr>
				</tbody>
			</table>
		</div>

		<div class="row no-print">
			<div class="col-xs-12">
				<button type="button" id="modificar_proveedor" class="btn btn-success pull-right" onclick="window.location.href='{{Url('/proveedores/modificar/'.$proveedor->id)}}'">
					<i class="fa fa-edit"></i> Modificar
				</button>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<p class="page-header" style="font-size: 18px;">
					<i class="fa fa-tags"></i> Rappels
				</p>
			</div>
		</div>

		<!-- Table row -->
		<div class="row">
			<div class="col-xs-12 table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th style="width:5%">ID</th>
							<th>Condiciones</th>
							<th style="width:10%">Max</th>
							<th style="width:10%">Min</th>
							<th style="width:10%"></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($rappel as $r)
							<tr>
								<td>{{$r->id}}</td>
								<td>{!! $r->condiciones !!}</td>
								<td>{{$r->max}}</td>
								<td>{{$r->min}}</td>
								<td>
									<div data-placement="top" data-toggle="tooltip" title="Eliminar" class="pull-right"><button type="button" id="eliminarButton" class="btn btn-danger" data-toggle="modal" data-target="#confirmacion_modal_{{$r->id}}"><i class="fa fa-trash"></i></button></div>
									<a href="/proveedores/{{$proveedor->id}}/rappels/modificar/{{$r->id}}"><button data-placement="top" data-toggle="tooltip" title="Editar" type="button" id="editarButton" class="btn btn-success pull-right" style="margin: 0 10px;"><i class="fa fa-edit"></i></button></a>
								</td>
							</tr>
							<!-- Modal -->
							<div class="modal fade" id="confirmacion_modal_{{$r->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h3 class="modal-title" id="confirmacion_modalLabel">Confirmación</h3>
										</div>
										<div class="modal-body">
											<h5>¿Estás seguro de que desea eliminar el Rappel <strong>{{$r->id }}</strong>?</h5>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;">Cancelar</button>
											<a href="/proveedores/{{$proveedor->id}}/rappels/eliminar/{{$r->id}}"><button type="button" class="btn btn-primary">Sí, estoy seguro</button></a>
										</div>
									</div>
								</div>
							</div>
							<!-- Modal End -->
						@endforeach
						<tr>
							<td colspan="5">
								<a href="/proveedores/{{$proveedor->id}}/rappels/nuevo">
									<button data-placement="top" data-toggle="tooltip" title="Nuevo Rappel" type="button" id="verButton" class="btn btn-primary pull-right">
										<i class="fa fa-plus"></i>
									</button>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
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

</script>
@endsection
