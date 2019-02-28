@extends('layouts.backend')
@section('titulo','Productos > listado')
@section('titulo_h1','Productos')

@section('estilos')
<!-- DataTables -->
<link rel="stylesheet" href="{{url('/plugins/datatables/dataTables.bootstrap.css')}}">
<link rel="stylesheet" href="/css/custom.css">
<style>

#dataTables_productos .incidencia {
	/*background-color: rgba(255, 0, 0, 0.42);*/
    color: #ff0000;
	transition: all 0.5s;
}
.productos > hr {
	margin: 2px;
    border-color: #f4f4f4;
}
.productos {
	font-size: 12px;
}
.productos > hr:last-child {
	display:none;
}
.subrallado {
    background-color: rgba(243,236,18,0.25);
    cursor: pointer;
}

.ver_producto:nth-child(2n) {
	background-color: #f9fafc;
}
.uppercase{
	text-transform: uppercase;
	width: 170px;
}
#generar_excel_pdf{
	clear: left;
	display: block;
}
.generarBox{
	display: none;
}
#mostrarFormulario{
    width: 105px;
    margin-left: 10px;
    padding: 8px 5px;
    margin-bottom: 10px;
}
.rowBotonesExcel{
    padding-left: 0px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #f4f4f4;
}
#checkAllExcel{
    display: block;
    border-bottom: 1px solid #f4f4f4;
    margin-bottom: 12px;
}
</style>
@endsection



