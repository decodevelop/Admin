jQuery(document).ready(function($){

  // Función de eventos scroll
  $(window).scroll(scrollDown);
  // --------------------------

  $('tr[id^="cantidad_"]').click(suma_cantidad);

  $('#generar-reposicion').click(function(){
    $('.loader-dw').show();
  });
  $('a[title="Duplicar"]').click(function(){
    $('a[title="Duplicar"]').css('pointer-events','none');
    $('.loader-dw').show();
  });

  if($('select[name=o_csv]').val()=='NONE'){
    $('#button_submit_import').addClass('desactivate');
  }
  $('select[name=o_csv]').change(function(){
		if($(this).val()=='NONE'){
			$('#button_submit_import').addClass('desactivate');
		}else{
			$('#button_submit_import').removeClass('desactivate');
		}

	});


    $('.more_files').click(function(){
      $(".add_import_files").append('<div class="new_file"><label class="remove_file"><i class="fa fa-minus"></i></label><input type="file" name="csv[]" /></div>');
      $('.remove_file').click(function(){
        //alert($(this).attr('class'));
        $(this).parent().remove();
      });
    });

    $('.ver_producto').click(function(){
      var classPrd = $( this ).attr("class").split(" ")[1];
      $("."+classPrd + ' .input_id_producto').click();
    });

    $('.select-prd').click(function(){
      var classPrd = $( this ).attr("class").split(" ")[1];

      $("."+classPrd + ' .input_id_producto').click();
    });

    $('.ver_pedido').click(function(){
      //alert($( this ).attr("class").split(" ")[1]);
      var classPrd = $( this ).attr("class").split(" ")[1];
      //alert(classPrd);
      $(".table-check."+classPrd + ' input').click();
    });

    /*$('tr:first-child').dblclick(function(){
      $('.check-all').click();
    });*/

    $('table').dblclick(function(){
      $('.check-all').click();
    });

    // Botones para modificar, guardar y eliminar productos de la tabla de amazon
    $('.modificarProducto').click(modificar_producto_amazon);
    $('.guardarProducto').click(guardar_producto_amazon);
    $('.borrarProducto').click(borrar_producto_amazon);
    // ========================================================================
    propagationStop();
    eliminarIndividual();
    //==============================

    $('.guardarStockProducto').click(guardar_stock_producto);
    $('.actualizarStockRecibido').click(comanda_stock_recibida);
    //==============================
    $('#gen_albaran').click(function(){
      $('#form_csv').attr("action", "/amazon/gen_albaran_amazon");
      $('#form_csv').submit();
    });
    $('#gen_etiquetas').click(function(){
      $('#form_csv').attr("action", "/amazon/importar_csv_amazon_subida_new");
      $('#form_csv').submit();
    });
    //==========================
    $(".iframe-ventana").click(function(){
      $("#iframe-pedido").modal('show');
    });

});
function guardar_producto_amazon(){
  var idProducto = $(this).parent().parent().attr('id');
  var ean = $('#'+idProducto+" .td_codigoean_producto div").text();
  producto_amazon = new Array();

  nombre = $('#'+idProducto+' .td_nombre_producto input').val();
  referencia = $('#'+idProducto+' .td_referencia_producto input').val();
  codigo_ean = $('#'+idProducto+' .td_codigoean_producto input').val();
  asin = $('#'+idProducto+' .td_asin_producto input').val();

  producto_amazon.push({"nombre":nombre, "referencia":referencia, "codigo_ean":codigo_ean, "asin":asin, "ean_original":ean });

  $('.loader-dw').show();
  $.ajax({
    url: "/amazon/modificarProducto",
    method: "GET",
    data: {producto_amazon : JSON.stringify(producto_amazon)}
  }).done(function(){
      $('.loader-dw').hide();
      modificar_informacion_producto_amazon(idProducto);
      apprise("Producto actualizado correctamente");
  });
}
function borrar_producto_amazon(){
  var idProducto = $(this).parent().parent().attr('id');
  var ean = $('#'+idProducto+" .td_codigoean_producto div").text();
  apprise('Eliminar definitivamente el pedido?', {'verify':true}, function(r){
    if(r){
      $('.loader-dw').show();
      $.ajax({
        url: "/amazon/borrarProducto/"+ean,
        method: "GET",
        //data: {producto_amazon : JSON.stringify(producto_amazon)}
      }).done(function(){
          $('.loader-dw').hide();
          $('#'+idProducto).remove();
          apprise("Producto borrado correctamente");
      });
    }
  });

}
function modificar_producto_amazon(){
  var idProducto = $(this).parent().parent().attr('id');
  $(this).hide();
  $('#'+idProducto+' td div').hide();
  $('#'+idProducto+' .borrarProducto').show();
  $('#'+idProducto+' .guardarProducto').show();
  $('#'+idProducto+' .agregarCarrito').hide();

  var arrayTd = new Array("td_nombre_producto", "td_referencia_producto", "td_codigoean_producto", "td_asin_producto");

  arrayTd.forEach(function(element) {
    $('#'+idProducto+" ."+element+" input").show();
  });
}

function modificar_informacion_producto_amazon(idProducto){
  var arrayTd = new Array(".td_nombre_producto", ".td_referencia_producto", ".td_codigoean_producto", ".td_asin_producto");

  arrayTd.forEach(function(element) {
    $('#'+idProducto+" "+element+" div").empty().append($('#'+idProducto+" "+element+" input").val());
    $('#'+idProducto+" "+element+" input").hide();
    $('#'+idProducto+" "+element+" div").show();
  });

  $('#'+idProducto+' td div').show();
  $('#'+idProducto+' .agregarCarrito').show();
  $('#'+idProducto+' .modificarProducto').show();
  $('#'+idProducto+' .borrarProducto').hide();
  $('#'+idProducto+' .guardarProducto').hide();

}

