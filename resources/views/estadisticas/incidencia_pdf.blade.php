Estadistica incidencias
<link rel="stylesheet" href="/css/custom.css">
<section class="content">
  <div class="row">
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
  </div>
</section>
