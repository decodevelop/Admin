@extends('layouts.backend')
@section('titulo','Orígenes > inicio')
@section('titulo_h1','Orígenes')

@section('estilos')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{url('/plugins/datatables/dataTables.bootstrap.css')}}">
	<link rel="stylesheet" href="/css/custom.css">
@endsection

@section('contenido')
	<style>
	tr:nth-child(2n) {
		background-color: #f9fafc;
	}
	thead {
		background: #7eb5c1;
	}
</style>
<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i>&nbsp;Orígenes<small class="pull-right"></small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
	<!-- info row -->
	<div class="row invoice-info">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Origen</th>
					<th>Referencia</th>
					<th>Color</th>
					<th>Transportista Principal</th>
					<th>Web</th>
					<th>Api Key</th>
					<th>Seguimiento</th>
					<th style="width: 10%">Opciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($origenes as $origen)
					<tr>
						<td>{{$origen->nombre}}</td>
						<td>{{$origen->referencia}}</td>
						<td style='color:{{$origen->color}}'>
							{{$origen->color}}
						</td>
						<td>{{$origen->transportista_principal}}</td>
						<td>{{$origen->web}}</td>
						<td>{{$origen->api_key}}</td>
						<td>
							@if ( $origen->seguimiento != 0 )
								Sí
							@else
								No
							@endif
						</td>
						<td>
							<a href="/origenes/editar/{{$origen->id}}">
								<button data-placement="top" data-toggle="tooltip" title="Editar" type="button" id="editarButton" class="btn btn-primary"style="margin: 0 2px;">
									<i class="fa fa-edit"></i>
								</button>
							</a>

							<span data-placement="top" data-toggle="tooltip" title="Eliminar">
								<button type="button" id="eliminarButton" class="btn btn-github" data-toggle="modal" data-target="#confirmacion_modal_{{$origen->id}}">
									<i class="fa fa-trash"></i>
								</button>
							</span>

						</td>
					</tr>
					<!-- Modal -->
					<div class="modal fade" id="confirmacion_modal_{{$origen->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h3 class="modal-title" id="confirmacion_modalLabel">Confirmación</h3>
								</div>
								<div class="modal-body">
									<h5>¿Estás seguro de que desea eliminar el origen <strong>{{$origen->nombre}}</strong>?</h5>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;">Cancelar</button>
									<a href="/origenes/eliminar/{{$origen->id}}"><button type="button" class="btn btn-primary">Sí, estoy seguro</button></a>
								</div>
							</div>
						</div>
					</div>
					<!-- Modal End -->
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
<!-- /.row -->
</section>
<!-- /.box-body -->
@endsection
