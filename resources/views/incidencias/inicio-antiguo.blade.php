@extends('layouts.backend')
@section('titulo','Incidencias > listado')
@section('titulo_h1','Incidencias')

@section('estilos')
@endsection

@section('contenido')
<?php
?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box DataTableBox">
				<div class="box-header with-border">
				  <h3 class="box-title">Listado de incidencias</h3>
				  <div class="box-tools pull-right">
					<a href="{{url('/importar_csv')}}" type="button" class="btn btn-block btn-default btn-sm"><i class="fa fa-upload"></i> Importar CSV</a>
				  </div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
				<p> Filtros aplicados: 
				@foreach($_GET as $key => $filter)
				@if($filter!="")
				<span class="label bg-blue">{{$key .":".$filter}}</span> 
				@endif
				@endforeach
				</p>
					<table class="table table-bordered">
						<thead>
							<tr>
							<form id="filtros_datatable" method="get">
							<input class="form-control input-sm" style="display:none;" type="text" name="page" placeholder="pagina" value="{{$incidencias->currentPage()}}">
								<th style="width: 10px"><input type="checkbox" class="flat-red" name='check_all' value='all'></th>
								<th style="width: 10px"></th>
								<th style="width: 10px"></th>
								<th style="width: 10px">
									<select class="form-control input-sm" type="text" name="prioridad">
										<option value="1" {{(@$_GET['prioridad']=='1') ? 'selected': ''}}>nula</option>
										<option value="2" {{(@$_GET['prioridad']=='2') ? 'selected': ''}}>baja</option>
										<option value="3" {{(@$_GET['prioridad']=='3') ? 'selected': ''}}>media</option>
										<option value="4" {{(@$_GET['prioridad']=='4') ? 'selected': ''}}>alta</option>
										<option value="5" {{(@$_GET['prioridad']=='5') ? 'selected': ''}}>máxima</option>
										<option value="" {{(@$_GET['prioridad']!='0' && (@$_GET['prioridad']!='1')) ? 'selected': ''}}>Todas</option>
									</select>
								</th>
								<th style="width: 10px">
									<select class="form-control input-sm" type="text" name="estado">
										<option value="1" {{(@$_GET['estado']=='1') ? 'selected': ''}}>Abierta</option>
										<option value="2" {{(@$_GET['estado']=='0') ? 'selected': ''}}>Cerrada</option>
										<option value="" {{(@$_GET['estado']!='0' && (@$_GET['estado']!='1')) ? 'selected': ''}}>Todas</option>
									</select>
								</th>
								<th style="width: 10px">
									<button type="submit" class="btn btn-primary btn-sm">FILTRAR</button>
								</th>
							</form>
							</tr>
							<tr>
								<th style="width: 10px"></th>
								<th style="width: 10px">U. Incidencia</th>
								<th style="width: 10px">U. asignado</th>
								<th style="width: 10px">Prioridad</th>
								<th style="width: 10px">Estado </th>
								<th class="text-center" style="width: 10px">Opciónes </th>
							</tr>
						</thead>
						<tbody id="dataTables_pedidos">
							
							@forelse($incidencias as $key => $incidencia)
							<tr class="ver_pedido @if($incidencia->prioridad==1) incidencia @endif num-{{ $incidencia->id }}">
								<td>
									<input type="checkbox" class="flat-red" name='pedido' value='{{ $incidencia->id }}'>
								</td>
								<td>{{ $incidencia->id_usuario_incidencia }}</td>
								<td>{{ $incidencia->id_usuario_asignado_incidencia }}</td>
								<td>{{ $incidencia->prioridad }}</td>
								<td>{{ $incidencia->estado }}</td>
								<td>
									<a title="Ver detalle" href="/incidencias/detalle/{{$incidencia->id }}" id="aceptar-{{ $incidencia->id }}" type="button" class="btn btn-primary btn-xs">
										<i class="fa fa-eye" aria-hidden="true"></i>
									</a>
								</td>
							</tr>
							@empty
								<p>No hay datos.</p>
							@endforelse
						</tbody>
					</table>
				</div>
				
				<!-- /.box-body -->
				<div class="box-footer clearfix">
					<ul class="pagination pagination-sm no-margin pull-right">
						<!-- $paginacion->links('pedidos.pagination',["test" => "test"] ) -->
						{!! $incidencias->appends($_GET)->links() !!}
							
					</ul>
				</div>
				<div class="box-footer clearfix">
				<button title="Generar albaranes en pdf" id="generar_albaranes_pdf" type="button" class="btn btn-danger btn-xs"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Generar Albaranes</button>
				<button title="Generar Excel" id="generar_excel_pdf" type="button" class="btn btn-success btn-xs"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Generar Excel</button>
				<form id="generar_albaranes_pdf_form" method="post"  action="{{Url(''.'/pedidos/albaranes')}}">
				{{ csrf_field() }}
				<input type="hidden" id="ids" name="ids" value="empty"/>
				</form>
				<form id="generar_excel_form" method="post"  action="{{Url(''.'/pedidos/gen_excel')}}">
				{{ csrf_field() }}
				<input type="hidden" id="ids_e" name="ids" value="empty"/>
				</form>
				</div>
				
			</div>
        <!-- /.box -->
        </div>
    </div>
    <!-- /.row -->
