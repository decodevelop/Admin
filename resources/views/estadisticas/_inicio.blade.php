@extends('layouts.backend')
@section('titulo','Estadisticas > listado')
@section('titulo_h1','Estadisticas')

@section('estilos')
@endsection

@section('contenido')
<?php
?>
<section class="content">
	<div class="row">
		<div class="col-md-2">

          <!-- DONUT CHART -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Ingresos semana actual</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
			<?php $total = ""  ?>
            @forelse($estadisticas_glob["semana"] as $key => $origen_estadistica)
			<dd>{{$origen_estadistica->nombre}} -
				<?php
					$price_noiva = ($origen_estadistica->total) / 1.21;
				 	$price_noiva = number_format( $price_noiva , 2, ',', '.' );
					echo $price_noiva ?> €</dd>


			<?php $total += $origen_estadistica->total;  ?>
			@empty
			@endforelse
			</br>
			<?php $total = ($total) / 1.21;
						$total = number_format( $total , 2, ',', '.' );  ?>
			<dd><b class="label label-danger " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        <!-- /.box -->
        </div>
		<div class="col-md-2">

          <!-- DONUT CHART -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Ingresos mes actual</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
			<?php $total = ""  ?>
            @forelse($estadisticas_glob["mes"] as $key => $origen_estadistica)
			<dd>{{$origen_estadistica->nombre}} - <?php
				$price_noiva = ($origen_estadistica->total) / 1.21;
				$price_noiva = number_format( $price_noiva , 2, ',', '.' );
				echo $price_noiva ?> €</dd>
			<?php $total += $origen_estadistica->total;  ?>
			@empty
			@endforelse
			</br>
			<?php $total = ($total) / 1.21;
						$total = number_format( $total , 2, ',', '.' );  ?>
			<dd><b class="label label-danger " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
            </div>
            <!-- /.box-body -->
          </div>
        <!-- DONUT CHART -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Ingresos mes anterior</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
      <?php $total = ""  ?>
            @forelse($estadisticas_glob["mesAnterior"] as $key => $origen_estadistica)
      <dd>{{$origen_estadistica->nombre}} - <?php
				$price_noiva = ($origen_estadistica->total) / 1.21;
				$price_noiva = number_format( $price_noiva , 2, ',', '.' );
				echo $price_noiva ?> €</dd>
      <?php $total += $origen_estadistica->total;  ?>
      @empty
      @endforelse
      </br>
			<?php $total = ($total) / 1.21;
						$total = number_format( $total , 2, ',', '.' );  ?>
      <dd><b class="label label-danger " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        <!-- /.box -->
        </div>
		<div class="col-md-2">

          <!-- DONUT CHART -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Ingresos año <?php echo date ("Y") ?></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
			<?php $total = ""  ?>
            @forelse($estadisticas_glob["año"] as $key => $origen_estadistica)
			<dd>{{$origen_estadistica->nombre}} - <?php
				$price_noiva = ($origen_estadistica->total) / 1.21;
				$price_noiva = number_format( $price_noiva , 2, ',', '.' );
				echo $price_noiva ?> €</dd>
			<?php $total += $origen_estadistica->total;  ?>
			@empty
			@endforelse
			</br>
			<?php $total = ($total) / 1.21;
						$total = number_format( $total , 2, ',', '.' );  ?>
			<dd><b class="label label-danger " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
            </div>
            <!-- /.box-body -->
          </div>
		    <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Ingresos año <?php echo date ("Y")-1 ?></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
			<?php $total = "" ?>
            @forelse($estadisticas_glob["añopasado"] as $key => $origen_estadistica)
			<dd>{{$origen_estadistica->nombre}} - <?php
				$price_noiva = ($origen_estadistica->total) / 1.21;
				$price_noiva = number_format( $price_noiva , 2, ',', '.' );
				echo $price_noiva ?> €</dd>
			<?php $total += $origen_estadistica->total;  ?>
			@empty
			@endforelse
			</br>
			<?php $total = ($total) / 1.21;
						$total = number_format( $total , 2, ',', '.' );  ?>
			<dd><b class="label label-danger " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
        <?php if(date ("Y")-1 == 2016){ ?>
          <dd><b class="label label-danger " style="text-transform: uppercase;">Total de enero a diciembre: 481.440,86 €</b></dd>
        <?php } ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        <!-- /.box -->
        </div>
				<div class="col-md-2">
					<div class="box box-danger">
	            <div class="box-header with-border">
	              <h3 class="box-title">Julio</h3>
	              <div class="box-tools pull-right">
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
	              </div>
	            </div>
	            <div class="box-body">
				<?php $total = "" ?>
	            @forelse($estadisticas_glob["julio"] as $key => $origen_estadistica)
				<dd>{{$origen_estadistica->nombre}} - <?php
					$price_noiva = ($origen_estadistica->total) / 1.21;
					$price_noiva = number_format( $price_noiva , 2, ',', '.' );
					echo $price_noiva ?> €</dd>
				<?php $total += $origen_estadistica->total;  ?>
				@empty
				@endforelse
				</br>
				<?php $total = ($total) / 1.21;
							$total = number_format( $total , 2, ',', '.' );  ?>
				<dd><b class="label label-danger " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>

	            </div>
	            <!-- /.box-body -->
	          </div>
				</div>
		 <div class="col-md-6">
          <!-- AREA CHART -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Estadística Mensual - <?php echo date ("Y") ?></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                  <div id="bar-chart-mensual" style="height: 300px;"></div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
         <div class="col-md-6">
          <!-- AREA CHART -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Estadística Anual </h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                  <div id="bar-chart-anual" style="height: 300px;"></div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
         <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Estadística Clientes - <?php echo date ("Y") ?></h3>
              <div class="box-tools pull-right">
              </div>
              <div class="box-body">
                <div>
                    <span class="quesitoClientes col-xs-8" style="margin-top: 20px; margin-bottom: 20px;text-align: center;"></span>
                    <span class="leyendaQuesito col-xs-4">
                      <p>Leyenda: </p>
                  
                    </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- /.col (LEFT) -->
    </div>
    <!-- /.row -->
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
    /*
     * INFORME MENSUAL
     * ---------
     */

    var bar_data_mensual = {
      data: [
      ["Enero", <?php echo $estadistica_mensual_final[0] ?>],
      ["Febrero", <?php echo $estadistica_mensual_final[1] ?>],
      ["Marzo", <?php echo $estadistica_mensual_final[2] ?>],
      ["Abril", <?php echo $estadistica_mensual_final[3] ?>],
      ["Mayo", <?php echo $estadistica_mensual_final[4] ?>],
      ["Junio", <?php echo $estadistica_mensual_final[5] ?>],
      ["Julio", <?php echo $estadistica_mensual_final[6] ?>],
      ["Agosto", <?php echo $estadistica_mensual_final[7] ?>],
      ["Septiembre", <?php echo $estadistica_mensual_final[8] ?>],
      ["Octubre", <?php echo $estadistica_mensual_final[9] ?>],
      ["Noviembre", <?php echo $estadistica_mensual_final[10] ?>],
      ["Diciembre", <?php echo $estadistica_mensual_final[11] ?>]
      ],
      color: "#3c8dbc"
    };
    $.plot("#bar-chart-mensual", [bar_data_mensual], {
      grid: {
        borderWidth: 1,
        borderColor: "#f3f3f3",
        tickColor: "#f3f3f3"
      },
      series: {
        bars: {
          show: true,
          barWidth: 0.5,
          align: "center"
        }
      },
      xaxis: {
        mode: "categories",
        tickLength: 0
      }
    });
    /* END INFORME MENSUAL */

    /*
     * INFORME MENSUAL
     * ---------
    */
    var bar_data_anual = {
      data: [
      ["2016", 481440.86],
      ["2017", <?php echo $estadistica_anual_final[0] ?>],
      ["2018", <?php echo $estadistica_anual_final[1] ?>],
      ["2019", <?php echo $estadistica_anual_final[2] ?>],
      ["2020", <?php echo $estadistica_anual_final[3] ?>]
      ],
      color: "#3c8dbc"
    };

    $.plot("#bar-chart-anual", [bar_data_anual], {
      grid: {
        borderWidth: 1,
        borderColor: "#f3f3f3",
        tickColor: "#f3f3f3"
      },
      series: {
        bars: {
          show: true,
          barWidth: 0.5,
          align: "center"
        }
      },
      xaxis: {
        mode: "categories",
        tickLength: 0
      }
    });


    /* Quesito */
    var myvalues = [
      <?php
        foreach ($estadisticas_glob["año"] as $vkey => $estadisticaQuesito) {
         echo $estadisticaQuesito->total.', ';
        }
      ?>
    ];
    $('.quesitoClientes').sparkline(myvalues,
    {
      type: 'pie',
      width: 250,
      height: 250,
      sliceColors: [
        <?php
          foreach ($coloresQuesito as $vkeyColor => $colorQ) {
           echo '"'.$colorQ.'", ';
          }
        ?>
      ]
    });

  });
</script>
@endsection
