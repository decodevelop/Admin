<?php
use App\Pedidos_wix_importados;
?>
@extends('layouts.backend')
@section('titulo','Incidencias > nueva')
@section('titulo_h1','Nueva incidencia')

@section('estilos')
<!-- DataTables -->
<link rel="stylesheet" href="{{url('/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('contenido')
<section class="content">
	@if ($message = Session::get('danger'))
	<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>
		<strong>{{ $message }}</strong><br>
	</div>

	@endif
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i> Detalles de pedido seleccionado - {{$pedido->numero_albaran}}  <small class="pull-right">Fecha pedido: {{ $pedido->fecha_pedido }}</small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->

	<!-- Table row -->
	<div class="row">
	<div class="col-xs-12 table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Nombre Producto</th>
					<th>Variante</th>
					<th>SKU</th>
					<th>Cantidad</th>
					<th>Subtotal</th>
					<th>Transportista</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($pedido->productos as $producto)
					<tr class="select-prd num-{{ $producto->id }} ">

						<td>{{$producto->nombre_esp}}</td>
						<td>{{$producto->variante}}</td>
						<td>{{$producto->SKU}}</td>
						<td>{{$producto->cantidad}}</td>
						<td>@if ($pedido->origen->referencia == 'AM' )
							{{$producto->precio_final}}
						@else
							{{($producto->cantidad * $producto->precio_final)}}
						@endif</td>
						<td>{{$producto->transportista->nombre}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		</div>
		<!-- /.col -->
		<div class="col-xs-5">
			<p class="lead">Importe:</p>

			<div class="table-responsive">
				<table class="table">
				<tbody>
					<tr>
						<th>Total:</th>
						<td>{{$pedido->total}} €</td>
					</tr>
				</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- /.row -->

	<div class="row">
    <div class="col-xs-12">
      <div class="box DataTableBox">
        <div class="box-body">
          <form id="form_incidencia" method="post">
           {{ csrf_field() }}


              <label>Producto afectado</label>
           <div style="margin-bottom:10px">
             <select class="productos_incidencia selectpicker" name="productos_incidencia[]" title="Productos afectados" multiple>
               @foreach ($pedido->productos as $producto)
                 <option value="{{ $producto->id }}">{{$producto->nombre_esp}} ({{$producto->SKU}})</option>
               @endforeach
             </select>
           </div>

           <div>
          <label>Estado</label>
            <select name="estado_incidencia">
              <option value="1">Abierta</option>
              <option value="0">Cerrada</option>
              <option value="2">Resuelta</option>
            </select>
          </div>
            <div>
            <label>Motivo de la incidencia</label>
            <select name="id_motivo" id="id_motivo">
              @foreach ($motivos as $motivo)
                <option value="{{$motivo->id}}">{{$motivo->nombre}}</option>
              @endforeach
            </select>
          </div>
            <textarea class="callout callout-default" name="motivo_info" id="motivo_info" form="form_incidencia" style="width: 100%;margin-top:10px;margin-bottom:0px;display:block"></textarea>
          <div>
            <label>Gestión</label>
            <select name="id_gestion" id="id_gestion">
              @foreach ($gestiones as $gestion)
                <option value="{{$gestion->id}}">{{$gestion->nombre}}</option>
              @endforeach
            </select>
            <textarea class="callout callout-default" name="gestion_info" id="gestion_info" form="form_incidencia" style="width: 100%;margin-top:10px;margin-bottom:0px;display:block" ></textarea>
          </div>
          <div style="margin-top:10px">
            <label>Cantidad a descontar</label>
            <input type="number" name="cantidad_descontar" id="cantidad_descontar" placeholder="Cantidad a descontar" step="any" value="" />
          </div>

            <button class="btn btn-default btn-sm" type="submit">Actualizar incidencia</button>
          </form>

        </div>

      </div>

    </div>
  </div>
</section>
@endsection

@section('scripts')
<!-- DataTables -->

<script src="{{url('/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{url('/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script>
$(document).ready(function(){

});
</script>
@endsection
