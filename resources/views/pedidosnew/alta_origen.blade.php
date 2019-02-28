@extends('layouts.backend')
@section('titulo','Origen > nuevo')
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
				<i class="fa fa-globe"></i>&nbsp; Añadir nuevo origen<small class="pull-right"></small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">
		<form id="añadir_nuevo_origen_form" action="" method="post">
			{{ csrf_field() }}
			<div class="col-sm-3 invoice-col">
				Nombre del origen: <input type="text" class="form-control input-sm" name="nombre" value=""/>
			  Referencia: <input type="text" class="form-control input-sm" name="referencia" value=""/>
			  Grupo: <input type="text" class="form-control input-sm" name="grupo" value=""/>
			  Color: <input type="text" class="form-control input-sm" name="color" value=""/>
        Transportista principal: FALTA FER FOREACH</br>

			  Web: <input type="text" class="form-control input-sm" name="web" value=""/>
      <!--  Datos3: <input type="text" class="form-control input-sm" name="" value=""/> -->
      <!--  Datos4: <input type="text" class="form-control input-sm" name="" value=""/> -->
        <input type="submit" class="btn btn-info" value="Añadir nuevo origen"> </input>
      </div>
			<!-- /.col -->
			</form>
		</div>
		<!-- /.row -->
</section>
<!-- /.box-body -->
@endsection
