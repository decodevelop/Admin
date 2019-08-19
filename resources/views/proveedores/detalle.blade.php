@extends('layouts.backend')
@section('titulo','Detalles '.$proveedor->nombre)
@section('titulo_h1','Proveedores')
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
					<i class="fa fa-briefcase"></i> Detalles de  {{$proveedor->nombre}}
				</h2>
			</div>
		</div>

		<div class="col-xs-12 col-lg-6">
			<table class="table table-proveedores">
				<tbody>
					<tr>
						<td class="text-left" style="width:20%"><strong>ID:</strong></td>
						<td class="text-left"><span>{{$proveedor->id}}</span></td>
					</tr>
					<tr>
						<td class="text-left"><strong>Nombre:</strong></td>
						<td class="text-left"><span>{{$proveedor->nombre}}</span></td>
					</tr>
					<tr>
						<td class="text-left"><strong>E-Mail:</strong></td>
						<td class="text-left"><span>{{$proveedor->email}}</span></td>
					</tr>
					<tr>
						<td class="text-left"><strong>Teléfono:</strong></td>
						<td class="text-left"><span>{{$proveedor->telefono}}</span></td>
					</tr>
					<tr>
						<td class="text-left"><strong>Plazo de entrega:</strong></td>
						<td class="text-left"><span>{{$proveedor->plazo_entrega}}</span></td>
					</tr>
					<tr>
						<td class="text-left"><strong>Plazo de entrega Web:</strong></td>
						<td class="text-left"><span>{{$proveedor->plazo_entrega_web}}</span></td>
					</tr>
					<tr>
						<td class="text-left"><strong>Envío:</strong></td>
						<td class="text-left"><span>{{$proveedor->envio}}</span></td>
					</tr>
					<tr>
						<td class="text-left"><strong>Método de pago:</strong></td>
						<td class="text-left"><span>{{$proveedor->metodo_pago}}</span></td>
					</tr>
					<tr>
						<td class="text-left"><strong>Precio especial campaña:</strong></td>
						<td class="text-left"><span>{{$proveedor->precio_esp_campana}}</span></td>
					</tr>
					<tr>
						<td class="text-left"><strong>Logística:</strong></td>
						<td class="text-left"><span>{{$proveedor->logistica}}</span></td>
					</tr>
					<tr>
						<td class="text-left"><strong>Contrato:</strong></td>
						<td class="text-left">
							<span>{{$proveedor->contrato}}&nbsp;&nbsp;</span>
							@if($proveedor->contrato_pdf)
								<a href="/PDFs/contratos/{{$proveedor->id}}/{{$proveedor->id}}_contrato.pdf" target=_blank>
									<i class="fa fa-file-pdf-o"></i> Ver contrato
								</a>
							@endif
						</td>
					</tr>
					<tr>
						<td class="text-left"><strong>Listo para vender:</strong></td>
						@if ($proveedor->listo_para_vender)
							<td class="text-left"><span style="color:green; font-size:20px;"><i class="fa fa-check"></i></span></td>
						@else
							<td class="text-left"><span style="color:#d80101; font-size:20px;"><i class="fa fa-times"></i></span></td>
						@endif
					</tr>
				</tbody>
			</table>
		</div>

		<div class="col-xs-12 col-lg-6">
			<table class="table table-proveedores">
				<tbody>
					<tr>
						<td class="text-left" style="width:20%"><strong>Última visita:</strong></td>
						<td class="text-left">
							<span>
								@php
								if(isset($proveedor->ultima_visita)){
									$u_visita = explode('-', $proveedor->ultima_visita);
									$proveedor->ultima_visita = $u_visita[2].'/'.$u_visita[1].'/'.$u_visita[0];
								}
								@endphp
								{{$proveedor->ultima_visita}}
							</span>
						</td>
					</tr>
					<tr>
						<td class="text-left"><strong>Vacaciones:</strong></td>
						<td class="text-left">
							<span>
								@php

								if(isset($proveedor->vacaciones_inicio)){
									$v_inicio = explode('-', $proveedor->vacaciones_inicio);
									$proveedor->vacaciones_inicio = $v_inicio[2].'/'.$v_inicio[1].'/'.$v_inicio[0];
								}

								if(isset($proveedor->vacaciones_fin)){
									$v_fin = explode('-', $proveedor->vacaciones_fin);
									$proveedor->vacaciones_fin = $v_fin[2].'/'.$v_fin[1].'/'.$v_fin[0];
								}
								@endphp

								@if(isset($proveedor->vacaciones_inicio) || isset($proveedor->vacaciones_fin))
									{{$proveedor->vacaciones_inicio}} - {{$proveedor->vacaciones_fin}}
								@endif
							</span>
						</td>
					</tr>
					<tr>
						<td class="text-left" style=""><strong>Valoración media:</strong></td>
						<td class="text-left" style="">
							<span style="margin-right:10px">
								@php
								echo round($proveedor->valoracion_media, 2).' / 5';
								@endphp
							</span>

							<span class="rating-stars-def" style="text-align:left!important">
								<span class="rating-stars-container-def">

									@for ($i=0; $i < round($proveedor->valoracion_media); $i++)
										<div class="rating-star-def-act">
											<i class="fa fa-star"></i>
										</div>
									@endfor

									@for ($i=0; $i < 5 - round($proveedor->valoracion_media); $i++)
										<div class="rating-star-def-des">
											<i class="fa fa-star"></i>
										</div>
									@endfor

								</span>
							</span>
						</td>
					</tr>
					<tr>
						<td class="text-left" style="vertical-align: top!important; padding-top:18px"><strong>Horario:</strong></td>
						<td class="text-left" style="vertical-align: top!important; padding-top:18px">
							@if ($horario != null)
								<span>
									<a class="collapse-horario collapsed" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
										@php
										switch (date("l")) {
											case 'Monday':
											echo "Lunes: $horario->lunes";
											break;

											case 'Tuesday':
											echo "Martes: $horario->martes";
											break;

											case 'Wednesday':
											echo "Miércoles: $horario->miercoles";
											break;

											case 'Thursday':
											echo "Jueves: $horario->jueves";
											break;

											case 'Friday':
											echo "Viernes: $horario->viernes";
											break;

											case 'Saturday':
											echo "Sábado: $horario->sabado";
											break;

											case 'Sunday':
											echo "Domingo: $horario->domingo";
											break;
										}
										@endphp
									</a>
								</span>
								<div class="collapse" id="collapseExample">
									<div class="card card-body">
										<div class="horario-cont">
											<table class="horario-table">
												<tbody>
													<tr>
														<td><strong>Lunes </strong></td>
														<td style="text-align: right!important">{{$horario->lunes}}</td>
													</tr>
													<tr>
														<td><strong>Martes </strong></td>
														<td style="text-align: right!important">{{$horario->martes}}</td>
													</tr>
													<tr>
														<td><strong>Miércoles </strong></td>
														<td style="text-align: right!important">{{$horario->miercoles}}</td>
													</tr>
													<tr>
														<td><strong>Jueves </strong></td>
														<td style="text-align: right!important">{{$horario->jueves}}</td>
													</tr>
													<tr>
														<td><strong>Viernes </strong></td>
														<td style="text-align: right!important">{{$horario->viernes}}</td>
													</tr>
													<tr>
														<td><strong>Sábado </strong></td>
														<td style="text-align: right!important">{{$horario->sabado}}</td>
													</tr>
													<tr>
														<td><strong>Domingo </strong></td>
														<td style="text-align: right!important">{{$horario->domingo}}</td>
													</tr>

												</tbody>
											</table>
										</div>
									</div>
								</div>
							@endif
						</td>
					</tr>
					<tr>
						<td class="text-left" style="vertical-align: top!important"><strong>Observaciones:</strong></td>
						<td class="text-left" style="vertical-align: top!important"><div>{!! $proveedor->observaciones !!}</div></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="row no-print">
			<div class="col-xs-12">
				<button type="button" id="modificar_proveedor" class="btn btn-success pull-right" onclick="window.location.href='{{Url('/proveedores/modificar/'.$proveedor->id)}}'">
					<i class="fa fa-edit"></i> Modificar
				</button>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<p class="page-header" style="font-size: 18px;">
					<i class="fa fa-users"></i> Personal de contacto
				</p>
			</div>
		</div>

		<!-- Table row -->
		<div class="row">
			<div class="col-xs-12 table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th class=text-left style="">Cargo</th>
							<th class=text-left style="">Nombre</th>
							<th class=text-left>Correo</th>
							<th class=text-left>Teléfono</th>
							<th class=text-right style="width:20%;padding-right: 25px;">Opciones</th>
						</tr>
					</thead>
					<tbody>
						@php($contP = 0)
						@foreach ($personal as $p)
							@php($contP = $contP + 1)
							<tr>
								<td class="text-left" style="vertical-align:top!important">{{$p->cargo}}</td>
								<td class="text-left" style="vertical-align:top!important">{{$p->nombre}}</td>
								<td class="text-left" style="vertical-align:top!important">{{$p->correo}}</td>
								<td class="text-left" style="vertical-align:top!important">@if($p->telefono != 0){{$p->telefono}}@endif</td>
									<td class="text-left" style="vertical-align:top!important">
										@if($contP > 3)
											<div data-placement="top" data-toggle="tooltip" title="Eliminar" class="pull-right">
												<button type="button" id="eliminarButton" class="btn btn-github" data-toggle="modal" data-target="#confirmacion_modal_pers_{{$p->id}}" style="margin-left: 10px;">
													<i class="fa fa-trash"></i>
												</button>
											</div>
										@endif

										<a href="/proveedores/{{$proveedor->id}}/personal/modificar/{{$p->id}}">
											<button data-placement="top" data-toggle="tooltip" title="Editar" type="button" id="editarButton" class="btn btn-primary pull-right">
												<i class="fa fa-edit"></i>
											</button>
										</a>
									</td>
								</tr>
								<!-- Modal -->
								<div class="modal fade" id="confirmacion_modal_pers_{{$p->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h3 class="modal-title" id="confirmacion_modalLabel">Confirmación</h3>
											</div>
											<div class="modal-body">
												<h5>¿Estás seguro de que desea eliminar el Personal <strong>{{$p->id }}</strong>?</h5>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;">Cancelar</button>
												<a href="/proveedores/{{$proveedor->id}}/personal/eliminar/{{$p->id}}"><button type="button" class="btn btn-primary">Sí, estoy seguro</button></a>
											</div>
										</div>
									</div>
								</div>
								<!-- Modal End -->
							@endforeach
							<tr>
								<td colspan="5">
									<a href="/proveedores/{{$proveedor->id}}/personal/nuevo">
										<button data-placement="top" data-toggle="tooltip" title="Nuevo Personal" type="button" id="verButton" class="btn btn-default pull-right">
											<i class="fa fa-plus"></i>
										</button>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- /.col -->
			</div>

			<div class="row">
				<div class="col-xs-12">
					<p class="page-header" style="font-size: 18px;">
						<i class="fa fa-tags"></i> Rappels
					</p>
				</div>
			</div>

			<!-- Table row -->
			<div class="row">
				<div class="col-xs-12 table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th class=text-left style="">Mínimo</th>
								<th class=text-left style="">Máximo</th>
								<th class=text-left>Porcentaje</th>
								<th class=text-right style="width:20%;padding-right: 25px;">Opciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($rappel as $r)
								<tr>
									<td class="text-left" style="vertical-align:top!important">{{$r->min}}</td>
									<td class="text-left" style="vertical-align:top!important">{{$r->max}}</td>
									<td class="text-left" style="vertical-align:top!important">{{$r->condiciones}} %</td>
									<td class="text-left" style="vertical-align:top!important">
										<div data-placement="top" data-toggle="tooltip" title="Eliminar" class="pull-right">
											<button type="button" id="eliminarButton" class="btn btn-github" data-toggle="modal" data-target="#confirmacion_modal_{{$r->id}}">
												<i class="fa fa-trash"></i>
											</button>
										</div>

										<a href="/proveedores/{{$proveedor->id}}/rappels/modificar/{{$r->id}}">
											<button data-placement="top" data-toggle="tooltip" title="Editar" type="button" id="editarButton" class="btn btn-primary pull-right" style="margin: 0 10px;">
												<i class="fa fa-edit"></i>
											</button>
										</a>
									</td>
								</tr>
								<!-- Modal -->
								<div class="modal fade" id="confirmacion_modal_{{$r->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h3 class="modal-title" id="confirmacion_modalLabel">Confirmación</h3>
											</div>
											<div class="modal-body">
												<h5>¿Estás seguro de que desea eliminar el Rappel <strong>{{$r->id }}</strong>?</h5>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;">Cancelar</button>
												<a href="/proveedores/{{$proveedor->id}}/rappels/eliminar/{{$r->id}}"><button type="button" class="btn btn-primary">Sí, estoy seguro</button></a>
											</div>
										</div>
									</div>
								</div>
								<!-- Modal End -->
							@endforeach
							<tr>
								<td colspan="5">
									<a href="/proveedores/{{$proveedor->id}}/rappels/nuevo">
										<button data-placement="top" data-toggle="tooltip" title="Nuevo Rappel" type="button" id="verButton" class="btn btn-default pull-right">
											<i class="fa fa-plus"></i>
										</button>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-xs-12 col-lg-6" style="margin-bottom: 20px;">
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

											@if ($seg->destacado)
												<input type="radio" id="{{$seg->id}}" class="seguimiento_destacado" onclick="comentario_destacado(this);" name="destacado" value="{{$seg->id_proveedor}}" checked>
												<label for="{{$seg->id}}" class="pull-right"></label>
											@else
												<input type="radio" id="{{$seg->id}}" class="seguimiento_destacado" onclick="comentario_destacado(this);" name="destacado" value="{{$seg->id_proveedor}}">
												<label for="{{$seg->id}}" class="pull-right"></label>
											@endif
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
											<input type="hidden" name="id_pedido_seguimiento" id="id_pedido_seguimiento" value="{{$proveedor->id}}">
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
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#collapse-valoracion"><p class="lead">Valoraciones:</p></a>
								</h4>
							</div>
							<div id="collapse-valoracion" class="col-xs-12 panel-collapse collapse in">
								@if (count($valoraciones) > 0)
									@foreach ($valoraciones as $val)
										<div class="fila-seguimiento col-md-8 col-xs-12 @if($val->id_usuario == Auth::user()->id) propietario  @endif">
											<div class="seg-left col-xs-3">
												<div class="usuario"> <i class="fa fa-user" aria-hidden="true"></i> {{$usuarios[$val->id_usuario-1]->apodo}}</div>
												<div class="fecha">{{$val->created_at}}</div>
											</div>
											<div class="seg-right col-xs-8" style="padding-right:0!important">
												<div class="rating-stars-def" style="text-align:left!important">
													<div class="rating-stars-container-def">

														@for ($i=0; $i < $val->puntuacion; $i++)
															<div class="rating-star-def-act">
																<i class="fa fa-star"></i>
															</div>
														@endfor

														@for ($i=0; $i < (5 - $val->puntuacion); $i++)
															<div class="rating-star-def-des">
																<i class="fa fa-star"></i>
															</div>
														@endfor

													</div>
												</div>

												<div class="comentario">
													<div class="mensaje"> {{$val->comentario}} </div>
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
										<form id="form_valoracion">
											{{ csrf_field() }}
											<div class="rating-stars" style="text-align:left!important">
												<div class="rating-stars-container">
													<div class="rating-star">
														<i class="fa fa-star"></i>
													</div>
													<div class="rating-star">
														<i class="fa fa-star"></i>
													</div>
													<div class="rating-star">
														<i class="fa fa-star"></i>
													</div>
													<div class="rating-star">
														<i class="fa fa-star"></i>
													</div>
													<div class="rating-star">
														<i class="fa fa-star"></i>
													</div>
												</div>

												<input type="number" readonly="readonly" class="rating-value hidden" name="puntuacion" id="rating-stars-value">
											</div>

											<div class="comentario col-xs-11">
												<textarea class="callout callout-default" name="comentario_valoracion" id="comentario_valoracion" style=""></textarea>
											</div>
											<input type="hidden" name="id_valoracion" id="id_valoracion" value="{{$proveedor->id}}">
											<div class="enviar col-xs-1"><label for="enviar_valoracion"><i class="fa fa-paper-plane"></i></label><button type="submit" id="enviar_valoracion" class="hidden">Enviar</button></div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<style>
		.collapse-horario:hover {
			font-weight: bold;
		}

		.collapse-horario:after {
			font-family: 'FontAwesome';
			content: "\f0d8";
			color: #000000;
			font-size: 20px;
			line-height: 12px;
			padding-left: 20px;
		}

		.collapse-horario.collapsed:after {
			font-family: 'FontAwesome';
			content: "\f0d7";
		}

		.horario-cont {
			margin-top: 20px;
			margin-bottom: 20px;
		}

		.horario-table td{
			height: 20px!important;
			text-align: left;
			width: 30%;
			border-bottom: 1px solid #f4f4f4;
			padding: 5px;
		}

		.table-proveedores td {
			height: 57px;
		}

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


		input[type=radio].seguimiento_destacado{
			border: 0;
			clip: rect(0 0 0 0);
			height: 1px;
			margin: -1px;
			overflow: hidden;
			padding: 0;
			position: absolute;
			width: 1px;
		}

		input[type=radio].seguimiento_destacado + label:before{
			font-family: FontAwesome;
			display: inline-block;
			content: "\f08d";
			letter-spacing: 10px;
			font-size: 1.5em;
			color: grey;
			width: 1.4em;
		}

		input[type=radio].seguimiento_destacado:checked + label:before{
			content: "\f08d";
			font-size: 1.5em;
			color: #b00505;
			letter-spacing: 5px;
		}
		</style>
	@endsection

	@section('scripts')
		<!-- DataTables -->
		<script src="{{url('/plugins/datatables/jquery.dataTables.min.js')}}"></script>
		<script src="{{url('/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
		<script>
		$(document).ready(function(e){
			$("#form_seguimiento").submit(function(e){
				e.preventDefault();
				var idped = $( '#id_pedido_seguimiento' ).val();
				var mensaje_seguimiento = $('#comentario_seguimiento').val();

				//console.log($("#form_seguimiento").serialize());
				//console.log(mensaje_seguimiento);

				$('.loader-dw').show();
				//ajax
				$.ajax({
					url: "/proveedores/seguimiento/" + idped,
					method: "POST",
					data: $("#form_seguimiento").serialize()
				}).done(function(msg){
					//$('.loader-dw').hide();
					//apprise(msg);
					location.reload(true);
				});
			});

			$("#form_valoracion").submit(function(e){
				e.preventDefault();
				var idped = $( '#id_valoracion' ).val();
				var mensaje_valoracion = $('#comentario_valoracion').val();
				var puntuacion_valoracion = $('#rating-stars-value').val();

				//console.log($("#form_valoracion").serialize());
				//console.log(mensaje_valoracion);

				$('.loader-dw').show();
				//ajax
				$.ajax({
					url: "/proveedores/valoracion/" + idped,
					method: "POST",
					data: $("#form_valoracion").serialize()
				}).done(function(msg){
					//$('.loader-dw').hide();
					//apprise(msg);
					location.reload(true);
				});
			});
		});

		function	comentario_destacado(destacado){
			//console.log(destacado.value);
			//		destacado.preventDefault();

			$('.loader-dw').show();
			//ajax
			$.ajax({
				url: "/proveedores/seguimiento/" + destacado.value + "/destacado",
				method: "POST",
				data: {"_token": "{{ csrf_token() }}", "destacado": destacado.id}
			}).done(function(msg){
				//$('.loader-dw').hide();
				//apprise(msg);
				location.reload(true);
			});
		}
		</script>
	@endsection
