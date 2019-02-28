@extends('layouts.backend')
@section('titulo','Detalles pedido número')
@section('estilos')
  <style>
  input#buscar_producto {
    width: 100%;
    height: 40px;
}
  tr.cambio_palet td a {
    text-align: center;
    width: 100%;
  }
  tr.cambio_palet td {
    padding: 10px;
  }
    div#eliminar_producto {
      text-align: center;
      padding: 6px;
      background: #b7b7b7;
      width: 50%;
      margin: auto;
      border-radius: 2px;
    }
    .producto_palet {
      height: 35px;
      background: #cdf3ef;
      border: 1px #969696;
      border-style: dashed;
    }
    .producto_palet td,.producto_palet_fijo td {
       text-align: center;
       padding: 5px 10px;
    }
    .producto_palet_fijo {
        height: 35px;
        background: #ececec;
        border: 1px #969696;
        border-style: solid;
    }
  </style>
@endsection
@section('contenido')
<section class="invoice">
	<!-- title row -->
	<div class="row">
		<div class="col-xs-12">
			<h2 class="page-header">
				<i class="fa fa-globe"></i> Detalles de pedido seleccionado <small class="pull-right"></small>
			</h2>
		</div>
	</div>
	<!-- /.box-header -->
		<!-- info row -->
		<div class="row invoice-info">

      <div class="col-xs-6 table-responsive">
        <p class="lead">PALET A MODIFICAR</p>
        <div class="row invoice-info">
    		   <table class="table table-bordered">
             <thead>
               <tr>
                 <th>Referencia</th>
                 <th>Productos</th>
               <tr>
             </thead>
             <tbody>

    						 <tr class="ver_palets">
    							 <td> {{$palet->referencia}}</td>
    							 <td>
    								 <table class="tabla_producto stackDrop">

    									 @foreach ($palet->productos_palets as $productos_palets)
    										 <tr  id="productoPalet_{{$productos_palets->id}}" class="producto_palet" >
    											 <td>{{$productos_palets->producto->producto->nombre}}({{$productos_palets->cantidad}}) - {{$productos_palets->producto->producto->referencia}}</td>
    										 </tr>
    									 @endforeach

    								 </table>

    							 </td>

    						 </tr>


             </tbody>
           </table>
           <div class="eliminar_producto" id="eliminar_producto">
             <i class="fa fa-trash"></i>
           </div>
    		</div>

      </div>

      <div class="col-xs-6 table-responsive">
        <p class="lead">AÑADIR A PALET:
          <select class="select_palets " name="select_palets" id="select_palets">
            <option value="default">palet...</option>
            @foreach ($palets as $palet_s)
              <option value="{{$palet_s->id}}" {{($palet_s->id == $palet->id) ? 'hidden' : ''}}>{{$palet_s->referencia}}</option>
            @endforeach
          </select>
        </p>

        <div class="row invoice-info">
    		   <table class="table table-bordered">
             <thead>
               <tr>
                 <th>Productos</th>
               <tr>
             </thead>
             <tbody>

    						 <tr class="ver_palets">

    							 <td>
    								 <table class="tabla_producto_añadido stackDrop2">



    								 </table>

    							 </td>

    						 </tr>


             </tbody>
           </table>

    		</div>


      </div>
    </div>
		<!-- /.row -->
    <hr>
		<!-- Table row -->
		<div class="row">

      <div class="col-xs-6">
        <p class="lead">BUSCAR PRODUCTO</p>
        <input type="text" class="form-control" name="buscar_producto" id="buscar_producto" value="">

      </div>

      <div class="col-xs-6">
        <p class="lead">PRODUCTO ENCONTRADOS</p>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th style="width: 10px">Nombre producto</th>
              <th style="width: 10px">Referencia</th>
              <th style="width: 10px">Código barras</th>
              <th style="width: 10px">Restantes</th>
              <th style="width: 10px">Cantidad</th>
              <th style="width: 10px">Opciones</th>
            <tr>

          </thead>
          <tbody id="dataTables_pedidos">

          </tbody>
        </table>

      </div>

			<!-- /.col -->
		</div>
		<!-- /.row -->


		<!-- /.row -->
		<div class="row">
			<div class="col-xs-12">
				<a class="btn btn-default" href=""><i class="fa fa-arrow-left" style="margin-right: 5px;display:none"></i>Volver</a>
				<button type="button" id="guardar_modificaciones" class="btn btn-primary pull-right" style="margin-right: 5px;display:none"><i class="fa fa-save"></i> Finalizar y guardar</button>
			</div>
		</div>
</section>

