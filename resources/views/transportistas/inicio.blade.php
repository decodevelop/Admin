@extends('layouts.backend')
@section('titulo','Transportistas > inicio')
@section('titulo_h1','Transportistas')

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
				<i class="fa fa-globe"></i>&nbsp;Transportistas<small class="pull-right"></small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
	<!-- info row -->
	<div class="row invoice-info">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Transportista</th>
					<th style="width: 10%">Opciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($transportistas as $transportista)
					<tr>
						<td>{{$transportista->id}}</td>
						<td>{{$transportista->nombre}}</td>
						<td>
							<a href="/transportistas/editar/{{$transportista->id}}">
								<button data-placement="top" data-toggle="tooltip" title="Editar" type="button" id="editarButton" class="btn btn-primary"style="margin: 0 2px;">
									<i class="fa fa-edit"></i>
								</button>
							</a>

							<span data-placement="top" data-toggle="tooltip" title="Eliminar">
								<button type="button" id="eliminarButton" class="btn btn-github" data-toggle="modal" data-target="#confirmacion_modal_{{$transportista->id}}">
									<i class="fa fa-trash"></i>
								</button>
							</span>

						</td>
					</tr>
					<!-- Modal -->
					<div class="modal fade" id="confirmacion_modal_{{$transportista->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h3 class="modal-title" id="confirmacion_modalLabel">Confirmación</h3>
								</div>
								<div class="modal-body">
									<h5>¿Estás seguro de que desea eliminar el transportista <strong>{{$transportista->nombre}}</strong>?</h5>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;">Cancelar</button>
									<a href="/transportistas/eliminar/{{$transportista->id}}"><button type="button" class="btn btn-primary">Sí, estoy seguro</button></a>
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