@section('contenido')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box DataTableBox">
				<div class="box-header with-border">
				  <h3 class="box-title">Listado de productos</h3>
				  <div class="box-tools pull-right">
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
                <th class="table-check" style="width: auto;"></th>
								<th style="width: auto;">Nombre</th>
                <th style="width: 8%;">Tamaño</th>
                <th style="width: auto;">Color</th>
								<th style="width: auto;">SKU(act)</th>
								<th style="width: auto;">EAN</th>
                <th style="width: 6%;">Stock</th>
                <th style="width: 6%;">Minimo</th>
                <th style="width: 6%;">Pedido</th>
								<th style="width: 20%;">Opciones</th>
							</tr>
              <tr class="filtro-admin">
							<form id="filtros_datatable" method="get">
								<input class="form-control input-sm" style="display:none;" type="text" name="page" placeholder="pagina" value="{{$listado_productos->currentPage()}}">
								<th class="table-check" style="width: auto;"><input type="checkbox" class="flat-red" name='check_all' value='all'></th>
								<th style="width: auto;">
									<input id="nombre" name="nombre" type="text" placeholder="Nombre" class="form-control input-md" value="{{@$_GET['nombre']}}">
								</th>
                <th style="width: auto;">
									<input id="largo" name="largo" type="text" placeholder="Tamaño" class="form-control input-md" value="{{@$_GET['ancho']}}">
								</th>
                <th style="width: auto;">
									<input id="color" name="color" type="text" placeholder="Color" class="form-control input-md" value="{{@$_GET['color']}}">
								</th>
								<th style="width: auto;">
									<input id="skuAct" name="skuAct" type="text"  placeholder="SKU(act)" class="form-control input-md" value="{{@$_GET['skuAct']}}">
								</th>
                <th style="width: auto;">
									<input id="ean" name="ean" type="text"  placeholder="ean" class="form-control input-md" value="{{@$_GET['ean']}}">
								</th>
								<th style="width: auto;"></th>
                <th style="width: auto;"></th>
                <th style="width: auto;"></th>
								<th style="width: auto;">
									<button type="submit" style="float: right; margin-right:  10px" class="btn btn-primary btn-sm">FILTRAR</button>
								</th>
							</form>
							</tr>

						</thead>
						<tbody id="dataTables_productos">
							@forelse($listado_productos as $keyProductos => $producto)
								<tr class="ver_producto num-{{$producto->id}}" id="num-{{$producto->id}}" @if($producto->esperaStock != 0) style="background: #fff0df;" @endif >
									<td class="table-check">
										<input type="checkbox" class="flat-red" name='producto' value='{{$producto->id}}'>
									</td>



									<td style="width: auto;">@if($producto->esperaStock != 0) <i class="fa fa-truck"></i> @endif {{$producto->nombre}}</td>
                  <td style="width: auto;">{{$producto->largo}}</td>
                  <td style="width: auto;">{{$producto->color}}</td>
									<td style="width: auto;">{{$producto->skuActual}}</td>
                  <td class="td_codigoean_producto" style="width: auto;">{{$producto->ean}}</td>
                  <td class="td_stock_producto" style="width: auto;"><input name="stock" type="number"class="form-control input-md" value="{{$producto->stock}}"></td>
                  <td class="td_stock_control" style="width: auto;"><input name="stockControl" type="number"class="form-control input-md" value="{{$producto->stockControl}}"></td>
                  <td class="td_stock_pedido" style="width: auto;"><input name="pedidoMinim" type="number"class="form-control input-md" value="{{$producto->pedidoMinim}}"></td>
                  <td style="width: auto;">
                    <button type="button" class="btn btn-block btn-info btn-sm guardarStockProducto" style="width:50%;float:left"><i class="fa fa-floppy-o"></i> Guardar</button>
                    <button type="button" class="btn btn-block btn-default btn-sm actualizarStockRecibido" style="width:50%;float:left;margin-top:0px"><i class="fa fa-refresh"></i> Recibido</button>

                  </td>
								</tr>
							@empty
								<p>No hay datos.</p>
							@endforelse
						</tbody>
					</table>
				</div>

					<a href="/productos/generar_stock_web" class="btn btn-success pull-left"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Generar csv</a>
          <a href="/productos/stock_web" target="ventana" class="iframe-ventana btn btn-default pull-left">subir csv</a>

				<!-- /.box-body -->
				<div class="box-footer clearfix">
					<ul class="pagination pagination-sm no-margin pull-right">
						{!! $listado_productos->appends($_GET)->links() !!}

					</ul>
				</div>
				<div class="box-footer clearfix generarBox">
				<form id="generar_albaranes_pdf_form" method="post"  action="{{Url(''.'/productos/albaranes')}}">
					{{ csrf_field() }}
					<input type="hidden" id="ids" name="ids" value="empty"/>
				</form>

				<form id="generar_excel_form" method="post"  action="{{Url(''.'/productos/gen_excel')}}">
					{{ csrf_field() }}
					<input type="hidden" id="ids_e" name="ids" value="empty"/>
					<label> Marcar todo: </label>
						<input id="checkAllExcel" type="checkbox" class="checkboxExcelAll" name="check_all_excel" value="all"/>
					<table class="col-md-12 tableFotos">
						<tr>
							<td class="uppercase">Foto Principal</td>
								<td> <input type="checkbox" class="checkboxExcel" name="E_fotoPrincipal" value="fotoPrincipal"></td>
						</tr>
					</table>
					@php ($contadorTablas = 0)
					@php ($contadorTablas_name = 0)
					@forelse($listado_productos[0] as $keyProductos => $producto)
						@if ($contadorTablas == 0)
							<table class="col-md-2">
						@endif
							<tr>
								<td class="uppercase">{{$keyProductos}}</td>
								<td> <input type="checkbox" class="checkboxExcel" name="E_{{$keyProductos}}" value="{{$keyProductos}}"></td>
							</tr>
						@php ($contadorTablas = $contadorTablas+1)
						@php ($contadorTablas_name = $contadorTablas_name+1)
						@if ($contadorTablas == 5)
							</table>
							@php ($contadorTablas = 0)
						@endif
					@empty
						<p>No hay campos.</p>
					@endforelse
					</table>
				</form>
				<br>

			</div>
        <!-- /.box -->
        </div>
    </div>
    <!-- /.row -->
    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#iframe-pedido" id="abrir-iframe" style="display:none">abrir iframe</button>
    <div class="modal fade" id="iframe-pedido" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-body">
              <iframe name=ventana></iframe>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection

