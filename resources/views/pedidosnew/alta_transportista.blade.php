@extends('layouts.backend')
@section('titulo','Transportistas > nuevo')
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
				<i class="fa fa-truck"></i> &nbsp; Añadir nuevo transportista<small class="pull-right"></small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">
		<form id="añadir_nuevo_transportista_form" action="" method="post">
			{{ csrf_field() }}
			<div class="col-sm-3 invoice-col">
				Nombre del transportista: <input type="text" class="form-control input-sm" name="nombre" value=""/>
			<!--	Teléfono contacto: <input type="text" class="form-control input-sm" name="telefono" value=""/>
        Datos2: <input type="text" class="form-control input-sm" name="" value=""/>
        Datos3: <input type="text" class="form-control input-sm" name="" value=""/> -->
        <input type="submit" class="btn btn-info" value="Añadir nuevo transportista"> </input>
      </div>
			<!-- /.col -->
			</form>
		</div>
		<!-- /.row -->
</section>
<!-- /.box-body -->
@endsection
