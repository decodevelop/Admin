@extends('layouts.backend')
@section('titulo','Proveedor > nuevo')
@section('contenido')

<style>
input[type="submit"] {
  float: right;
    margin-top: 10px;
}
select {
  margin-bottom: 2px;
  padding: 3px 5px;
}
</style>
<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-envelope-square"></i> &nbsp; Añadir nuevo proveedor<small class="pull-right"></small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">
		<form id="añadir_nuevo_proveedor_form" action="" method="post">
			{{ csrf_field() }}
			<div class="col-sm-3 invoice-col">
				Nombre del proveedor: <input type="text" class="form-control input-sm" name="nombre" value=""/>
			  Teléfono: <input type="text" class="form-control input-sm" name="telefono" value=""/>
        Correo: <input type="email" class="form-control input-sm" name="correo" value=""/>
      <!--  Datos3: <input type="text" class="form-control input-sm" name="" value=""/> -->
      <!--  Datos4: <input type="text" class="form-control input-sm" name="" value=""/> -->
        <input type="submit" class="btn btn-info" value="Añadir nuevo proveedor"> </input>
      </div>
			<!-- /.col -->
			</form>
		</div>
		<!-- /.row -->
</section>
<!-- /.box-body -->
@endsection
