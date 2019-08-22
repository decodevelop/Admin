@extends('layouts.backend')
@section('titulo','Detalles '.$cliente->nombre)
@section('titulo_h1','Clientes')
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
          <i class="fa fa-briefcase"></i> Detalles de  {{$cliente->nombre_apellidos}}
        </h2>
      </div>
    </div>

    <div class="col-xs-12 col-lg-6">
      <table class="table table-clientes">
        <tbody>
          <tr>
            <td class="text-left" style="width:20%"><strong>ID:</strong></td>
            <td class="text-left"><span>{{$cliente->id}}</span></td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="border-top: 0px;"></td>
            <td class="text-left" style="border-top: 0px;"><strong>Datos Facturación</strong></td>
          </tr>
          <tr>
            <td class="text-left"><strong>Nombre:</strong></td>
            <td class="text-left"><span>{{$cliente->nombre_apellidos}}</span></td>
          </tr>
          <tr>
            <td class="text-left"><strong>E-Mail:</strong></td>
            <td class="text-left"><span>{{$cliente->email_facturacion}}</span></td>
          </tr>
          <tr>
            <td class="text-left"><strong>Teléfono:</strong></td>
            <td class="text-left"><span>{{$cliente->telefono}}</span></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="col-xs-12 col-lg-6">
      <table class="table table-clientes">
        <tbody>
          <tr>
            <td class="text-left" style="width:20%"><strong>DNI:</strong></td>
            <td class="text-left"><span>{{$cliente->dni}}</span></td>
          </tr>
          <tr>
            <td style="height: 10px;"></td>
            <td style="height: 10px;"></td>
          </tr>
          <tr>
            <td style="border-top: 0px;"></td>
            <td class="text-left" style="border-top: 0px;"><strong>Datos Envío</strong></td>
          </tr>
          <tr>
            <td class="text-left"><strong>Nombre:</strong></td>
            <td class="text-left"><span>{{$cliente->nombre_envio}}</span></td>
          </tr>
          <tr>
            <td class="text-left"><strong>E-Mail:</strong></td>
            <td class="text-left"><span>{{$cliente->email}}</span></td>
          </tr>
          <tr>
            <td class="text-left"><strong>Teléfono:</strong></td>
            <td class="text-left"><span>{{$cliente->telefono}}</span></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="row no-print">
      <div class="col-xs-12">
        <button type="button" id="modificar_cliente" class="btn btn-success pull-right" onclick="window.location.href='{{Url('/clientes/modificar/'.$cliente->id)}}'">
          <i class="fa fa-edit"></i> Modificar
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <p class="page-header" style="font-size: 18px;">
          <i class="fa fa-map-marker"></i> Direcciones
        </p>
      </div>
    </div>

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th class=text-left style="">Dirección envío</th>
              <th class=text-left style="">Dirección facturación</th>
              <th class=text-right style="width:20%;padding-right: 25px;">Opciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($direcciones as $d)
              <tr>
                <td class="text-left" style="vertical-align:top!important">
                  {{$d->direccion_envio}}<br>
                  {{$d->ciudad_envio}} ({{$d->estado_envio}}), {{$d->pais_envio}} {{$d->cp_envio}}
                </td>

                <td class="text-left" style="vertical-align:top!important">
                  {{$d->direccion_facturacion}}<br>
                  {{$d->ciudad_facturacion}} ({{$d->estado_facturacion}}), {{$d->pais_facturacion}} {{$d->cp_facturacion}}
                </td>

                <td class="text-left" style="vertical-align:top!important">
                  <div data-placement="top" data-toggle="tooltip" title="Eliminar" class="pull-right">
                    <button type="button" id="eliminarButton" class="btn btn-github" data-toggle="modal" data-target="#confirmacion_modal_pers_{{$d->id}}" style="margin-left: 10px;">
                      <i class="fa fa-trash"></i>
                    </button>
                  </div>
                  <a href="/clientes/{{$cliente->id}}/direccion/modificar/{{$d->id}}">
                    <button data-placement="top" data-toggle="tooltip" title="Editar" type="button" id="editarButton" class="btn btn-primary pull-right">
                      <i class="fa fa-edit"></i>
                    </button>
                  </a>
                </td>
              </tr>
              <!-- Modal -->
              <div class="modal fade" id="confirmacion_modal_pers_{{$d->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h3 class="modal-title" id="confirmacion_modalLabel">Confirmación</h3>
                    </div>
                    <div class="modal-body">
                      <h5>¿Estás seguro de que desea eliminar el Personal <strong>{{$d->id }}</strong>?</h5>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;">Cancelar</button>
                      <a href="/clientes/{{$cliente->id}}/direccion/eliminar/{{$d->id}}"><button type="button" class="btn btn-primary">Sí, estoy seguro</button></a>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Modal End -->
            @endforeach
            <tr>
              <td colspan="5">
                <a href="/clientes/{{$cliente->id}}/direccion/nueva">
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

  .table-clientes td {
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
