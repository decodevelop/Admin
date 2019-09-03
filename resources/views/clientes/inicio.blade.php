@extends('layouts.backend')
@section('titulo','Panel Clientes')
@section('titulo_h1','Panel Clientes')

@section('estilos')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{url('/plugins/datatables/dataTables.bootstrap.css')}}">
  <link rel="stylesheet" href="/css/custom.css">
  <style>
  table.tabla_producto {
    width: 100%;
  }
  td.nombre-producto {
    width: 54%;
  }
  td.estado-incidencia, td.estado-envio{
    width: 22%;
  }
  .tabla_producto tr {
    border-bottom: 1px solid #d8cfcf;
  }
  table.tabla_producto td {
    padding: 8px 5px;
  }

  .estado-envio span.label .popover-content {
    text-transform: uppercase;
  }
  #dataTables_pedidos tr:hover {
    border-left: 1px solid #bcb83c;
    border-right: 1px solid #bcb83c;
    background-color: rgba(243,236,18,0.5);
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

  .ver_pedido:nth-child(2n) {
    background-color: #f9fafc;
  }

  /* MEJORAS 24/7/2017 */

  th {
    vertical-align: middle !important;

    text-align: left;
  }

  td {
    text-align: left;
    vertical-align: middle !important;
  }
  .filtro-admin{
    background: #deecf9;
  }


  select.form-control.input-sm.filterProducts {
    width: 100% !important;
    border-radius: 4px !important;
    background: #f1f8ff;
    border: 1px solid #b4d6ea;
  }
  button#set-bultos_4433 {
    margin: 9px 6px 0px 9px !important;
  }
  .botones-pedidos {
    min-width: 140px;
  }

  td.productos {
    /*display: block;*/
    text-align: center;
    padding: 0px;
    vertical-align: middle !important;
    overflow: auto;
    width: 250px !important;
    height: 120px;
    margin: auto;
  }

  .botones-pedidos button {
    width: 38px;
  }
  i.fa.fa-edit {
    width: 12px;
  }
  .button-close {
    background: #f4f4f4 !important;
    color: red !important;
    width: 38px !important;
    height: 34px !important;
  }
  .button-close i {
    display: none;
  }
  .button-close::before{
    font-weight: bold;
    content: 'X';
  }
  #dataTables_pedidos tr:hover {
    border-left: 1px solid #000000;
    border-right: 1px solid #000000;
    background-color: rgba(126, 135, 138, 0.16);
    cursor: pointer;
  }
  .modal-body {
    height: 100px;
  }
  .modal-body > div {
    line-height: 34px;
  }
  span.label a {
    color: white !important;
  }
  span.label .popover {
    color: black !important;
  }
  .incidencia i.fa.fa-exclamation-triangle {
    font-size: 10px;
    color: #ab0000;
  }
  #dataTables_pedidos .incidencia:hover {

    /*background-color: rgba(152, 152, 152, 0.1)!important;*/
    color: black;
  }
  .loader-dw {
    background: rgba(0, 0, 0, 0.68);
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
  }
  .load-modal {
    width: 100%;
    height: 100%;
    position: fixed;
  }
  .load-modal > h1 {
    color: white;
    text-align: center;
    margin-top: -35%;
  }
  .load-modal > img{
    top: 0;
    bottom: 0;
    position: fixed;
    left: 0;
    right: 0;
    margin: auto;
  }
  a.btn.btn-default.btn-md.btn-mrw img {
    -webkit-filter: grayscale(90%);
    -moz-filter: grayscale(90%);
    -ms-filter: grayscale(90%);
    -o-filter: grayscale(90%);
    filter: grayscale(90%);
    filter: Gray();

    -webkit-transition: all 0.2s ease;
    -moz-transition: all 0.2s ease;
    -ms-transition: all 0.2s ease;
    -o-transition: all 0.2s ease;
    transition: all 0.2s ease;
  }
  a.btn.btn-default.btn-md.btn-mrw:hover img {
    -webkit-filter: grayscale(0%);
    -moz-filter: grayscale(0%);
    -ms-filter: grayscale(0%);
    -o-filter: grayscale(0%);
    filter: none;
  }
  div#servicio-mrw {
    font-size: 20px;
    font-weight: bold;
  }
  div#referencia-mrw {
    font-size: 16px;
    font-weight: bold;
    color: #595959;
    padding-top: 4px;
    text-align: right;
  }

  .th-id {width: 10%;}