@section('scripts')
<!-- DataTables -->

<script src="{{url('/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{url('/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script>
$(document).ready(function(){


	/* Eliminar pedido */
	$('[id^="eliminar-pedido_"]').click(function(){
		var idped = $( this ).attr("id").split("_")[1];
		apprise('Eliminar definitivamente el pedido?', {'verify':true}, function(r){
			if(r){
				$.ajax({
					url: "/pedidos/eliminar/"+idped,
					method: "POST",
				data: { "_token": "{{ csrf_token() }}", id: idped}
				}).done(function(mensaje){
					//alert( "Pedido enviado: " + mensaje );
					apprise(mensaje);
				});

			} // final if
		});
	});

	/* Al checkear el input global, marcamos todos y desmarcamos al uncheck. */
	$("[name='check_all']").click(function(){
		 if($(this).is(":checked")) {
			//var checkarray = $("[name='pedido']:checked").serializeArray();
			$("[name='producto']").each(function(){
				if(!$(this).is(":checked")) {
					$(".num-"+$(this).val()).addClass("subrallado");
					$(this).click();
				}
            });
			//$("[name='pedido']").click();
		} else {
			$("[name='producto']").each(function(){
				if($(this).is(":checked")){
					$(".num-"+$(this).val()).removeClass("subrallado");
					$(this).click();
				}
            });
		}
	});

	/* Al seleccionar cada pedido de forma independiente, marcamos y añadimos o eliminamos clase subrallado, */
	$("[name='producto']").click(function(){
		if($(this).is(":checked")) {
			$(".num-"+$(this).val()).addClass("subrallado");
		} else {
			$(".num-"+$(this).val()).removeClass("subrallado");
		}
	});

	$.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
	/* Al clicar sobre el botón, importamos albaranes marcados ( si los hay ) mediante ajax y retorna un pdf. */
	$("#generar_albaranes_pdf").click(function(){

		 var arrayProductos = $("[name='producto']").serializeArray();
		 $("#ids").val(JSON.stringify(arrayProductos));
		 $("#generar_albaranes_pdf_form").submit();
		 /*$.ajax({
			url: "/pedidos/albaranes",
			type:'POST',
			data:{ ids:arrayProductos, "_token":"{{ csrf_token() }}" }
		}).done(function(pdf){
			window.open(pdf,'_blank');

		});*/
	});

	$("#generar_excel_pdf").click(function(){
		 var arrayProductos = $("[name='producto']").serializeArray();
		 $("#ids_e").val(JSON.stringify(arrayProductos));
		 $("#generar_excel_form").submit();
		 /*$.ajax({
			url: "/pedidos/albaranes",
			type:'POST',
			data:{ ids:arrayProductos, "_token":"{{ csrf_token() }}" }
		}).done(function(pdf){
			window.open(pdf,'_blank');

		});*/
	});
	$("#mostrarFormulario").click(function(){
		if($(".generarBox").css("display")=="block") $(".generarBox").css("display","none");
		else $(".generarBox").css("display","block");
	});
	$("#checkAllExcel").click(function(){
		$(".checkboxExcel").each(function(){
			$(this).click();
		});
	});
});
productos_amazon_carrito = new Array();
$(".agregarCarrito").click(function(){
  productoSelected = $(this).parent().parent();

  id = productoSelected.find(".input_id_producto").val();
  cantidad = productoSelected.find(".input_cantidad_producto").val();
  codigo_ean = productoSelected.find(".td_codigoean_producto").html();
  nombre = productoSelected.find(".td_nombre_producto").html();
  referencia = productoSelected.find(".td_referencia_producto").html();
  if(cantidad=='') cantidad = 0;
  productos_amazon_carrito.push({"id_producto":id,"cantidad_producto":cantidad,"codigo_ean":codigo_ean, "nombre_producto":nombre, "referencia_producto":referencia});
  $("#productos_amazon_carrito").val(JSON.stringify(productos_amazon_carrito));
  $("#carrito_compra_form").submit();
});
</script>
@endsection
