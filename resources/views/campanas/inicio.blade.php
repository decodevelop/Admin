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
           </tr>
					 @endforeach
         </tbody>
       </table>
		</div>
		<!-- /.row -->
</section>
<!-- /.box-body -->
@endsection