</style>
@endsection

@section('contenido')
  <section class="content">

    <div class="row">
      <div class="col-md-12">
        <div class="box DataTableBox">

          <div class="box-body">
            <table class="table table-bordered">
              <thead>

                <tr>
                  <th class="th-filtro th-id">ID</th>
                  <th class="th-filtro th-nombre">Nombre</th>
                  <th class="th-filtro th-telefono">Teléfono</th>
                  <th class="th-filtro th-email">E-mail</th>
                  <th class="th-filtro th-opciones text-center" style="width: 150px">Opciones </th>
                </tr>

                <!-- FILTRO -->
                <tr class="filtro-admin">

                  <form id="filtros_datatable" method="get">
                    <input class="form-control input-sm" style="display:none;" type="text" name="page" placeholder="pagina" value="{{$clientes->currentPage()}}">
                    <!-- FILTRO CLIENTE  -->
                    <th>
                      <input class="form-control input-sm filterProducts" type="number" name="id" placeholder="ID" value="@if(isset($filtro['id'])){{$filtro['id']}}@endif">
                    </th>

                    <th>
                      <input class="form-control input-sm filterProducts" type="text" name="nombre" placeholder="Nombre" value="@if(isset($filtro['nombre'])){{$filtro['nombre']}}@endif">
                    </th>

                    <th>
                      <input class="form-control input-sm filterProducts" type="text" name="telefono" placeholder="Teléfono" value="@if(isset($filtro['telefono'])){{$filtro['telefono']}}@endif">
                    </th>

                    <th>
                      <input class="form-control input-sm filterProducts" type="text" name="email" placeholder="E-mail" value="@if(isset($filtro['email'])){{$filtro['email']}}@endif">
                    </th>
                    <!-- END FILTRO CLIENTE -->
                    <th>
                      <button type="submit" class="btn btn-default btn-sm" style="width:100%;">FILTRAR</button>
                    </th>
                  </form>
                </tr>
                <!-- END FILTRO -->

              </thead>

              <tbody id="dataTables_pedidos">

                @forelse($clientes as $key => $cliente)
                  <tr onclick="window.location = '/clientes/detalle/{{$cliente->id}}';">
                    <td>{{$cliente->id}}</td>
                    <td>{{$cliente->nombre_apellidos}}</td>
                    <td>{{$cliente->telefono}}</td>
                    <td>{{$cliente->email}}</td>

                    <td>
                      <a href="/clientes/detalle/{{$cliente->id}}">
                        <button data-placement="top" data-toggle="tooltip" title="Detalle" type="button" id="verButton" class="btn btn-default">
                          <i class="fa fa-eye"></i>
                        </button>
                      </a>

                      <a href="/clientes/{{$cliente->id}}/ver_pedidos">
                        <button data-placement="top" data-toggle="tooltip" title="Ver pedidos" type="button" id="pedidosButton" class="btn btn-default" style="margin: 0 2px;">
                          <i class="fa fa-shopping-bag"></i>
                        </button>
                      </a>

                      <a href="/clientes/modificar/{{$cliente->id}}">
                        <button data-placement="top" data-toggle="tooltip" title="Editar" type="button" id="editarButton" class="btn btn-primary">
                          <i class="fa fa-edit"></i>
                        </button>
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center"><strong>No se han encontrado clientes con los parámetros indicados.</strong></td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="box-footer clearfix">
            <ul class="pagination pagination-sm no-margin pull-right">
              <!-- $paginacion->links('pedidos.pagination',["test" => "test"] ) -->
              {!! $clientes->appends($_GET)->links() !!}

            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('scripts')
  <script src="{{url('/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
  <script src="{{url('/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
  <script>

  /* bootstrap crear pops */
  $('[data-toggle="popover"]').popover();
  $(document).ready(function(){
    $('[name="o_origen_referencia"]').change(function(){
      $('[name="origen_referencia"]').val($('[name="o_origen_referencia"]').val());
      //$('#debuger').val($('[name="o_o_csv"]').val());
    });


    $('[id^="albaran-pedido_"]').click(function(){
      var idped = $( this ).attr("id").split("_")[1];
      apprise('Generar albarán en A4?', {'verify':true,}, function(r){

        if(r){
          apprise('2 copias? ', {'verify':true,}, function(r){
            if(r){
              window.location.href = "/pedidos/albaran/A4/"+idped;
            }else{
              window.location.href = "/pedidos/albaran/FA4/"+idped;
            }


          });
        }else{

          window.location.href = "/pedidos/albaran/etiqueta/"+idped;

        }
      });
    });

    /* Eliminar pedido */
    $('[id^="eliminar-pedido_"]').click(function(){
      var idped = $( this ).attr("id").split("_")[1];
      apprise('Eliminar definitivamente el pedido?', {'verify':true}, function(r){
        if(r){
          $('.loader-dw').show();

          $.ajax({
            url: "/pedidos/eliminar/"+idped,
            method: "POST",
            data: { "_token": "{{ csrf_token() }}", id: idped}
          }).done(function(mensaje){
            //alert( "Pedido enviado: " + mensaje );
            $('.loader-dw').hide();
            $('.num-'+idped).fadeOut('slow','linear',function(){this.remove()});
            apprise(mensaje);
          });

        } // final if
      });
    });

    /* Al clicar sobre enviar pedido, realizamos un ajax para mararlo como enviado, con previa confirmación. */
    $('[id^="enviar-pedido_"]').click(function(){
      var numped = $( this ).attr("id").split("_")[1];
      apprise('Marcar pedido como enviado?', {'verify':true}, function(r){
        if(r){
          apprise('Deseas enviar notificación al cliente?', {'verify':true}, function(r){
            $('.loader-dw').show();
            if(r){
              $(this).prop( "disabled", true );
              $.ajax({
                url: "/pedidos/enviar_pedido/"+numped+"?notificar=si",
                cache: false
              }).done(function(msg){
                var resultado = JSON.parse(msg);
                var mensaje = resultado[0];
                var fecha_envio = resultado[1];

                $(".fecha-envio-"+numped+" > span").removeClass("label-danger").addClass("label-success", {duration:500}).html(''+fecha_envio+'');
                //alert( "Pedido enviado: " + mensaje );
                $('.loader-dw').hide();
                apprise(mensaje);
              });
            } else {
              $(this).prop("disabled", true);
              $.ajax({
                url: "/pedidos/enviar_pedido/"+numped+"?notificar=no",
                cache: false
              }).done(function(msg){
                var resultado = JSON.parse(msg);
                var mensaje = resultado[0];
                var fecha_envio = resultado[1];
                $(".fecha-envio-"+numped+" > span").removeClass("label-danger").addClass("label-success", {duration:500}).html(''+fecha_envio+'');
                //alert( "Pedido enviado: " + mensaje );


                $('.loader-dw').hide();
                apprise(mensaje);
              });
            }
          });

        } // final if
      });
    });

    /* cambiar bultos pedido */
    $('[id^="set-bultos_"]').click(function(){
      var idped = $( this ).attr("id").split("_")[1];
      var value = $( '#value-bultos_'+idped ).val();
      $('.loader-dw').show();
      $.ajax({
        url: "/pedidos/crear_observacion_bultos/"+idped,
        method: "POST",
        data: { "_token": "{{ csrf_token() }}", id: idped, bultos:value}
      }).done(function(mensaje){
        //alert( "Pedido enviado: " + mensaje );
        $('.loader-dw').hide();
        apprise(mensaje);
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

    $("#generar_albaranes_pdf").click(function(){

      var arrayPedidos = $("[name='pedido']").serializeArray();

      if(arrayPedidos.length == 0){
        apprise('Por favor, seleccione al menos un pedido.');
      } else {

        $("#ids").val(JSON.stringify(arrayPedidos));

        apprise('Generar albarán en A4?', {'verify':true,}, function(r){

          if(r){
            apprise('2 copias? ', {'verify':true,}, function(r){
              if(r){
                $("#type").val("A4");
                $("#generar_albaranes_pdf_form").submit();
              }else{
                $("#type").val("FA4");
                $("#generar_albaranes_pdf_form").submit();
              }


            });
          }else{
            $("#type").val("etiqueta");
            $("#generar_albaranes_pdf_form").submit();
          }
        });
      }
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
  var arrayFiltersE = $(".filterProducts").serializeArray();

  $("#ids_e").val(JSON.stringify(arrayPedidos));
  $("#filters_e").val(JSON.stringify(arrayFiltersE));

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
