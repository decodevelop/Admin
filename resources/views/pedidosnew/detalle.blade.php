@extends('layouts.backend')
@section('titulo','Detalles pedido número ('.$pedido->id.")")
@section('contenido')
@if (\Session::has('mensaje'))
	<div class="pad margin no-print">
      <div class="callout callout-info" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-info"></i> OK!</h4>
        {!! \Session::get('mensaje') !!}
      </div>
    </div>
@endif
<link rel="stylesheet" href="/css/custom.css">
<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i> Detalles de pedido seleccionado - {{$pedido->numero_albaran}}
					<small class="pull-right"> | <b>Fecha máxima de salida: {{ $pedido->fecha_max_envio }}</b></small>
					<small class="pull-right">Fecha pedido: {{ $pedido->fecha_pedido }} </small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">
			<div class="col-sm-4 invoice-col">
				Datos Facturación
				<address>
				<strong>{{ $pedido->cliente->nombre_apellidos }}</strong><br>
				{{ $pedido->cliente->direccion->direccion_facturacion }}<br>
				{{ $pedido->cliente->direccion->ciudad_facturacion }}, {{ $pedido->cliente->direccion->pais_facturacion }} {{ $pedido->cliente->direccion->cp_facturacion }}<br>
				Teléfono: {{ $pedido->cliente->telefono_facturacion }}<br>
				Correo: {{ $pedido->cliente->email_facturacion }}
				</address>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				Datos envío
				<address>
				<strong>{{ $pedido->cliente->nombre_envio }}</strong><br>
				{{ $pedido->cliente->direccion->direccion_envio }}<br>
				{{ $pedido->cliente->direccion->ciudad_envio }}, {{ $pedido->cliente->direccion->pais_envio }} {{ $pedido->cliente->direccion->cp_envio }}<br>
				Teléfono: {{ $pedido->cliente->telefono }}<br>
				Correo: {{ $pedido->cliente->email }}
				</address>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				<b>Factura Nº {{ $pedido->codigo_factura }}</b><br>
				<b>Nº Pedido PS:</b> {{ $pedido->numero_pedido_ps }}<br>
				<b>Nº Pedido:</b> {{ $pedido->numero_pedido }}<br>
				<b>Estado pago:</b> {{ ( strtolower($pedido->estado_pago) == 'paid') ? 'Pagado' : 'No pagado' }}<br>
				<b>Forma de pago:</b> {{ $pedido->metodo_pago->nombre }}<br>

			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->

		<!-- Table row -->
		<div class="row">
		<div class="col-xs-12 table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="table-check"><input type="checkbox" class="flat-red check-all" name='check_all' value='all'></th>
						<th>Nombre (ES)</th>
						<th>Nombre original</th>
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
							<td class="table-check num-{{ $producto->id }}"><input type="checkbox" class="flat-red input_id_producto" name='pedido' value='{{ $producto->id }}'/></td>
							<td>{{$producto->nombre_esp}}</td>
							<td>{{$producto->nombre}}</td>
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
			<form id="generar_pdf_productos_form" method="post"  action="">
				{{ csrf_field() }}
				<input type="hidden" id="ids" name="ids" value="empty"/>
			</form>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->

		<div class="row">
			<!-- accepted payments column -->

			<div class="col-xs-4">
				<p class="lead">Observaciones:</p>
				<form id="form_observaciones">
				 {{ csrf_field() }}
				<label>@if($pedido->observaciones!=NULL || $pedido->observaciones!='') Activa @else No hay Inactiva @endif</label>
					<textarea class="callout @if($pedido->observaciones!=NULL || $pedido->observaciones!='') callout-info  @else  callout-default  @endif" name="mensaje_observacion" form="form_observaciones" style="width: 100%;margin-top:10px;margin-bottom:0px;" placeHolder="No hay ningún mensaje en la db.">@if($pedido->observaciones!=NULL || $pedido->observaciones!='') {{$pedido->observaciones}} @endif</textarea>
					<button class="btn btn-block btn-default btn-sm" type="submit" >Actualizar Observación </button>
				</form>
			</div>
			<div class="col-xs-3">
				<div class="col-xs-12">
				 	<p class="lead"><strong>Bultos</strong><div class="input-group">
						<input type="number" min="0" id="value-bultos_{{$pedido->id }}" class="form-control" placeholder="Bultos" name="bultos" value="{{$pedido->bultos}}">
						<div class="input-group-btn">
							<button id="set-bultos_{{ $pedido->id }}" class="btn btn-default" type="submit"><i class="fa fa-archive" aria-hidden="true"></i></button>
						</div>
					</div></p>
				</div>
				<div class="col-xs-12">
					<a class="btn btn-default" style="display:none" href="" id="generar-reposicion" name="generar-reposicion">Reposición</a>
				</div>
			</div>
			<!-- /.col -->
			<div class="col-xs-5">
				<p class="lead">Importe:</p>

				<div class="table-responsive">
					<table class="table">
					<tbody>
						<tr>
							<th style="width:50%">Subtotal:</th>
							<td>{{$pedido->total-($pedido->total/100*21)}} €</td>
						</tr>
						<tr>
							<th>IVA aprox.(21%)</th>
							<td>{{$pedido->total/100*21}} €</td>
						</tr>
						<tr>
							<th>Envio:</th>
							<td>Gratuito</td>
						</tr>
						<tr>
							<th>Total:</th>
							<td>{{$pedido->total}} €</td>
						</tr>
					</tbody>
					</table>
				</div>
			</div>
			<div class="col-xs-12 col-lg-6 " style="margin-bottom: 20px;">

				<div class="panel-group">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" href="#collapse-seguimiento"><p class="lead">Seguimiento:</p></a>
							</h4>
						</div>
						<div id="collapse-seguimiento" class="col-xs-12 panel-collapse collapse in">
							@if (count($seguimiento) > 0)
								@foreach ($seguimiento as $seg)
								<div class="fila-seguimiento col-md-8 col-xs-12 @if($seg->id_usuario == Auth::user()->id) propietario  @endif">
									<div class="seg-left col-xs-3">
										<div class="usuario"> <i class="fa fa-user" aria-hidden="true"></i> {{$usuarios[$seg->id_usuario-1]->apodo}}</div>
										<div class="fecha">{{$seg->created_at}}</div>
									</div>
									<div class="seg-right col-xs-8" style="padding-right:0!important">
											<div class="comentario">
												<div class="mensaje"> {{$seg->mensaje}} </div>
											</div>
									</div>
								</div>
								@endforeach
							@endif
							<div class="fila-seguimiento col-md-8 col-xs-12">
								<div class="seg-left col-xs-3">
									<div class="usuario"> <i class="fa fa-commenting" aria-hidden="true"></i> {{Auth::user()->apodo}}:</div>
									<div class="fecha"><?php echo date('Y-m-d H:i:s'); ?></div>
								</div>
								<div class="seg-right col-xs-9">
									<form id="form_seguimiento">
										{{ csrf_field() }}
										<div class="comentario col-xs-11">
											<textarea class="callout callout-default" name="comentario_seguimiento" id="comentario_seguimiento" style=""></textarea>
										</div>
										<input type="hidden" name="id_pedido_seguimiento" id="id_pedido_seguimiento" value="{{ $pedido->id }}">
										<div class="enviar col-xs-1"><label for="enviar_seguimiento"><i class="fa fa-paper-plane"></i></label><button type="submit" id="enviar_seguimiento" class="hidden">Enviar</button></div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>



			</div>

			<div class="col-xs-12 col-lg-6 " style="margin-bottom: 20px;">

				<div class="panel-group">
				  <div class="panel panel-default">
				    <div class="panel-heading ">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" href="#collapse-incidencia"><p class="lead">Historial incidencia:</p></a>
				      </h4>
				    </div>
				    <div id="collapse-incidencia" class="panel-collapse collapse">
							<table class="table table-striped">
								<thead>
									<tr>
										<th class="table-check"><input type="checkbox" class="flat-red check-all" name='check_all' value='all'></th>
										<th>Motivo</th>
										<th>Gestión</th>
										<th>Estado</th>
										<th>Detalles</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($incidencias as $incidencia)
										<tr class="incidencia-{{$incidencia->id}}">
											<td>{{$incidencia->motivo->nombre}}</td>
											<td>{{$incidencia->gestion->nombre}}</td>
											<td>
												@if($incidencia->estado == 0)
													Cerrada
												@elseif($incidencia->estado == 1)
													Abierta
												@elseif($incidencia->estado == 2)
													Resuelta
												@endif
											</td>
											<td> <a href="/incidencias/detalle/{{$incidencia->id}}">Modificar</a></td>
										</tr>
									@endforeach
								</tbody>
							</table>
							<div class="box-footer">
								<div class="btn btn-warning">
									<a style="color: white" href="/incidencias/nueva/{{$pedido->id}}"><i class="fa fa-plus"></i>&nbsp;Nueva incidencia</a>
								</div>
							</div>


				    </div>
				  </div>
				</div>



			</div>


		<!-- /.row -->

		<!-- this row will not appear when printing -->
		<div class="row no-print">
			<div class="col-xs-12">
				<a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</a>
				<button type="button" id="modificar_pedido" class="btn btn-success pull-right" onclick="window.location.href='{{Url('/pedidos/modificar/'.$pedido->id)}}'"><i class="fa fa-edit"></i> modificar</button>
				<button type="button" id="generar_albaranes_pdf" class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generar albaran PDF</button>
			</div>
		</div>
