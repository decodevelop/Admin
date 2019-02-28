@extends('layouts.backend')
@section('contenido')

<style>
input[type="submit"] {
  float: right;
    margin-top: 10px;
}
select {
  margin-bottom: 5px;
  padding: 2px 8px;
}
</style>
<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i> &nbsp;Editar datos del producto NOMBRE<span value="$pedido->id"></span> del pedido NUMERO<small class="pull-right"></small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">
		<form id="modificar_producto_pedido_form" action="" method="post">
			{{ csrf_field() }}
			<div class="col-sm-4 invoice-col">
				Nombre esp: <input type="text" class="form-control input-sm" name="nombre_esp" value=""/>
				Nombre: <input type="text" class="form-control input-sm" name="nombre" value=""/>
        SKU: <input type="text" class="form-control input-sm" name="SKU" value=""/>
        EAN: <input type="text" class="form-control input-sm" name="ean" value=""/>
				Variante: <input type="text" class="form-control input-sm" name="variante" value=""/>
				Cantidad: <input type="number" class="form-control input-sm" name="cantidad" value=""/>
				Peso: <input type="number" class="form-control input-sm" name="peso" value=""/>
      </div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
        Precio base: <input type="number" class="form-control input-sm" name="precio_base" value=""/>
        Precio final: <input type="number" class="form-control input-sm" name="precio_final" value=""/>
        Texto especial producto: <input type="text" class="form-control input-sm" name="texto_especial_producto" value=""/>
        Estado proveedor:</br>
        <select name="estado_proveedor">
            <option value="0">No enviado</option>
            <option value="1">Enviado</option>
        </select>
      </br>
        Estado envío:</br>
        <select name="estado_envio">
          <option value="0">No enviado</option>
          <option value="1">Enviado</option>
        </select></br>
        Fecha envío: <input type="date" class="form-control input-sm" name="fecha_envio" value=""/>
        ID antigua<input type="number" class="form-control input-sm" name="antigua_id" value=""/>

        <input type="submit" class="btn btn-info" value="Actualizar producto"> </input>
			<!-- /.col -->
			</form>
		</div>
		<!-- /.row -->
</section>
<!-- /.box-body -->
@endsection
