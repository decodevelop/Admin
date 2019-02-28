@extends('layouts.backend')
@section('titulo','Estadísticas > listado')
@section('titulo_h1','Estadísticas')

@section('estilos')
@endsection

@section('contenido')
<link rel="stylesheet" href="/css/custom.css">
<?php
?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<h1>Filtro {{$fecha['mes']}} / {{$fecha['any']}}</h1>
		</div>

		<div class="col-md-6">
			@php
				$i = 2
			@endphp
			<div class="fila-estadisticas">

					<div class="col-md-8 ingresos-año">
								<!-- DONUT CHART -->
								<div class="box box-primary">
									<div class="box-header with-border">
										<h3 class="box-title">
											Ingresos mes actual
										</h3>
										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
											</button>
											<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
										</div>
									</div>
									<div class="box-body">
										<table class="table table-bordered">
											@foreach ($estadisticas as $origen_key => $esta)
												@if ($origen_key != 'total')
													<tr>
														<td>{{$origenes->find($origen_key)->nombre}}</td>
														<td>{{number_format($esta, 2, ',', '.' )}} €</td>
													</tr>
												@endif
											@endforeach
										</table>
										<dd style="margin-bottom: 10px; margin-top: 10px; font-size:17px;">
											<b class="label label-default " style="text-transform: uppercase;">
												Total incidencias: {{number_format($incidencias, 2, ',', '.' )}} €
											</b>
										</dd>
										<dd>
											<b class="label label-primary " style="text-transform: uppercase;">
												Total: {{number_format($estadisticas['total'] - $incidencias, 2, ',', '.' ) }} €
											</b>
										</dd>
									</div>
									<!-- /.box-body -->
								</div>
							<!-- /.box -->
					</div>

			</div>
        <!-- /.col (LEFT) -->



    </div>
<!-- /.col (LEFT) -->
			<!-- Quesito -->
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
										@foreach ($estadisticas as $origen_key => $esta)
											@if ($origen_key != 'total')
											<div>
												<span class="leyendaColor" style="background-color:{{$origenes->find($origen_key)->color}}; width:20px; height:10px; display: inline-block;border: 1px solid black;"></span>
												 &nbsp; {{$origenes->find($origen_key)->nombre}}
											 </div>
											@endif
										@endforeach
									</span>
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
        foreach ($estadisticas as $vkey => $estadisticaQuesito) {
					if ($vkey != 'total'){
         		echo $estadisticaQuesito.', ';
			 		}
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
							foreach ($estadisticas as $kl => $og_estas) {
								if ($kl != 'total'){
		          	echo $i.': "'.$origenes->find($kl)->nombre.'",';
								$i++;
							}
		 				 }
						 ?>
						}
			},
      sliceColors: [
        <?php
          /*foreach ($coloresQuesito as $vkeyColor => $colorQ) {
           echo '"'.$colorQ.'", ';
				 }*/
					foreach ($estadisticas as $kk => $og_esta) {
						if ($kk != 'total'){
          		echo '"'.$origenes->find($kk)->color.'", ';
						}
 				 }

        ?>
      ]

    });

  });
</script>


@endsection
