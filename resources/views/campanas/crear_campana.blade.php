@section('contenido')

<style>

</style>
<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i>&nbsp; Crear nueva campaña<small class="pull-right"></small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">
		<form id="crear_nueva_campana_form" action="" method="post">
			{{ csrf_field() }}
			<div class="col-sm-3 invoice-col">
				Nombre de la campaña: <input type="text" class="form-control input-sm" name="nombre" value=""/>
			  Fecha de inicio: <input type="date" class="form-control input-sm" name="fecha_inicio" value=""/>
			  Fecha de finalización: <input type="date" class="form-control input-sm" name="fecha_fin" value=""/>
			  Dirección de envío: <input type="text" class="form-control input-sm" name="direccion_envio" value=""/>
			  Ciudad: <input type="text" class="form-control input-sm" name="ciudad_envio" value=""/>
			  Estado: <input type="text" class="form-control input-sm" name="estado_envio" value=""/>
			  País: <input type="text" class="form-control input-sm" name="pais_envio" value=""/>
			  Código Postal: <input type="text" class="form-control input-sm" name="cp_envio" value=""/>

        <input type="submit" class="btn btn-info" value="Crear nueva campaña"> </input>
      </div>
			<!-- /.col -->
			</form>
		</div>
		<!-- /.row -->
</section>
<!-- /.box-body -->
@endsection
