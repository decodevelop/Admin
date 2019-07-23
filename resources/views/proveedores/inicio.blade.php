@extends('layouts.backend')
@section('titulo','Proveedores > inicio')
@section('titulo_h1','Proveedores')

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
				<i class="fa fa-briefcase"></i>&nbsp;Proveedores<small class="pull-right"></small>
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
					<th>Proveedor</th>
					<th>E-Mail</th>
					<th>Teléfono</th>
					<th>Valoración</th>
					<th>Listo para vender</th>
					<th style="width: 10%">Opciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($proveedores as $proveedor)
					<tr>
						<td>{{$proveedor->id}}</td>
						<td><a href="/proveedores/detalle/{{$proveedor->id}}">{{$proveedor->nombre}}</a></td>
						<td>{{$proveedor->email}}</td>
						<td>{{$proveedor->telefono}}</td>
						<td>
							<span style="margin-right:10px">
								@php
								echo round($proveedor->valoracion_media, 2).' / 5';
								@endphp
							</span>

							<span class="rating-stars-def">
								<span class="rating-stars-container-def">

									@for ($i=0; $i < round($proveedor->valoracion_media); $i++)
										<div class="rating-star-def-act">
											<i class="fa fa-star"></i>
										</div>
									@endfor

									@for ($i=0; $i < 5 - round($proveedor->valoracion_media); $i++)
										<div class="rating-star-def-des">
											<i class="fa fa-star"></i>
										</div>
									@endfor

								</span>
							</span>
						</td>
						<td>
							@if ($proveedor->listo_para_vender)
								<span style="color:green; font-size:20px;"><i class="fa fa-check"></i></span>
							@else
								<span style="color:#d80101; font-size:20px;"><i class="fa fa-times"></i></span>
							@endif
						</td>
						<td>
							<a href="/proveedores/detalle/{{$proveedor->id}}">
								<button data-placement="top" data-toggle="tooltip" title="Detalle" type="button" id="verButton" class="btn btn-default">
									<i class="fa fa-eye"></i>
								</button>
							</a>

							<a href="/proveedores/modificar/{{$proveedor->id}}">
								<button data-placement="top" data-toggle="tooltip" title="Editar" type="button" id="editarButton" class="btn btn-primary" style="margin: 0 2px;">
									<i class="fa fa-edit"></i>
								</button>
							</a>

							<!--
							<span data-placement="top" data-toggle="tooltip" title="Eliminar">
							<button type="button" id="eliminarButton" class="btn btn-github" data-toggle="modal" data-target="#confirmacion_modal_{{$proveedor->id}}">
							<i class="fa fa-trash"></i>
						</button>
					</span>
				-->
			</td>
		</tr>
		<!-- Modal
		<div class="modal fade" id="confirmacion_modal_{{$proveedor->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
		<h3 class="modal-title" id="confirmacion_modalLabel">Confirmación</h3>
	</div>
	<div class="modal-body">
	<h5>¿Estás seguro de que desea eliminar el Proveedor <strong>{{$proveedor->id }}. {{$proveedor->nombre}}</strong>?</h5>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;">Cancelar</button>
<a href=""><button type="button" class="btn btn-primary">Sí, estoy seguro</button></a>
</div>
</div>
</div>
</div> -->
<!-- Modal End -->
@endforeach
</tbody>
</table>
</div>
<!-- /.row -->
</section>
<!-- /.box-body -->
@endsection
