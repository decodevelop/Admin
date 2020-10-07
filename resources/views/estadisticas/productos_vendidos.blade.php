@extends('layouts.backend')
@section('titulo','Estadísticas > listado')
@section('titulo_h1','Estadísticas: Productos')

@section('estilos')
<link rel="stylesheet" href="/css/custom.css">
@endsection

@section('contenido')

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
							<div class="col-md-2">
                PRODUCTO
              </div>
							<div class="col-md-2">
                NO CONTIENE
              </div>
            </div>
            <div class="col-md-12">
              <div class="col-md-2">
                <select name="mes" id="filtro-mes" class="form-control">
                  <option value="1" {{($fecha['mes']==1) ? 'selected' : '' }}>Enero</option>
                  <option value="2" {{($fecha['mes']==2) ? 'selected' : '' }}>Febrero</option>
                  <option value="3" {{($fecha['mes']==3) ? 'selected' : '' }}>Marzo</option>
                  <option value="4" {{($fecha['mes']==4) ? 'selected' : '' }}>Abril</option>
                  <option value="5" {{($fecha['mes']==5) ? 'selected' : '' }}>Mayo</option>
                  <option value="6" {{($fecha['mes']==6) ? 'selected' : '' }}>Junio</option>
                  <option value="7" {{($fecha['mes']==7) ? 'selected' : '' }}>Julio</option>
                  <option value="8" {{($fecha['mes']==8) ? 'selected' : '' }}>Agosto</option>
                  <option value="9" {{($fecha['mes']==9) ? 'selected' : '' }}>Septiembre</option>
                  <option value="10" {{($fecha['mes']==10) ? 'selected' : '' }}>Octubre</option>
                  <option value="11" {{($fecha['mes']==11) ? 'selected' : '' }}>Noviembre</option>
                  <option value="12" {{($fecha['mes']==12) ? 'selected' : '' }}>Diciembre</option>
                </select>
              </div>
              <div class="col-md-2">
                <select name="any" id="filtro-ano" class="form-control">
									<option value="2018" {{($fecha['any']==2018) ? 'selected' : '' }}>2018</option>
                  <option value="2017" {{($fecha['any']==2017) ? 'selected' : '' }}>2017</option>
                  <option value="2016" {{($fecha['any']==2016) ? 'selected' : '' }}>2016</option>
                </select>
              </div>
							<div class="col-md-2">
								<input type="text" class="form-control" name="filtro-producto" id="filtro-producto" value="{{$name_product}}">
							</div>
							<div class="col-md-2">
								<input type="text" class="form-control" name="not_like" id="not_like" value="">
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
			<h1>Filtro {{$fecha['mes']}} / {{$fecha['any']}}</h1>
		</div>

		<div class="col-md-6">
			<div class="fila-estadisticas">
				<div class="col-md-12 mes-actual">
		          <!-- DONUT CHART -->
		          <div class="box box-primary">
		            <div class="box-header with-border">
		              <h3 class="box-title">Productos mas vendidos</h3>
		              <div class="box-tools pull-right">
		                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                </button>
		                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		              </div>
		            </div>
		            <div class="box-body">
									<table class="table table-bordered">
										<thead>
			                <tr>
			                  <th>Producto</th>
			                  <th>SKU</th>
			                  <th>Cantidad</th>
			                </tr>
			                @foreach ($productos_vendidos as $count => $fila_valor)
			                    <tr id="cantidad_{{$count}}">
			                      <td>{{$fila_valor->nombre_producto}}</td>
			                      <td>{{$fila_valor->sku_producto}}</td>
			                      <td id="suma_cantidad_{{$count}}">{{$fila_valor->cantidad}}</td>
			                    </tr>

			                @endforeach
			              </thead>
									</table>
		            </div>
		            <!-- /.box-body -->
		          </div>

		        <!-- /.box -->
		        </div>
			</div>

		</div>
		<div class="col-md-6">
			<div class="fila-estadisticas">
			<div class="col-md-12 mes-actual">
						<!-- DONUT CHART -->
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Totales</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>TOTAL PRODUCTOS</th>
											<th>{{$total_productos}}</th>
										</tr>
									</thead>
									<tr>
										<td>Suma selección</td>
										<td id="suma">0</td>
									</tr>
								</table>
							</div>
							<!-- /.box-body -->
						</div>

					<!-- /.box -->
					</div>
		</div></div>
        <!-- /.col (LEFT) -->
				<div class="col-md-12 graficos">
					<div class="col-md-12">
					 <div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Gráfico</h3>
								<div class="box-tools pull-right">
								</div>
								<div class="box-body">
									<div class="chart">
											<div id="productosVendidos" style="height: 500px;"></div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
    </div>
    <!-- /.row -->
</section>
@endsection

<style>
	.fila-estadisticas{
		display: flex;
	}
	.label.label-primary {
    display: block;
    margin-bottom: 4px;
    font-size: 14px;
	}
	span.productosVendidos {
    display: block;
}
</style>

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


    /* END INFORME MENSUAL */

    /*
     * INFORME MENSUAL
     * ---------
    */




    /* Quesito */
    var myvalues = {
			data: [
					<?php
						foreach ($productos_vendidos as $estadisticaQuesito) { ?>
						[<?php echo '"'.$estadisticaQuesito->sku_producto.'", '. $estadisticaQuesito->cantidad; ?>],
					<?php
						}
					?>
				],
				color: "#3c8dbc"
		};

		$.plot("#productosVendidos", [myvalues], {
      grid: {
        borderWidth: 1,
        borderColor: "#f3f3f3",
        tickColor: "#f3f3f3"
      },
      series: {
        bars: {
          show: true,
          barWidth: 0.8,
          align: "center"
        }
      },
      xaxis: {
        mode: "categories",
        tickLength: 0
      }
    });


  });
</script>
@endsection
