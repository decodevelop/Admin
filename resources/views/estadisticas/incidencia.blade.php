@extends('layouts.backend')
@section('titulo','Estadísticas > Incidencias')
@section('titulo_h1','Incidencias (Sin IVA)')

@section('estilos')
@endsection

@section('contenido')
<?php
?>
<link rel="stylesheet" href="/css/custom.css">
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Filtro por mes</h3>
        </div>
        <div class="box-body">
          <form class="buscar-mes" method="post">
            <div class="col-md-12">
              <div class="col-md-2">
                MES
              </div>
              <div class="col-md-2">
                AÑO
              </div>
              <div class="col-md-1">
                DESCARGAR PDF
              </div>
            </div>
            <div class="col-md-12">
              <div class="col-md-2">
                <select name="mes" id="filtro-mes" class="form-control">
                  <option value="1" {{ ($fecha['mes']==1) ? 'selected' : '' }}>Enero</option>
                  <option value="2" {{ ($fecha['mes']==2) ? 'selected' : '' }}>Febrero</option>
                  <option value="3" {{ ($fecha['mes']==3) ? 'selected' : '' }}>Marzo</option>
                  <option value="4" {{ ($fecha['mes']==4) ? 'selected' : '' }}>Abril</option>
                  <option value="5" {{ ($fecha['mes']==5) ? 'selected' : '' }}>Mayo</option>
                  <option value="6" {{ ($fecha['mes']==6) ? 'selected' : '' }}>Junio</option>
                  <option value="7" {{ ($fecha['mes']==7) ? 'selected' : '' }}>Julio</option>
                  <option value="8" {{ ($fecha['mes']==8) ? 'selected' : '' }}>Agosto</option>
                  <option value="9" {{ ($fecha['mes']==9) ? 'selected' : '' }}>Septiembre</option>
                  <option value="10" {{ ($fecha['mes']==10) ? 'selected' : '' }}>Octubre</option>
                  <option value="11" {{ ($fecha['mes']==11) ? 'selected' : '' }}>Noviembre</option>
                  <option value="12" {{ ($fecha['mes']==12) ? 'selected' : '' }}>Diciembre</option>
                </select>
              </div>
              <div class="col-md-2">
                <select name="any" id="filtro-ano" class="form-control">
                   <option value="2018" {{ ($fecha['any']==2018) ? 'selected' : '' }}>2018</option>
                   <option value="2017" {{ ($fecha['any']==2017) ? 'selected' : '' }}>2017</option>
                   <option value="2016" {{ ($fecha['any']==2016) ? 'selected' : '' }}>2016</option>
                </select>
              </div>
              <div class="col-md-1">
                <input type="checkbox" name="pdf" id="switch" /><label for="switch">Toggle</label>
              </div>
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="col-md-2">
                <button type="submit" name="send" class="form-control">ir</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="col-md-4">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Valor de las incidencias</h3>
          </div>
          <div class="box-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Color</th>
                  <th>Origen</th>
                  <th>Cantidad</th>
                  <th>Valor total</th>
                </tr>
                <?php $total_valor = 0;
                      $total_cantidad = 0; ?>
                @foreach ($tabla_cantidad_valor as $origen => $fila_valor)
                  @if ($fila_valor['cantidad'] > 0)
                    <tr>
                      <td><div style="background-color:{{$fila_valor['color']}};width:20px;height:20px"></div></td>
                      <td>{{$fila_valor['nombre']}}</td>
                      <td>{{$fila_valor['cantidad']}}</td>
                      <td>{{number_format(($fila_valor['valor']) , 2, ',', '.' )}} €</td>
                      <?php $total_valor += $fila_valor['valor'];
                      $total_cantidad += $fila_valor['cantidad']; ?>
                    </tr>
                  @endif
                @endforeach
                <tr>
                  <td colspan="2"><b>Total</b></td>
                  <td>{{$total_cantidad}}</td>
                  <td>{{number_format($total_valor , 2, ',', '.' )}} €</td>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Cantidad incidencias</h3>
          </div>
          <div class="box-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Color</th>
                  <th>Incidencias</th>
                  <th>Cantidad</th>
                  <th>Porcentaje</th>
                </tr>

                @foreach ($cantidad_incidencias as $incidencia => $cantidad_incidencia)
                  @if ($cantidad_incidencia > 0)
                    <tr>
                      @if ($incidencia == 'otros')
                        <td><div style="background-color:#a0a3a5;width:20px;height:20px"></div></td>
                        <td>{{$incidencia}}</td>
                        <td>{{$cantidad_incidencia}}</td>
                        <td>{{number_format((($cantidad_incidencia * 100)/$cantidad_incidencias['total']) , 2, ',', '.' )}}%</td>
                      @elseif ($incidencia == 'total')
                        <td colspan="3"><b>{{$incidencia}}</b></td>
                        <td>{{$cantidad_incidencia}}</td>
                      @elseif ($incidencia == 'solucionados')
                        <td colspan="2"><b>Indice de solucionados</b></td>
                        <td>{{$cantidad_incidencia}}</td>
                        @if($cantidad_incidencias['total'] > 0)
                            <td>{{number_format((($cantidad_incidencia * 100)/$cantidad_incidencias['total']) , 2, ',', '.' )}}%</td>
                        @endif
                      @else
                        <td><div style="background-color:{{$mensaje_incidencia[$incidencia]['color']}};width:20px;height:20px"></div></td>
                        <td>{{$mensaje_incidencia[$incidencia]['tipo']}}</td>
                        <td>{{$cantidad_incidencia}}</td>
                        @if($cantidad_incidencias['total'] > 0)
                          <td>{{number_format((($cantidad_incidencia * 100)/$cantidad_incidencias['total']) , 2, ',', '.' )}}%</td>
                        @endif
                      @endif

                    </tr>
                  @endif
                @endforeach
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Gestión incidencias</h3>
          </div>
          <div class="box-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Color</th>
                  <th>Gestión</th>
                  <th>Cantidad</th>
                  <th>Porcentaje</th>
                </tr>
                @foreach ($cantidad_gestion as $incidencia => $cantidad_incidencia)
                  @if ($cantidad_incidencia > 0)
                    <tr>
                      @if ($incidencia == 'otros')
                        <td><div style="background-color:#a0a3a5;width:20px;height:20px"></div></td>
                        <td>{{$incidencia}}</td>
                        <td>{{$cantidad_incidencia}}</td>
                        <td>{{number_format((($cantidad_incidencia * 100)/$cantidad_gestion['total']) , 2, ',', '.' )}}%</td>
                      @elseif ($incidencia == 'total')
                        <td colspan="3"><b>{{$incidencia}}</b></td>
                        <td>{{$cantidad_incidencia}}</td>
                      @else
                        <td><div style="background-color:{{$mensaje_gestion[$incidencia]['color']}};width:20px;height:20px"></div></td>
                        <td>{{$mensaje_gestion[$incidencia]['tipo']}}</td>
                        <td>{{$cantidad_incidencia}}</td>
                        <td>{{number_format((($cantidad_incidencia * 100)/$cantidad_gestion['total']) , 2, ',', '.' )}}%</td>
                      @endif

                    </tr>
                  @endif
                @endforeach
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="col-md-4">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Grafico valor incidencias</h3>
          </div>
          <div class="box-body">
            <span id="sparkline">&nbsp;</span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Grafico cantidad incidencias</h3>
          </div>
          <div class="box-body">
            <span id="quesitoCantidad">&nbsp;</span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Grafico gestión incidencias</h3>
          </div>
          <div class="box-body">
            <span id="quesitoGestion">&nbsp;</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="col-md-6 class_quesitoCabecerosIncidencias">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Cabeceros vendidos</h3>
          </div>
          <div class="box-body">
              <div class="col-md-12">
                <div class="col-md-6">
                  <table class="table table-bordered">
                    <tr>
                      <td><div style="background-color:#849d9c;width:20px;height:20px"></div></td>
                      <th>Web</th>
                      <td>{{$total_cabeceros['CB']}}</td>
                      @if ($total_cabeceros['CB'] + $total_cabeceros['CC'] != 0)
                        <td>{{number_format((($total_cabeceros['CB'])*100)/($total_cabeceros['CB'] + $total_cabeceros['CC']))}} %</td>
                      @else
                        <td>--</td>
                      @endif
                    </tr>
                    <tr>
                      <td><div style="background-color:#09a8be;width:20px;height:20px"></div></td>
                      <th>Contactos web</th>
                      <td>{{$total_cabeceros['CC']}}</td>
                      @if ($total_cabeceros['CB'] + $total_cabeceros['CC'] != 0)
                        <td>{{number_format((($total_cabeceros['CC'])*100)/($total_cabeceros['CB'] + $total_cabeceros['CC']))}} %</td>
                      @else
                        <td>--</td>
                      @endif
                    </tr>
                  </table>
                </div>
                <div class="col-md-6">
                  <span id="quesitoWebContactos">&nbsp;</span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="col-md-6">
                  <table class="table table-bordered">
                    <tr>
                      <td><div style="background-color:#f48b3a;width:20px;height:20px"></div></td>
                      <th>Total cabeceros</th>
                      <td>{{$total_cabeceros['CB'] + $total_cabeceros['CC']}}</td>
                      @if ($total_cantidad != 0)

                        <td>{{number_format((($total_cabeceros['CB'] + $total_cabeceros['CC'])*100)/$total_cantidad)}} %</td>
                      @else
                      <td>--</td>
                      @endif
                    </tr>
                    <tr>
                      <td><div style="background-color:#d01c13;width:20px;height:20px"></div></td>
                      <th>Total incidencias</th>
                      <td>{{$total_cantidad}}</td>
                      @if ($total_cantidad != 0)
                      <td>{{number_format((($total_cantidad-($total_cabeceros['CB'] + $total_cabeceros['CC']))*100)/$total_cantidad)}} %</td>
                      @else
                      <td>--</td>
                      @endif
                    </tr>
                  </table>
                </div>

                <div class="col-md-6">
                  <span id="quesitoCabecerosIncidencias">&nbsp;</span>
                </div>
              </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 class_quesitoFacturacionIncidencias">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Costes de incidencias</h3>
          </div>
          <div class="box-body">
            <div class="col-md-6">
              <table class="table table-bordered">
                <tr>
                  <td><div style="background-color:#3366cc;width:20px;height:20px"></div></td>
                  <th>Coste incidencias</th>
                  <td>{{number_format($total_valor , 2, ',', '.' )}} €</td>
                  @if ($facturacion_total != 0)
                  <td>{{number_format(($total_valor*100)/$facturacion_total , 2, ',', '.' )}} %</td>
                  @else
                  <td> -- </td>
                  @endif
                </tr>
                <tr>
                  <td><div style="background-color:#dc3912;width:20px;height:20px"></div></td>
                  <th>Total facturación</th>
                  <td>{{number_format($facturacion_total , 2, ',', '.' )}} €</td>
                  @if ($facturacion_total != 0)
                  <td>{{number_format((($facturacion_total-$total_valor)*100)/$facturacion_total , 2, ',', '.' )}} %</td>
                  @else
                  <td> -- </td>
                  @endif
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <span id="quesitoFacturacionIncidencias">&nbsp;</span>
            </div>
          </div>
        </div>

      </div>
    </div>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Productos afectados</h3>
        </div>
        <div class="box-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Nº pedido</th>
                <th>fecha</th>
                <th>cliente_facturacion</th>
                <th>transporte </th>
                <th>nombre_producto</th>
                <th>cantidad</th>
                <th>total</th>
                <th>mensaje_incidencia</th>
              </tr>
              @foreach ($productos_incidencia as $k => $producto_i)
                <tr>
                  <td>{{$producto_i->numero_pedido}}</td>
                  <td>{{$producto_i->fecha_pedido}}</td>
                  <td>{{$producto_i->cliente_facturacion}}</td>
                  <td>{{$producto_i->metodo_entrega}}</td>
                  <td>{{$producto_i->nombre_producto}}</td>
                  <td>{{$producto_i->cantidad_producto}}</td>
                  @if ($producto_i->historial_incidencia > 0)
                  <td>{{$producto_i->historial_incidencia}} €</td>
                  @else
                  <td>---</td>
                  @endif
                  <?php
                    if($producto_i->mensaje_incidencia != ''){
                      $i_mens = explode(": ",$producto_i->mensaje_incidencia);
                    }else{
                      $i_mens = array();
                      $i_mens[0]='';
                    }




                  ?>
                @if (is_numeric($i_mens[0]) == 1)
                  @if (sizeof($i_mens)<2)
                    <td>{{$mensaje_incidencia[$i_mens[0]]['tipo']}}</td>
                  @else
                    @if (isset($mensaje_incidencia[$i_mens[0]]['tipo']))
                      <td>{{$mensaje_incidencia[$i_mens[0]]['tipo']}} : {{$i_mens[1]}}</td>
                    @endif
                  @endif

                @else
                  <td>{{$producto_i->mensaje_incidencia}}</td>
                @endif

                </tr>
              @endforeach
            </thead>
          </table>
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
<!-- ChartJS 1.0.1 -->
<script src="{{url('/plugins/chartjs/Chart.min.js')}}"></script>
<!-- FLOT CHARTS -->
<script src="{{url('/plugins/flot/jquery.flot.min.js')}}"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="{{url('/plugins/flot/jquery.flot.resize.min.js')}}"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="{{url('/plugins/flot/jquery.flot.pie.min.js')}}"></script>
<!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
<script src="{{url('/plugins/flot/jquery.flot.categories.min.js')}}"></script>
<script type="text/javascript" src="{!! asset('js/jquery.sparkline.min.js') !!}"></script>
<script>
 $(function () {
      var valor_incidencias = [
        <?php
          foreach ($tabla_cantidad_valor as $origen2 => $valores) {
            echo $valores['valor'].',';
          }
        ?>];
        var cantidad_incidencias = [
        <?php
          foreach ($cantidad_incidencias as $num => $val){
            if($val > 0 && $num != 'total' && $num != 'solucionados'){
              echo $val.',';
            }
          }
        ?>
        ];
        var cantidad_gestion = [
        <?php
          foreach ($cantidad_gestion as $num3 => $val3){
            if($val3 > 0 && $num3 != 'total'){
              echo $val3.',';
            }
          }
        ?>
        ];
        var cabeceros_incidencias = [{{$total_cabeceros['CB']+$total_cabeceros['CC']}},{{$total_cantidad - ($total_cabeceros['CB']+$total_cabeceros['CC'])}}];
        var facturacion_incidencias = [{{$facturacion_total - $total_valor}},{{$total_valor}}];
        var web_contactos = [{{$total_cabeceros['CB']}},{{$total_cabeceros['CC']}}];


        $('#quesitoWebContactos').sparkline(web_contactos, {
            type: "pie",
            width: 100,
            height: 100,
            tooltipFormat: <?php echo "'{{offset:offset}} ({{percent.1}}%)'" ?>,
            sliceColors: ['#849d9c','#09a8be']

        });

        $('#quesitoFacturacionIncidencias').sparkline(facturacion_incidencias, {
            type: "pie",
            width: 100,
            height: 100,
            tooltipFormat: <?php echo "'{{offset:offset}} ({{percent.1}}%)'" ?>,
            sliceColors: ['#3366cc','#dc3912']

        });
        $('#quesitoCabecerosIncidencias').sparkline(cabeceros_incidencias, {
            type: "pie",
            width: 100,
            height: 100,
            tooltipFormat: <?php echo "'{{offset:offset}} ({{percent.1}}%)'" ?>,
            sliceColors: ['#d01c13','#f48b3a']

        });
        // Draw a sparkline for the #sparkline element
        $('#quesitoGestion').sparkline(cantidad_gestion, {
            type: "pie",
            width: 250,
            height: 250,
            tooltipFormat: <?php echo "'{{offset:offset}} ({{percent.1}}%)'" ?>,
            sliceColors: [
              <?php
                foreach ($cantidad_gestion as $num2 => $val2) {
                  if($val2 > 0 && $num2 != 'total' && $num2 != 'otros'){
                  echo "'".$mensaje_gestion[$num2]['color']."',";
                }
                }
              ?>'#a0a3a5'],
              tooltipValueLookups: {
                  'offset': {
                    <?php
                      $i=0;
                      foreach ($cantidad_gestion as $num4 => $val4) {
                          if($val4 > 0 && $num4 != 'total' && $num4 != 'otros'){
                        echo $i.': "'.$mensaje_gestion[$num4]['tipo'].'",';
                        $i++;
                        }
                      }
                     ?>
                    }
              }
        });
        // Draw a sparkline for the #sparkline element
        $('#quesitoCantidad').sparkline(cantidad_incidencias, {
            type: "pie",
            width: 250,
            height: 250,
            tooltipFormat: <?php echo "'{{offset:offset}} ({{percent.1}}%)'" ?>,
            sliceColors: [
              <?php
                foreach ($cantidad_incidencias as $num2 => $val2) {
                  if($val2 > 0 && $num2 != 'total' && $num2 != 'solucionados' && $num2 != 'otros'){
                  echo "'".$mensaje_incidencia[$num2]['color']."',";
                }
                }
              ?>'#a0a3a5'],
              tooltipValueLookups: {
                  'offset': {
                    <?php
                      $i=0;
                      foreach ($cantidad_incidencias as $num4 => $val4) {
                          if($val4 > 0 && $num4 != 'total' && $num4 != 'solucionados' && $num4 != 'otros'){
                        echo $i.': "'.$mensaje_incidencia[$num4]['tipo'].'",';
                        $i++;
                        }
                      }
                     ?>
                    }
              }
        });
        $('#sparkline').sparkline(valor_incidencias, {
            type: "pie",
            width: 250,
            height: 250,
            tooltipFormat: <?php echo "'{{offset:offset}} {{value:valor_incidencias}}€ ({{percent.1}}%)'" ?>,
            sliceColors: [
              <?php
                foreach ($tabla_cantidad_valor as $origen2 => $valores) {
                  echo "'".$valores['color']."',";
                }
              ?>
            ],
            tooltipValueLookups: {
                'offset': {
                  <?php
                    $i=0;
                    foreach ($tabla_cantidad_valor as $origen2 => $valores) {
                      echo $i.': "'.$origen2.'",';
                      $i++;
                    }
                   ?>
                  }
            }

        });

  });
</script>
<style>
  .box-body {
    text-align: center;
  }
  td {
    text-align: left;
  }
  canvas {
    margin: 20px 0px;
}
</style>
@endsection
