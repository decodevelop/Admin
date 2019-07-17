@extends('layouts.backend')
@section('titulo','Campañas > inicio')
@section('titulo_h1','Campañas')

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
				<i class="fa fa-globe"></i>&nbsp;Campañas<small class="pull-right"></small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
	<!-- info row -->
	<div class="row invoice-info">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th >Origen</th>
					<th >Nombre campaña</th>
					<th>Palets</th>
					<th >Fecha inicio</th>
					<th >Fecha fin</th>
					<th >Total</th>
					<th >Opciones</th>
					<tr>
					</thead>
					<tbody>
						@foreach ($campanas as $campana)
							<tr>
								<td>{{$origenes->find($campana->origen_id)->nombre}}</td>
								<td><a href="/campanas/productos/{{$campana->id}}">{{$campana->nombre}}</a></td>
								<td><a href="/campanas/palets/{{$campana->id}}">Ver</a> // <a href="/campanas/palets/excel/{{$campana->id}}">Descargar Excel</a> </td>
								<td>{{$campana->fecha_inicio}}</td>
								<td>{{$campana->fecha_fin}}</td>
								<td>{{$campana->total}}</td>
								<td>
									<a href="/campanas/editar/{{$campana->id}}"><button data-placement="top" data-toggle="tooltip" title="Editar" type="button" id="editarButton" class="btn btn-primary"style="margin: 0 0px;"><i class="fa fa-edit"></i></button></a>
									<span data-placement="top" data-toggle="tooltip" title="Eliminar"><button type="button" id="eliminarButton" class="btn btn-github" data-toggle="modal" data-target="#confirmacion_modal_{{$campana->id}}"><i class="fa fa-trash"></i></button></span>
								</td>
							</tr>
							<!-- Modal -->
							<div class="modal fade" id="confirmacion_modal_{{$campana->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h3 class="modal-title" id="confirmacion_modalLabel">Confirmación</h3>
										</div>
										<div class="modal-body">
											<h5>¿Estás seguro de que desea eliminar la campaña <strong>{{$campana->nombre}}</strong>?</h5>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;">Cancelar</button>
											<a href="/campanas/eliminar/{{$campana->id}}"><button type="button" class="btn btn-primary">Sí, estoy seguro</button></a>
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
