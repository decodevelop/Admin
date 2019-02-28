@extends('layouts.backend')
@section('titulo','Amazon > listado')
@section('titulo_h1','Amazon')

@section('estilos')
<!-- DataTables -->
<link rel="stylesheet" href="{{url('/plugins/datatables/dataTables.bootstrap.css')}}">
<link rel="stylesheet" href="/css/custom.css">
<style>
#dataTables_pedidos tr:hover {
    border-left: 1px solid #000000;
    border-right: 1px solid #000000;
    background-color: rgba(126, 135, 138, 0.16);
    cursor: pointer;
}
#dataTables_pedidos .incidencia {
	/*background-color: rgba(255, 0, 0, 0.42);*/
    color: #ff0000;
	transition: all 0.5s;
}

#dataTables_pedidos .incidencia:hover {
	border-left:2px solid #ff0000;
	border-right:2px solid #ff0000;
	background-color: rgba(255, 0, 0, 0.10) !important;
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
.agregarCarrito, .guardarProducto, .modificarProducto, .borrarProducto {
    width: 46%;
    float: left;
    display: inline-block;
    margin: 0 0 0 2% !important;
}
.carritoImprimir{
    margin-top: 36px;
    position: absolute;
    right: 0;
    background-color: white;
    border: 1px solid #ddd;
    padding: 25px 25px 25px 10px;
    width: 396px;
    display: none;
}
.carritoListaImprimir{
    overflow: auto;
    max-height: 300px;
    list-style: none;
    padding-left: 22px;
}
.carritoImprimir p:first-child{
    font-weight: bolder;
    font-size: 16px;
    padding-bottom: 5px;
}
.carritoImprimir p{
    padding-left: 22px;
}
.carritoListaImprimir li{
  display: flex;
	padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
}
#btnVaciarListaCarrito,
#btnImprimirListaCarrito{
	width: 38%;
    margin-top: 15px;
}
.botonesCarritoImprimir{
	padding-left: 22px;
}
#btnCarrito{
	margin-top: 9px;
}
.eliminarIndividual{
	float: right;
    padding-left: 5px;
}
.eliminarIndividual i{
	color: red;
}
span.textoLineaCarrito {
    max-width: 320px;
}
.filtro-amazon{
      background: #deecf9;
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
          <a href="{{Url(''.'/amazon/subirExcel')}}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Subir productos mediante excel</a>
					<button id="btnImprimir" type="button" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Imprimir</button>
					<a id="btnSubirFichero" href="{{Url(''.'/amazon/importar_csv_amazon')}}" class="btn btn-default btn-sm"><i class="fa fa-upload" aria-hidden="true"></i> Importar</a>
					<button id="btnCarrito" type="button" class="btn btn-block btn-default btn-sm"><i class="fa fa-shopping-cart"></i> Carrito ({{(session()->exists('productosCarrito') ? count(session('productosCarrito')) : '0')}})</button>
				  </div>
				</div>
				<div class="carritoImprimir">
					<p>Lista:</p>
					@if(session()->exists('productosCarrito') && count(session('productosCarrito')) > 0)
						<ul class="carritoListaImprimir">
							@foreach(session('productosCarrito') as $key => $productoCarrito)
							<li><div class="col-xs-10 textoLineaCarrito">{{$productoCarrito[0]["cantidad_producto"].' x '.$productoCarrito[0]["nombre_producto"].': '.$productoCarrito[0]["codigo_ean"]}}</div>
                  <div class="col-xs-2"><div class="eliminarIndividual {{$productoCarrito[0]['codigo_ean']}}"><i class="fa fa-times"></i></div></div></li>
							@endforeach
						</ul>
						<div class="botonesCarritoImprimir">
							<a id="btnVaciarListaCarrito" href="{{Url(''.'/amazon/eliminarCarrito')}}" class="btn btn-default btn-sm pull-left"><i class="fa fa-trash"></i> Vaciar</a>
							<a id="btnImprimirListaCarrito" href="{{Url(''.'/amazon/imprimirCarritoCompra')}}" type="button" class="btn btn-default btn-sm pull-right"><i class="fa fa-print"></i> Imprimir</a>
						</div>
					@else
            <ul class="carritoListaImprimir">
						<p>No hay productos en el carrito.</p>
            </ul>
            <div class="botonesCarritoImprimir">
							<a id="btnVaciarListaCarrito" href="{{Url(''.'/amazon/eliminarCarrito')}}" class="btn btn-default btn-sm pull-left"><i class="fa fa-trash"></i> Vaciar</a>
							<a id="btnImprimirListaCarrito" href="{{Url(''.'/amazon/imprimirCarritoCompra')}}" type="button" class="btn btn-default btn-sm pull-right"><i class="fa fa-print"></i> Imprimir</a>
						</div>
					@endif
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
								<th style="width: 10px" class="table-check"></th>
								<th style="width: 10px">Nombre</th>
								<th style="width: 10px">Referencia</th>
								<th style="width: 10px">Codigo Ean</th>
								<th style="width: 10px">Asin</th>
								<th style="width: 10px">Cantidad</th>
								<th style="width: 10px">Opciones</th>
							</tr>
              <tr class="filtro-amazon">
							<form id="filtros_datatable" method="get">
								<th style="width: 10px" class="table-check"><input type="checkbox" class="flat-red check-all" name='check_all' value='all'></th>
								<th style="width: 10px">
									<input id="nombre" name="nombre" type="text" placeholder="Nombre" class="form-control input-md">
								</th>
								<th style="width: 10px">
									<input id="referencia" name="referencia" type="text" placeholder="Referencia" class="form-control input-md">
								</th>
								<th style="width: 10px">
									<input id="codigo_ean" name="codigo_ean" type="text" placeholder="Codigo EAN" class="form-control input-md">
								</th>
								<th style="width: 10px">
                  <input id="asin" name="asin" type="text" placeholder="Codigo ASIN" class="form-control input-md">
								</th>
								<th style="width: 10px;text-align: center;" colspan="2">
									<button type="submit" class="btn btn-primary btn-sm">FILTRAR</button>
								</th>
							</form>
							</tr>
						</thead>
						<tbody id="dataTables_pedidos">
							@forelse($listado_productos as $keyProductos => $producto)
								<tr class="ver_producto num-{{$producto->id}}" id="num-{{$producto->id}}">
									<td class="table-check">
										<input type="checkbox" class="flat-red input_id_producto" name='producto' value='{{$producto->id}}'>
									</td>

									<td style="width: 140px" class="td_nombre_producto"><div>{{$producto->nombre}}</div><input type="text" value="{{$producto->nombre}}" class="form-control" style="display:none"></td>
									<td style="width: 10px" class="td_referencia_producto"><div>{{$producto->referencia}}</div><input type="text" value="{{$producto->referencia}}" class="form-control" style="display:none"></td>
									<td style="width: 10px" class="td_codigoean_producto"><div>{{$producto->codigo_ean}}</div><input type="text" value="{{$producto->codigo_ean}}" class="form-control" style="display:none"></td>
									<td style="width: 10px" class="td_asin_producto"><div>{{$producto->asin}}</div><input type="text" value="{{$producto->asin}}" class="form-control" style="display:none"></td>
									<td style="width: 10px">
										<input name="cantidad" type="number" placeholder="Cantidad" class="form-control input-md input_cantidad_producto" value="1" min="1">
									</td>
									<td>
										<button type="button" class="btn btn-block btn-default btn-sm agregarCarrito"><i class="fa fa-plus"></i> Agregar</button>
                    <button type="button" class="btn btn-block btn-default btn-sm modificarProducto"><i class="fa fa-edit"></i> Modificar</button>
                    <button type="button" class="btn btn-block btn-danger btn-sm borrarProducto" style="display:none"><i class="fa fa-trash"></i> borrar</button>
                    <button type="button" class="btn btn-block btn-info btn-sm guardarProducto" style="display:none"><i class="fa fa-floppy-o"></i> Guardar</button>
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
						{!! $listado_productos->appends($_GET)->links() !!}
					</ul>
				</div>
				<div class="box-footer clearfix">
        <a href="{{Url(''.'/amazon/descargar')}}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Descargar excel de todos los productos</a>
				<form id="productos_amazon_formulario" method="post"  action="{{Url(''.'/amazon/imprimirEtiquetas')}}">
					{{ csrf_field() }}
					<input id="productos_amazon_imprimir" type="hidden" name="productos_amazon_imprimir" value="empty">
				</form>
				<form id="carrito_compra_form" method="post" action="{{Url(''.'/amazon/guardarCarrito')}}">
					{{ csrf_field() }}
					<input id="productos_amazon_carrito" type="hidden" name="productos_amazon_carrito" value="empty">
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
	/* Mostrar el carrito */
	$("#btnCarrito").click(function(){
		if($(".carritoImprimir").css("display") == "block") $(".carritoImprimir").css("display","none");
		else $(".carritoImprimir").css("display","block");
	});

	/* Agregar al carrito */
	productos_amazon_carrito = new Array();
	$(".agregarCarrito").click(function(){
    productos_amazon_carrito = new Array();
		productoSelected = $(this).parent().parent();

		id = productoSelected.find(".input_id_producto").val();
		cantidad = productoSelected.find(".input_cantidad_producto").val();
		codigo_ean = productoSelected.find(".td_codigoean_producto div").html();
		nombre = productoSelected.find(".td_nombre_producto div").html();
		referencia = productoSelected.find(".td_referencia_producto div").html();
		if(cantidad=='') cantidad = 0;
		productos_amazon_carrito.push({"id_producto":id,"cantidad_producto":cantidad,"codigo_ean":codigo_ean, "nombre_producto":nombre, "referencia_producto":referencia});


    $('.loader-dw').show();
    //ajax
    $.ajax({
      url: "{{Url(''.'/amazon/guardarCarrito')}}",
      method: "POST",
      data: {"_token": "{{ csrf_token() }}", productos_amazon_carrito : JSON.stringify(productos_amazon_carrito)}
    }).done(function(carrito_final){
        $('.loader-dw').hide();
        $('.carritoListaImprimir').children().remove();
        $('.carritoListaImprimir').append(carrito_final[0]);
        $('#btnCarrito').empty().append("<i class='fa fa-shopping-cart'></i> Carrito (" + carrito_final[1] + ")");
        //apprise(carrito_final);
    });
    //---

    //-----Atiguo--------
  /*  $("#productos_amazon_carrito").val(JSON.stringify(productos_amazon_carrito));
		$("#carrito_compra_form").submit();*/
    //-------------------
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

	/* Al dar clic sobre el boton imprimir, recogerá los valores de los productos subrallados y enviará la cantidad y el producto al controlador */
	$("#btnImprimir").click(function(){
		productos_amazon = new Array();
		$(".subrallado").each(function( index ) {
			id = $( this ).find(".input_id_producto").val();
			cantidad = $( this ).find(".input_cantidad_producto").val();
			codigo_ean = $( this ).find(".td_codigoean_producto").html();
			nombre = $( this ).find(".td_nombre_producto").html();
			referencia = $( this ).find(".td_referencia_producto").html();
			if(cantidad=='') cantidad = 0;
			productos_amazon.push({"id_producto":id,"cantidad_producto":cantidad,"codigo_ean":codigo_ean, "nombre_producto":nombre, "referencia_producto":referencia});
		});

		 $("#productos_amazon_imprimir").val(JSON.stringify(productos_amazon));
		 $("#productos_amazon_formulario").submit();
	});
});
</script>
@endsection