</section>
<!-- /.box-body -->
<style>
input[name='bultos'] {
    width: 50% !important;
}
div#collapse-incidencia {
    padding: 20px 15px;
}
.lead {
margin-bottom: 20px;
font-size: 16px;
font-weight: 600;
line-height: 1.4;
}
.panel-default>.panel-heading {
color: #000;
background-color: #f4f4f4;
border-color: #480101;
}
.panel-default>.panel-heading.warning{
	background-color: #f9b0b0;
}
select[name=estado_incidencia], select[name=desplegable_mensaje_incidencia], select[name=desplegable_gestion_incidencia] {
width: 155px;
margin-right: 80%;
border-radius: 4px;
}
a[data-toggle=collapse]:hover{
color: black;
}
textarea#mensaje_incidencia, #gestion_incidencia {
margin-bottom: 15px !important;
}
a[data-toggle=collapse] {
color: black;
}
inspector-stylesheet:1
a[data-toggle=collapse]:hover {
color: white !important;
}
select[multiple], select[size] {
height: auto;
width: 20%;
margin: 6px 0px;
border-radius: 6px;
padding: 7px;
}
.panel-title p.lead {
    margin-bottom: 5px !important;
}

</style>
@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{url('/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script>
$(document).ready(function(e){
	$('#generar-reposicion').click(function(){
    $('.loader-dw').show();
  });
	//
	$("[name='pedido']").click(function(){
		if($(this).is(":checked")) {
			$(".num-"+$(this).val()).addClass("subrallado");
		} else {
			$(".num-"+$(this).val()).removeClass("subrallado");
		}
	});
	// cambiar bultos
	$('[id^="set-bultos_"]').click(function(){
		var idped = $( this ).attr("id").split("_")[1];
		var value = $( '#value-bultos_'+idped ).val();
		$.ajax({
			url: "/pedidos/crear_observacion_bultos/"+idped,
			method: "POST",
		data: { "_token": "{{ csrf_token() }}", id: idped, bultos:value}
		}).done(function(mensaje){
			//alert( "Pedido enviado: " + mensaje );
			apprise(mensaje);
		});
	});


	$("#form_seguimiento").submit(function(e){
		e.preventDefault();
		var idped = $( '#id_pedido_seguimiento' ).val();
		var mensaje_seguimiento = $('#comentario_seguimiento').val();
		$('.loader-dw').show();
    //ajax
    $.ajax({
      url: "/pedidos/seguimiento_pedido/"+idped,
      method: "POST",
      data: $("#form_seguimiento").serialize()
    }).done(function(msg){
        //$('.loader-dw').hide();
        //apprise(msg);
				location.reload(true);
    });
	});

	// OnSubmit - Actualizar mediante jquery la incidencia
	$("#form_incidencia").submit(function(e){
		e.preventDefault();
		$('.loader-dw').show();
		$.ajax({
			method: "POST",
			url: "",
			data: $("#form_incidencia").serialize()
		}).done(function(msg) {
			if($('#desplegable_gestion_incidencia').val()== '2'){
				location.href = '/pedidos/duplicar/reposicion/{{$pedido->numero_pedido}}/{{$pedido->origen->referencia}}';
			}else{
				$('.loader-dw').hide();
				apprise(msg);
			}
		});
	});

	// OnSubmit - Actualizar mediante jquery la observacion
	$("#form_observaciones").submit(function(e){
		e.preventDefault();
		$.ajax({
			method: "POST",
			url: "",
			data: $("#form_observaciones").serialize()
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
		 var idped = {{ $pedido->id }};

		 apprise('Generar albarán en A4?', {'verify':true,}, function(r){

			 if(r){
				 apprise('2 copias? ', {'verify':true,}, function(r){
					 if(r){
						  $("#generar_pdf_productos_form").attr('action',"/pedidos/albaran/A4/"+idped);
							$("#generar_pdf_productos_form").submit();
					 }else{
						  $("#generar_pdf_productos_form").attr('action',"/pedidos/albaran/FA4/"+idped);
							$("#generar_pdf_productos_form").submit();
					 }


				 });
			 }else{

				  $("#generar_pdf_productos_form").attr('action',"/pedidos/albaran/etiqueta/"+idped);
					$("#generar_pdf_productos_form").submit();
			 }
		 });




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
