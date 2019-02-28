@extends('layouts.backend')
@section('titulo','Incidencias > nueva incidencia')
@section('titulo_h1','Añadir nueva incidencia')

@section('estilos')
@endsection

@section('contenido')
<form id="form_nuevo_pedido" method="POST">
	{{ csrf_field() }}
	<section class="content">
	<div class="row">
		@if($mensaje = Session::get('mensaje'))
		<div class="col-md-12">
		<div class="alert alert-info alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa fa-info"></i> Atención!</h4>
		{{ $mensaje }}
		</div>
		</div>
		@endif
		<div class="col-md-12">
			<div class="box DataTableBox">
				<div class="box-header with-border">
				<h3 class="box-title">Formulario incidencia</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="col-md-12" style="margin-bottom: 15px;">
						<h4>Información de la incidencia</h4>
						<div class="row">
							<div class="form-group col-md-3">
								<label for="prioridad">Prioridad</label>
								<select  class="form-control" name="prioridad">
									<option value="1">nula</option>
									<option value="2">baja</option>
									<option value="3">media</option>
									<option value="4">alta</option>
									<option value="5">máxima</option>
								</select>
							</div>
						</div>
						<h4>Detalles incidencia</h4>
						<div class="row">
							<div class="form-group col-md-12">
								<label for="descripcion_incidencia">Descripción:</label>
								<textarea id="editor1" name="mensaje_incidencia" rows="10" cols="80">
								</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- /.box -->
	</div>
	<!-- /.box -->
	<button type="submit" class="btn btn-app pull-right">
		<i class="fa fa-save"></i> Guardar
	</button>
	</div>
	<!-- /.row -->
	</section>
</form>
@endsection

@section('scripts')
<!-- CK Editor -->
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script>
$(document).ready(function(){
});
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1');
    //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();
  });
</script>
@endsection