function propagationStop(){
  $('input').dblclick(function(event){
    event.stopPropagation();
  });
  $('input').click(function(event){
    event.stopPropagation();
  });
  $('.modificarProducto').dblclick(function(event){
    event.stopPropagation();
  });
  $('.modificarProducto').click(function(event){
    event.stopPropagation();
  });
  $('.ver_pedido a').click(function(event){
    event.stopPropagation();
  });
  $('.ver_pedido button').click(function(event){
    event.stopPropagation();
  });
  $('.ver_pedido a').dblclick(function(event){
    event.stopPropagation();
  });
  $('.ver_pedido button').dblclick(function(event){
    event.stopPropagation();
  });

}
function eliminarIndividual(){
  $('.eliminarIndividual').click(function(){
  var ean = $( this ).attr("class").split(" ")[1];
  $('.loader-dw').show();
  $.ajax({
    url: "/amazon/eliminarIndividual/"+ean,
    method: "GET",
    //data: {"_token": "{{ csrf_token() }}", productos_amazon_carrito : JSON.stringify(productos_amazon_carrito)}
  }).done(function(carrito_final){
      $('.loader-dw').hide();
      $('.eliminarIndividual.'+ean).parent().parent().fadeOut('fast','linear',function(){this.remove()});
      $('#btnCarrito').empty().append("<i class='fa fa-shopping-cart'></i> Carrito (" + carrito_final + ")");
      //apprise(carrito_final);
  });
});

}
function eliminarIndividualCampana(){
  $('.eliminarIndividualCampana').click(function(){
  var ean = $( this ).attr("class").split(" ")[1];
  var id_campana = $('#id_campana').val();
  console.log(id_campana);
  $('.loader-dw').show();
  $.ajax({
    url: "/campanas/eliminarIndividual/"+id_campana+"/"+ean,
    method: "GET",
    //data: {}
  }).done(function(carrito_final){

       location.reload();
       //$('.loader-dw').hide();
      $('.eliminarIndividualCampana.'+ean).parent().parent().fadeOut('fast','linear',function(){this.remove()});
      $('#btnCarrito').empty().append("<i class='fa fa-shopping-cart'></i> Carrito (" + carrito_final + ")");
      //apprise(carrito_final);
  });
});

}
function remove_div(){
  alert($(this).attr('class'));
  //$(this).parent().remove();
}
function admindw(){
  $('.skin-blue .main-header .navbar').attr('style','background-color: #3c8dbc !important');
  $('.skin-blue .main-header .logo').attr('style','background-color: #367fa9 !important');
}
function scrollDown(){
  var scroll = $(window).scrollTop();//Obtenemos la posicion del "scrolling"

  // Botón carrito amazon y menú del carrito

    if(scroll > 180){
      $('.carritoImprimir').addClass('scrolled'); //Clase añadida en /public/css/custom.css
      $('#btnCarrito').addClass('scrolled');
    }else{
      $('.carritoImprimir').removeClass('scrolled'); //Clase añadida en /public/css/custom.css
      $('#btnCarrito').removeClass('scrolled');
    }

  //-------------------------------------------
}

function guardar_stock_producto(){
  var idProducto = $(this).parent().parent().attr('id');
  var ean = $('#'+idProducto+" .td_codigoean_producto").text();
  var stock = $('#'+idProducto+" .td_stock_producto input").val();
  var stockControl = $('#'+idProducto+" .td_stock_control input").val();
  var pedidoMinim = $('#'+idProducto+" .td_stock_pedido input").val();
  producto = new Array();

  producto.push({"stock":stock,"stockControl":stockControl,"pedidoMinim":pedidoMinim,"codigo_ean":ean});

  $('.loader-dw').show();
  $.ajax({
    url: "/productos/guardar_stock",
    method: "GET",
    data: {producto : JSON.stringify(producto)}
  }).done(function(){
      $('.loader-dw').hide();
      $('#'+idProducto).css('background','#fff0df');
      //modificar_informacion_producto_amazon(idProducto);
      apprise("Producto actualizado correctamente");
  });
}
function comanda_stock_recibida(){
  var idProducto = $(this).parent().parent().attr('id');
  var ean = $('#'+idProducto+" .td_codigoean_producto").text();
  var stock = $('#'+idProducto+" .td_stock_producto input").val();
  var stockControl = $('#'+idProducto+" .td_stock_control input").val();
  var pedidoMinim = $('#'+idProducto+" .td_stock_pedido input").val();
  producto = new Array();

  producto.push({"stock":stock,"stockControl":stockControl,"pedidoMinim":pedidoMinim,"codigo_ean":ean});

  $('.loader-dw').show();
  $.ajax({
    url: "/productos/stock_recibido",
    method: "GET",
    data: {producto : JSON.stringify(producto)}
  }).done(function(){
      $('.loader-dw').hide();
      //modificar_informacion_producto_amazon(idProducto);
      $('#'+idProducto+" .td_stock_producto input").val(parseInt(stock)+parseInt(pedidoMinim));
      $('#'+idProducto).css('background','#f9fafc');
      apprise("Producto actualizado correctamente");
  });
}

function suma_cantidad(){
  var suma = parseInt($('#suma').text());
  var id =   $(this).attr('id');
  //alert($(this).attr('id'));
  if($(this).hasClass("subrallado")){
    suma = suma - parseInt($("#suma_"+id).text());
    $(this).removeClass("subrallado");
  }else{
      suma = suma + parseInt($("#suma_"+id).text());

    $(this).addClass("subrallado");
  }
  $('#suma').empty().append(suma);
}