</section>
@endsection

@section('scripts')
<!-- DataTables -->

<script src="{{url('/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{url('/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script>
$(document).ready(function(){
	
	/* Al clicar sobre enviar pedido, realizamos un ajax para mararlo como enviado, con previa confirmación. */
	$('[id^="enviar-pedido_"]').click(function(){
		var numped = $( this ).attr("id").split("_")[1];
		apprise('Marcar pedido como enviado?', {'verify':true}, function(r){
			if(r){
				$(this).prop( "disabled", true );
				$.ajax({
					url: "/pedidos/enviar_pedido/"+numped,
					cache: false
				}).done(function(msg){
					var resultado = JSON.parse(msg);
					var mensaje = resultado[0];
					var fecha_envio = resultado[1];
					
					$(".fecha-envio-"+numped+" > span").removeClass("label-danger").addClass("label-success", {duration:500}).html(''+fecha_envio+'');
					//alert( "Pedido enviado: " + mensaje );
					apprise(mensaje);
				});
			} else {
			
			}
		});
	});
	
	/* Al checkear el input global, marcamos todos y desmarcamos al uncheck. */
	$("[name='check_all']").click(function(){
		 if($(this).is(":checked")) {
			//var checkarray = $("[name='pedido']:checked").serializeArray();
			$("[name='pedido']").each(function(){
				if(!$(this).is(":checked")) {
					$(".num-"+$(this).val()).addClass("subrallado");
					$(this).click();
				}
            });
			//$("[name='pedido']").click();
		} else {
			$("[name='pedido']").each(function(){
				if($(this).is(":checked")){
					$(".num-"+$(this).val()).removeClass("subrallado");
					$(this).click();
				}
            });
		}
	});
	
	/* Al seleccionar cada pedido de forma independiente, marcamos y añadimos o eliminamos clase subrallado, */
	$("[name='pedido']").click(function(){
		if($(this).is(":checked")) {
			$(".num-"+$(this).val()).addClass("subrallado");
		} else {
			$(".num-"+$(this).val()).removeClass("subrallado");
		}
	});
	
	$.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
	/* Al clicar sobre el botón, importamos albaranes marcados ( si los hay ) mediante ajax y retorna un pdf. */
	$("#generar_albaranes_pdf").click(function(){
		
		 var arrayPedidos = $("[name='pedido']").serializeArray();
		 $("#ids").val(JSON.stringify(arrayPedidos));
		 $("#generar_albaranes_pdf_form").submit();
		 /*$.ajax({
			url: "/pedidos/albaranes",
			type:'POST',
			data:{ ids:arrayPedidos, "_token":"{{ csrf_token() }}" }
		}).done(function(pdf){
			window.open(pdf,'_blank');
			
		});*/
	});
	
	$("#generar_excel_pdf").click(function(){
		
		 var arrayPedidos = $("[name='pedido']").serializeArray();
		 $("#ids_e").val(JSON.stringify(arrayPedidos));
		 $("#generar_excel_form").submit();
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
