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
					<th>Listo para vender</th>
					<th>Opciones</th>
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
									@if ($proveedor->listo_para_vender)
										<span style="color:green; font-size:20px;"><i class="fa fa-check"></i></span>
									@else
										<span style="color:#d80101; font-size:20px;"><i class="fa fa-times"></i></span>
									@endif
								</td>
								<td></td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<!-- /.row -->
	</section>
	<!-- /.box-body -->
@endsection