<!-- /.box-body -->
@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{url('/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script>
$(document).ready(function(e){

  $('#buscar_producto').keyup(function(){
    var buscar_producto = $('#buscar_producto').val();
    var id_campana = {{$palet->id_campana}};
    if(buscar_producto.toString().length > 4){
        $('.loader-dw').show();
      $.ajax({
        url: "{{Url(''.'/campanas/palets/cargarProductosCampana')}}",
        method: "POST",
        data: {"_token": "{{ csrf_token() }}", buscar_producto : JSON.stringify(buscar_producto) , id_campana : JSON.stringify(id_campana)}
      }).done(function(productos_campana){
          $('#dataTables_pedidos').children().remove();
          $('#dataTables_pedidos').append(productos_campana);
          $('.loader-dw').hide();
      });

    }
  });

  $('#select_palets').change(function(){
    var id_producto_palet =   $('#select_palets').val();
    if(id_producto_palet != 'default' ){
      $('.loader-dw').show();
      //ajax
      $.ajax({
        url: "{{Url(''.'/campanas/palets/cargarProductosPalets')}}",
        method: "POST",
        data: {"_token": "{{ csrf_token() }}", id_producto_palet : JSON.stringify(id_producto_palet)}
      }).done(function(productos_nuevo_palet){
          $('.loader-dw').hide();
          $('.tabla_producto_añadido').children().remove();
          $('.tabla_producto_añadido').append(productos_nuevo_palet);
      });
    }
  })
	$( '.producto_palet' ).draggable({
		appendTo: "body",
    cursor: "move",
    helper: 'clone',
    revert: "invalid"
	});
	$(".stackDrop").droppable({
    tolerance: "intersect",
    accept: ".producto_palet",
    activeClass: "ui-state-default",
    hoverClass: "ui-state-hover",
    drop: function(event, ui) {
        $(this).append($(ui.draggable));
    }
  });

  $(".stackDrop2").droppable({
    tolerance: "intersect",
    accept: ".producto_palet",
    activeClass: "ui-state-default",
    hoverClass: "ui-state-hover",
    drop: function(event, ui) {
        $(this).prepend($(ui.draggable));

        var id_producto_palet = $(ui.draggable).attr('id').split("_")[1];
        var id_palet_añadido = $('#select_palets').val();
        $('.loader-dw').show();
        //ajax
        $.ajax({
          url: "{{Url(''.'/campanas/palets/cambioPalet')}}",
          method: "POST",
          data: {"_token": "{{ csrf_token() }}", id_producto_palet : JSON.stringify(id_producto_palet), id_palet_añadido : JSON.stringify(id_palet_añadido) }
        }).done(function(producto_cambiado){
            $('.loader-dw').hide();
            apprise(producto_cambiado);

        });

        $(ui.draggable).draggable("disable");
        $(ui.draggable).removeClass("producto_palet").addClass("producto_palet_fijo");
    }
  });

  $(".eliminar_producto").droppable({
    tolerance: "intersect",
    accept: ".producto_palet",
    activeClass: "ui-state-default",
    hoverClass: "ui-state-hover",
    drop: function(event, ui) {
        $(this).append($(ui.draggable));
        $(ui.draggable).hide();
        var id_producto_palet = $(ui.draggable).attr('id').split("_")[1];

        $('.loader-dw').show();
        //ajax
        $.ajax({
          url: "{{Url(''.'/campanas/palets/eliminarProductoPalet')}}",
          method: "POST",
          data: {"_token": "{{ csrf_token() }}", id_producto_palet : JSON.stringify(id_producto_palet)}
        }).done(function(producto_eliminado){
            $('.loader-dw').hide();
            apprise(producto_eliminado);
        });

    }
  });

  agregarAPalet();
  productos_amazon_carrito = new Array();



});
function agregarAPalet(){
  $(".agregarCarrito").click(function(){
    productos_amazon_carrito = new Array();
    productoSelected = $(this).parent().parent();

    id = productoSelected.find(".input_id_producto").val();
    cantidad = productoSelected.find(".input_cantidad_producto").val();
    id_palet = {{$palet->id}};
    if(cantidad=='') cantidad = 0;
    productos_amazon_carrito.push({"id_producto":id,"cantidad_producto":cantidad, "id_palet":id_palet});

    $('.loader-dw').show();
    //ajax
    $.ajax({
      url: "{{Url(''.'/campanas/palets/addPaletModificado')}}",
      method: "POST",
      data: {"_token": "{{ csrf_token() }}", productos_amazon_carrito : JSON.stringify(productos_amazon_carrito)}
    }).done(function(carrito_final){
        $('.loader-dw').hide();

        apprise(carrito_final);
        location.reload(true);
    });
    //---

    //-----Atiguo--------
  /*  $("#productos_amazon_carrito").val(JSON.stringify(productos_amazon_carrito));
    $("#carrito_compra_form").submit();*/
    //-------------------
  });
}
</script>
@endsection
