@extends('layouts.backend')
@section('titulo','Estadísticas > listado')
@section('titulo_h1','Estadísticas por mes')

@section('estilos')
@endsection

@section('contenido')
<?php
?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h1>Filtro {{$fecha['mes']}} / {{$fecha['any']}}</h1>
		</div>
		<div class="col-md-6">
			<div class="fila-estadisticas">
				<div class="col-md-12 mes-actual">
		          <!-- DONUT CHART -->
		          <div class="box box-primary">
		            <div class="box-header with-border">
		              <h3 class="box-title">Ingresos mes</h3>
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
									<?php
												$valor_total_incidencias = ($valor_total_incidencias) / 1.21;
												$total = ($total) / 1.21;
												$total -= $valor_total_incidencias;
												$valor_total_incidencias = number_format( $valor_total_incidencias , 2, ',', '.' );
												$total = number_format( $total , 2, ',', '.' );
												?>
									<dd style="margin-bottom: 10px; font-size:17px;"><b class="label label-default " style="text-transform: uppercase;">{{ "Total incidencias: ".$valor_total_incidencias }} €</b></dd>

									<dd><b class="label label-primary " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
		            </div>
		            <!-- /.box-body -->
		          </div>

		        <!-- /.box -->
		        </div>
			</div>

		</div>
		<div class="col-md-6 graficos">
			<div class="col-md-12">
			 <div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Gráfico</h3>
						<div class="box-tools pull-right">
						</div>
						<div class="box-body">
							<div>
									<span class="quesitoClientes col-xs-8" style="margin-top: 20px; margin-bottom: 20px;text-align: center;"></span>
									<span class="leyendaQuesito col-xs-4">
										<p>Leyenda: </p>
										@forelse($estadisticas_glob["mes"] as $kk => $og_esta)
											<div><span class="leyendaColor" style="background-color:{{$og_esta->color}}; width:20px; height:10px; display: inline-block;border: 1px solid black;"></span> &nbsp; {{$og_esta->nombre}}</div>
										@empty
											No hay datos.
										@endforelse
									</span>
							</div>
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

<style>
	.fila-estadisticas{
		display: flex;
	}
	.label.label-primary {
    display: block;
    margin-bottom: 4px;
    font-size: 14px;
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
    var myvalues = [
      <?php
        foreach ($estadisticas_glob["mes"] as $vkey => $estadisticaQuesito) {
         echo $estadisticaQuesito->total.', ';
        }
      ?>
    ];

    $('.quesitoClientes').sparkline(myvalues,
    {
      type: 'pie',
      width: 250,
      height: 250,
			tooltipFormat: <?php echo "'{{offset:offset}} {{value:myvalues}}€ ({{percent.1}}%)'" ?>,
			tooltipValueLookups: {
					'offset': {
						<?php
							$i=0;
							foreach ($estadisticas_glob["mes"] as $kl => $og_estas) {
		          	echo $i.': "'.$og_estas->nombre.'",';
								$i++;
		 				 }
						 ?>
						}
			},
      sliceColors: [
        <?php
          /*foreach ($coloresQuesito as $vkeyColor => $colorQ) {
           echo '"'.$colorQ.'", ';
				 }*/
					foreach ($estadisticas_glob["mes"] as $kk => $og_esta) {
          	echo '"'.$og_esta->color.'", ';
 				 }

        ?>
      ]

    });

  });
</script>
@endsection
