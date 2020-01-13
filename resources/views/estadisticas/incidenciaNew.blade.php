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
                  
                    <option value="2020" {{ ($fecha['any']==2020) ? 'selected' : '' }}>2020</option>
                    <option value="2019" {{ ($fecha['any']==2019) ? 'selected' : '' }}>2019</option>
                   <option value="2018" {{ ($fecha['any']==2018) ? 'selected' : '' }}>2018</option>
                   <option value="2017" {{ ($fecha['any']==2017) ? 'selected' : '' }}>2017</option>
                   <option value="2016" {{ ($fecha['any']==2016) ? 'selected' : '' }}>2016</option>
                </select>
              </div>
              <div class="col-md-1">
                <input type="checkbox" name="excel" id="switch" /><label for="switch">Toggle</label>
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
                @foreach ($tabla_cantidad_valor as $origen_id => $fila_valor)
                  @if ($origen_id != 'total')
                    <tr>
                      <td><div style="background-color:{{$origenes->find($origen_id)->color}};width:20px;height:20px"></div></td>
                      <td>{{$origenes->find($origen_id)->nombre}}</td>
                      <td>{{$fila_valor['cantidad']}}</td>
                      <td>{{$fila_valor['valor']}} €</td>

                    </tr>
                  @endif
                @endforeach
                <tr>
                  <td colspan="2"><b>Total</b></td>
                  <td>{{$tabla_cantidad_valor['total']['cantidad']}}</td>
                  <td>{{$tabla_cantidad_valor['total']['valor']}} €</td>
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

                @foreach ($motivos_incidencias as $motivo_id => $fila_valor)
                  @if ($motivo_id != 'total')
                    <tr>

                        <td><div style="background-color:{{$motivos->find($motivo_id)->color}};width:20px;height:20px"></div></td>
                        <td>{{$motivos->find($motivo_id)->nombre}}</td>
                        <td>{{$fila_valor['cantidad']}}</td>
                          <td>{{number_format(($fila_valor['cantidad'] * 100)/$motivos_incidencias['total']['cantidad'], 2, ',', '.' )}}%</td>
                    </tr>
                  @endif
                @endforeach
                <td colspan="3"><b>TOTAL</b></td>
                <td>{{$motivos_incidencias['total']['cantidad']}}</td>
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
                @foreach ($gestiones_incidencias as $gestion_id => $fila_valor)
                  @if ($gestion_id != 'total')
                    <tr>

                        <td><div style="background-color:{{$gestiones->find($gestion_id)->color}};width:20px;height:20px"></div></td>
                        <td>{{$gestiones->find($gestion_id)->nombre}}</td>
                        <td>{{$fila_valor['cantidad']}}</td>
                        <td>{{number_format(($fila_valor['cantidad'] * 100)/$gestiones_incidencias['total']['cantidad'] , 2, ',', '.' )}}%</td>


                    </tr>
                  @endif
                @endforeach
                <td colspan="3"><b>TOTAL</b></td>
                <td>{{$gestiones_incidencias['total']['cantidad']}}</td>

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
                      <td>{{$total_cabeceros['web']}}</td>
                      @if ($total_cabeceros['web'] + $total_cabeceros['manual'] != 0)
                        <td>{{number_format((($total_cabeceros['web'])*100)/($total_cabeceros['web'] + $total_cabeceros['manual']))}} %</td>
                      @else
                        <td>--</td>
                      @endif
                    </tr>
                    <tr>
                      <td><div style="background-color:#09a8be;width:20px;height:20px"></div></td>
                      <th>Contactos web</th>
                      <td>{{$total_cabeceros['manual']}}</td>
                      @if ($total_cabeceros['web'] + $total_cabeceros['manual'] != 0)
                        <td>{{number_format((($total_cabeceros['manual'])*100)/($total_cabeceros['web'] + $total_cabeceros['manual']))}} %</td>
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
                      <td>{{$total_cabeceros['web'] + $total_cabeceros['manual']}}</td>
                      @if ($total_cabeceros['incidencia'] != 0)

                        <td>100%</td>
                      @else
                      <td>--</td>
                      @endif
                    </tr>
                    <tr>
                      <td><div style="background-color:#d01c13;width:20px;height:20px"></div></td>
                      <th>Total incidencias</th>
                      <td>{{$total_cabeceros['incidencia']}}</td>
                      @if ($total_cabeceros['incidencia'] != 0)
                      <td>{{number_format((($total_cabeceros['incidencia'] / ($total_cabeceros['web'] + $total_cabeceros['manual']))*100) , 2, ',', '.' )}} %</td>
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
                  <td><div style="background-color:#dc3912;width:20px;height:20px"></div></td>
                  <th>Coste incidencias</th>
                  <td>{{$tabla_cantidad_valor['total']['valor'] }} €</td>
                  @if ($total_facturacion_mes != 0)
                  <td>{{number_format(($tabla_cantidad_valor['total']['valor']*100)/$total_facturacion_mes , 2, ',', '.' )}} %</td>
                  @else
                  <td> -- </td>
                  @endif
                </tr>
                <tr>
                  <td><div style="background-color:#3366cc;width:20px;height:20px"></div></td>
                  <th>Total facturación</th>
                  <td>{{number_format($total_facturacion_mes , 2, ',', '.' )}} €</td>
                  @if ($total_facturacion_mes != 0)
                  <td>{{number_format((($total_facturacion_mes-$tabla_cantidad_valor['total']['valor'])*100)/$total_facturacion_mes , 2, ',', '.' )}} %</td>
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
                  <td>{{$producto_i->producto->pedido->numero_pedido}}</td>
                  <td>{{$producto_i->producto->pedido->fecha_pedido}}</td>
                  <td>{{$producto_i->producto->pedido->cliente->nombre_apellidos}}</td>
                  <td>{{$producto_i->producto->transportista->nombre}}</td>
                  <td>{{$producto_i->producto->nombre_esp}}</td>
                  <td>{{$producto_i->producto->cantidad}}</td>
                  <td>{{$producto_i->incidencia->cantidad_descontar}} €</td>
                  <td>{{$producto_i->incidencia->motivo->nombre}}</td>




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
          foreach ($tabla_cantidad_valor as $origen_id => $valores) {
            if ($valores['valor'] > 0 && $origen_id != 'total'){
              $valor_no_puntos=str_replace('.','',$valores['valor']);
              echo str_replace(',','.',$valor_no_puntos).',';
            }
          }
        ?>];
        var motivos_incidencias = [
        <?php
          foreach ($motivos_incidencias as $num => $val){
            if($val > 0 && $num != 'total'){
              echo $val['cantidad'].',';
            }
          }
        ?>
        ];
        var gestiones_incidencias = [
        <?php
          foreach ($gestiones_incidencias as $num3 => $val3){
            if($val3 > 0 && $num3 != 'total'){
              echo $val3['cantidad'].',';
            }
          }
        ?>
        ];

        var cabeceros_incidencias = [{{$total_cabeceros['incidencia']}},{{($total_cabeceros['web']+$total_cabeceros['manual']) - $total_cabeceros['incidencia'] }}];
        var web_contactos = [{{$total_cabeceros['web']}},{{$total_cabeceros['manual']}}];

        var facturacion_incidencias = [{{$total_facturacion_mes - $tabla_cantidad_valor['total']['valor']}},{{$tabla_cantidad_valor['total']['valor']}}];

     $('#quesitoCabecerosIncidencias').sparkline(cabeceros_incidencias, {
            type: "pie",
            width: 100,
            height: 100,
            tooltipFormat: <?php echo "'{{offset:offset}} ({{percent.1}}%)'" ?>,
            sliceColors: ['#d01c13','#f48b3a']

        });
        $('#quesitoWebContactos').sparkline(web_contactos, {
            type: "pie",
            width: 100,
            height: 100,
            tooltipFormat: <?php echo "'{{offset:offset}} ({{percent.1}}%)'" ?>,
            sliceColors: ['#849d9c','#09a8be']

        });
        // Draw a sparkline for the #sparkline element
       $('#quesitoGestion').sparkline(gestiones_incidencias, {
            type: "pie",
            width: 250,
            height: 250,
            tooltipFormat: <?php echo "'{{offset:offset}} ({{percent.1}}%)'" ?>,
            sliceColors: [
              <?php
                foreach ($gestiones_incidencias as $num2 => $val2) {
                  if($val2 > 0 && $num2 != 'total'){
                  echo "'".$gestiones->find($num2)->color."',";
                }
                }
              ?>'#a0a3a5'],
              tooltipValueLookups: {
                  'offset': {
                    <?php
                      $i=0;
                      foreach ($gestiones_incidencias as $num4 => $val4) {
                          if($val4 > 0 && $num4 != 'total'){
                        echo $i.': "'.$gestiones->find($num2)->nombre.'",';
                        $i++;
                        }
                      }
                     ?>
                    }
              }
        });
        // Draw a sparkline for the #sparkline element
        $('#quesitoCantidad').sparkline(motivos_incidencias, {
            type: "pie",
            width: 250,
            height: 250,
            tooltipFormat: <?php echo "'{{offset:offset}} ({{percent.1}}%)'" ?>,
            sliceColors: [
              <?php
                foreach ($motivos_incidencias as $num2 => $val2) {
                  if($val2 > 0 && $num2 != 'total' && $num2 != 'solucionados' && $num2 != 'otros'){
                  echo "'".$motivos->find($num2)->color."',";
                }
                }
              ?>'#a0a3a5'],
              tooltipValueLookups: {
                  'offset': {
                    <?php
                      $i=0;
                      foreach ($motivos_incidencias as $num4 => $val4) {
                          if($val4 > 0 && $num4 != 'total' && $num4 != 'solucionados' && $num4 != 'otros'){
                        echo $i.': "'.$motivos->find($num4)->nombre.'",';
                        $i++;
                        }
                      }
                     ?>
                    }
              }
        });
       $('#quesitoFacturacionIncidencias').sparkline(facturacion_incidencias, {
            type: "pie",
            width: 100,
            height: 100,
            tooltipFormat: <?php echo "'{{offset:offset}} ({{percent.1}}%)'" ?>,
            sliceColors: ['#3366cc','#dc3912']

        });
        $('#sparkline').sparkline(valor_incidencias, {
             type: "pie",
             width: 250,
             height: 250,
             tooltipFormat: <?php echo "'{{offset:offset}} {{value:valor_incidencias}}€ ({{percent.1}}%)'" ?>,
             sliceColors: [
               <?php
                 foreach ($tabla_cantidad_valor as $origen_id => $valores) {
                 if ($valores['valor'] > 0 && $origen_id != 'total' ){
                     echo "'".$origenes->find($origen_id)->color."',";
                   }
                 }
               ?>
             ],
             tooltipValueLookups: {
                 'offset': {
                   <?php
                     $i=0;
                     foreach ($tabla_cantidad_valor as $origen_id => $valores) {
                     if ($valores['valor'] > 0 && $origen_id != 'total'){
                         echo $i.': "'.$origenes->find($origen_id)->nombre.'",';
                        $i++;
                       }
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
