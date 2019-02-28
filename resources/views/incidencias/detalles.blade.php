@extends('layouts.backend')
@section('titulo','Detalles incidencia nº ('.$incidencia->id.")")
@section('contenido')
@if (\Session::has('mensaje'))
	<div class="pad margin no-print">
      <div class="callout callout-info" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-info"></i> OK!</h4>
        {!! \Session::get('mensaje') !!}
      </div>
    </div>
@endif
<section class="content">
	<div class="row">
		<div class="col-md-2">
          <div class="box box-solid">
            <div class="box-header with-border">

              <h3 class="box-title">Detalles</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <dl>
                <dt>Prioridad: {{ $incidencia->prioridad }}</dt>
                <dt>Estado: @if($incidencia->estado==1) abierta @else cerrada @endif</dt>
                </br>
                <dd>Iniciada por:  {{ $incidencia->id_usuario_incidencia }}</dd>
                <dd>Asignada a:  {{ $incidencia->id_usuario_asignado_incidencia }}</dd>
              </dl>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		<div class="col-md-10">
          <div class="box box-solid">
            <div class="box-header with-border">

              <h3 class="box-title">Descripción de la incidencia.</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <dl>
                <dt>{!! html_entity_decode($incidencia->mensaje) !!}</dt>
              </dl>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
	</div>
</section>
@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{url('/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script>
$(document).ready(function(e){
	// OnSubmit - Actualizar mediante jquery la incidencia
	$("#form_incidencia").submit(function(e){
		e.preventDefault();
		$.ajax({
			method: "POST",
			url: "",
			data: $("#form_incidencia").serialize()
		}).done(function(msg) {
			apprise(msg);
		});
	});
	
		
	/* Al checkear el input global, marcamos todos y desmarcamos al uncheck. */
	$("[name='check_all']").click(function(){
		 if($(this).is(":checked")) {
			$("[name='pedido']").click();
		} else {
			$("[name='pedido']").click();
		}
	});
	
	/* Al clicar sobre el botón, importamos albaran marcados mediante ajax y retorna un pdf ( utilizado para definir los bultos ). */
	$("#generar_albaranes_pdf").click(function(){
		 var arrayPedidos = $("[name='pedido']").serializeArray();
		 $("#ids").val(JSON.stringify(arrayPedidos));
		 $("#generar_pdf_productos_form").submit();
		 /*$.ajax({
			url: "/pedidos/albaranes",
			type:'POST',
			data:{ ids:arrayPedidos, "_token":"{{ csrf_token() }}" }
		}).done(function(pdf){
			window.open(pdf,'_blank');
			
		});*/
	});
	
});

</script>
@endsection
