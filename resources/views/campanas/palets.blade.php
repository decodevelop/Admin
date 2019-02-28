@extends('layouts.backend')
@section('titulo','Campañas > palets')
@section('titulo_h1','Palets')

@section('estilos')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{url('/plugins/datatables/dataTables.bootstrap.css')}}">
	<link rel="stylesheet" href="/css/custom.css">
	<style>

	thead {
    background: #7eb5c1;
}
	.ver_palets:nth-child(2n) {
	    background-color: #f9f7f7;
	}
	table.tabla_producto {
			width: 100%;
	}
	td.nombre-producto {
			width: 54%;
	}
	td.estado-incidencia, td.estado-envio{
		width: 22%;
	}
	.tabla_producto tr {
			border-bottom: 1px solid #d8cfcf;
	}
	table.tabla_producto td {
			padding: 8px 5px;
	}
	</style>
@endsection


@section('contenido')

<section class="invoice">
	<!-- title row -->
	<div class="row">
		@if (\Session::has('success'))
			<div class="alert alert-success">
				<ul>
					<li>{!! \Session::get('success') !!}</li>
				</ul>
			</div>
		@endif
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i>&nbsp;Palets<small class="pull-right"></small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">
		   <table class="table table-bordered">
         <thead>
           <tr>
             <th>Referencia</th>
             <th>Productos</th>
           <tr>
         </thead>
         <tbody>
					 @foreach ($palets as $palet)
						 <tr class="ver_palets">
							 <td> <a href="/campanas/palets/modificar/{{$palet->id}}">{{$palet->referencia}}</a></td>
							 <td>
								 <table class="tabla_producto stackDrop">

									 @foreach ($palet->productos_palets as $productos_palets)
										 <tr  id="productoPalet_{{$productos_palets->id}}" class="producto_palet" >
											 <td>{{$productos_palets->producto->producto->nombre}}({{$productos_palets->cantidad}})</td>
										 </tr>
									 @endforeach

								 </table>

							 </td>
							 <td>
								 <form class="" action="/campanas/palets/albaran/{{$palet->id}}" method="get">

									 <select class="" name="tamano" name="id_{{$palet->id}}">
										 <option value="1" {{($palet->tamano == "1'20 x 0'80 x 1'80") ? 'selected' : ''}}>1'20 x 0'80 x 1'80</option>
										 <option value="2" {{($palet->tamano == "2'00 x 1'00 x 1'80") ? 'selected' : ''}}>2'00 x 1'00 x 1'80</option>
										 <option value="3" {{($palet->tamano == "2'20 x 1'00 x 1'80") ? 'selected' : ''}}>2'20 x 1'00 x 1'80</option>
									 </select>
									 <button class="btn btn-block btn-default btn-sm"><i class="fa fa-print"></i> Generar albarán</button>
								 </form>
								 <a href="/campanas/palets/etiquetas/{{$palet->id}}" class="btn btn-block btn-default btn-sm"><i class="fa fa-tags"></i> Generar etiquetas</a>
								 <hr>
								 <div class="col-xs-3" style="float:right">
								 	<a href="/campanas/palets/eliminar/{{$palet->id}}" class="btn btn-block btn-danger btn-sm"><i class="fa fa-trash"></i> eliminar</a>
								 </div>
							 </td>
						 </tr>
					 @endforeach

         </tbody>
       </table>
		</div>
		<div class="box-footer clearfix">
					<ul class="pagination pagination-sm no-margin pull-right">
						<!-- $paginacion->links('pedidos.pagination',["test" => "test"] ) -->
						{!! $palets->appends($_GET)->links() !!}
					</ul>
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
	/*$( '.producto_palet' ).draggable({
		appendTo: "body",
    cursor: "move",
    helper: 'clone',
    revert: "invalid"
	});
	$(".stackDrop").droppable({
    tolerance: "intersect",
    accept: ".producto_palet",
    activeClass: "ui-state-default",
    hoverClass: "ui-state-hover",
    drop: function(event, ui) {
        $(this).append($(ui.draggable));
    }
});*/


});

</script>
@endsection
