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
            </div>
            <div class="col-md-12">
              <div class="col-md-2">
                <select name="mes" id="filtro-mes" class="form-control">
                  <option value="1" {{(date('m')==1) ? 'selected' : '' }}>Enero</option>
                  <option value="2" {{(date('m')==2) ? 'selected' : '' }}>Febrero</option>
                  <option value="3" {{(date('m')==3) ? 'selected' : '' }}>Marzo</option>
                  <option value="4" {{(date('m')==4) ? 'selected' : '' }}>Abril</option>
                  <option value="5" {{(date('m')==5) ? 'selected' : '' }}>Mayo</option>
                  <option value="6" {{(date('m')==6) ? 'selected' : '' }}>Junio</option>
                  <option value="7" {{(date('m')==7) ? 'selected' : '' }}>Julio</option>
                  <option value="8" {{(date('m')==8) ? 'selected' : '' }}>Agosto</option>
                  <option value="9" {{(date('m')==9) ? 'selected' : '' }}>Septiembre</option>
                  <option value="10" {{(date('m')==10) ? 'selected' : '' }}>Octubre</option>
                  <option value="11" {{(date('m')==11) ? 'selected' : '' }}>Noviembre</option>
                  <option value="12" {{(date('m')==12) ? 'selected' : '' }}>Diciembre</option>
                </select>
              </div>
              <div class="col-md-2">
                <select name="any" id="filtro-ano" class="form-control">
									<option value="2020" {{(date('Y')==2020) ? 'selected' : '' }}>2020</option>
									<option value="2019" {{(date('Y')==2019) ? 'selected' : '' }}>2019</option>
									<option value="2018" {{(date('Y')==2018) ? 'selected' : '' }}>2018</option>
                  <option value="2017" {{(date('Y')==2017) ? 'selected' : '' }}>2017</option>
                  <option value="2016" {{(date('Y')==2016) ? 'selected' : '' }}>2016</option>
                </select>
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
		<div class="col-md-6">
			@php
				$i = 2
			@endphp
			@foreach ($estadisticas as $periodo => $estadistica)
			@if ($i%2 == 0 ) <div class="fila-estadisticas">  @endif

					<div class="col-md-6 ingresos-año">
								<!-- DONUT CHART -->
								<div class="box box-primary">
									<div class="box-header with-border">
										<h3 class="box-title">Ingresos

												@if($periodo == 'semana')
													Ingresos semana actual
												@elseif($periodo == 'mes')
													Ingresos mes actual
												@elseif($periodo == 'mes_anterior')
													<?php echo  (date ("m")-1)."-".date ("Y") ?>
												@elseif($periodo == 'mes_año_anterior')
													 <?php echo  date ("m")."-".(date ("Y")-1) ?>
												@elseif($periodo == 'año')
													año <?php echo date ("Y") ?>
												@elseif($periodo == 'año_anterior')
													año <?php echo date ("Y")-1 ?>
												@elseif($periodo == '2años_anteriores')
													año <?php echo date ("Y")-2 ?>
												@else
													{{$periodo}}
												@endif



										</h3>
										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
											</button>
											<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
										</div>
									</div>
									<div class="box-body">
										<table class="table table-bordered">
											@foreach ($estadistica as $origen_key => $esta)
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
												Total incidencias: {{number_format($incidencias[$periodo], 2, ',', '.' )}} €
											</b>
										</dd>
										<dd>
											<b class="label label-primary " title="total - incidencias" style="text-transform: uppercase;">
												Total: {{number_format($estadisticas[$periodo]['total'] - $incidencias[$periodo], 2, ',', '.' ) }} €
											</b>
										</dd>
									</div>
									<!-- /.box-body -->
								</div>
							<!-- /.box -->
					</div>

					@if ($i%2 != 0 )  </div>  @endif
					@php $i++ @endphp
			@endforeach







			</div>

        <!-- /.col (LEFT) -->

				<div class="col-md-12">
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
											@foreach ($estadisticas['año'] as $origen_key => $esta)
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

				<div class="col-md-12">
				 <div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Estadística Clientes - <?php echo date ("Y")-1 ?></h3>
							<div class="box-tools pull-right">
							</div>
							<div class="box-body">
								<div>
										<span class="quesitoClientes-1 col-xs-8" style="margin-top: 20px; margin-bottom: 20px;text-align: center;"></span>
										<span class="leyendaQuesito col-xs-4">
											<p>Leyenda: </p>
											@foreach ($estadisticas['año_anterior'] as $origen_key => $esta)
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

				<div class="col-md-12">
				 <div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Estadística Clientes - <?php echo date ("Y")-2 ?></h3>
							<div class="box-tools pull-right">
							</div>
							<div class="box-body">
								<div>
										<span class="quesitoClientes-2 col-xs-8" style="margin-top: 20px; margin-bottom: 20px;text-align: center;"></span>
										<span class="leyendaQuesito col-xs-4">
											<p>Leyenda: </p>
											@foreach ($estadisticas['2años_anteriores'] as $origen_key => $esta)
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
		<div class="col-md-6 graficos">


		 <div class="col-md-12">
				<!-- AREA CHART -->
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#bars-{{date('Y')}}">{{date('Y')}}</a></li>
					<li><a data-toggle="tab" href="#bars-{{date('Y')-1}}">{{date('Y')-1}}</a></li>
					<li><a data-toggle="tab" href="#bars-{{date('Y')-2}}">{{date('Y')-2}}</a></li>
				</ul>
				<div class="tab-content">
					@for ($a=date ("Y"); $a > 2015 ; $a--)
					<div id="bars-{{$a}}" class="tab-pane fade in active">
							<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Estadística Mensual - {{$a}}</h3>

								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<div class="chart">
										<div id="bar-chart-mensual-{{$a}}" style="height: 300px;"></div>
								</div>
							</div>
							<!-- /.box-body -->
							</div>
					</div>
					@endfor
					<!-- /.box -->

				</div>
			</div>
			<div class="col-md-12">
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
	 var bar_data_mensual = new Array();
	 @for ($a=date ("Y"); $a > 2015 ; $a--)
		 bar_data_mensual[{{$a}}] = {
			 data: [
			 ["Enero", <?php echo $estadistica_mensual_final[$a][1] ?>],
			 ["Febrero", <?php echo $estadistica_mensual_final[$a][2] ?>],
			 ["Marzo", <?php echo $estadistica_mensual_final[$a][3] ?>],
			 ["Abril", <?php echo $estadistica_mensual_final[$a][4] ?>],
			 ["Mayo", <?php echo $estadistica_mensual_final[$a][5] ?>],
			 ["Junio", <?php echo $estadistica_mensual_final[$a][6] ?>],
			 ["Julio", <?php echo $estadistica_mensual_final[$a][7] ?>],
			 ["Agosto", <?php echo $estadistica_mensual_final[$a][8] ?>],
			 ["Septiembre", <?php echo $estadistica_mensual_final[$a][9] ?>],
			 ["Octubre", <?php echo $estadistica_mensual_final[$a][10] ?>],
			 ["Noviembre", <?php echo $estadistica_mensual_final[$a][11] ?>],
			 ["Diciembre", <?php echo $estadistica_mensual_final[$a][12] ?>]
			 ],
			 color: "#3c8dbc"
		 };
		 $.plot("#bar-chart-mensual-{{$a}}", [bar_data_mensual[{{$a}}]], {
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
	 @endfor


	 var bar_data_anual = {
		 data: [
			@foreach ($estadistica_anual_final as $any => $total_any)
				[{{$any}}, {{$total_any}}],
			@endforeach
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


	 var myvalues = [
		 <?php
			 foreach ($estadisticas["año"] as $vkey => $estadisticaQuesito) {
				if ($vkey != 'total'){
					echo $estadisticaQuesito.', ';
				}
			 }
		 ?>
	 ];

	 var myvalues_1 = [
		 <?php
			 foreach ($estadisticas["año_anterior"] as $vkey => $estadisticaQuesito) {
				if ($vkey != 'total'){
					echo $estadisticaQuesito.', ';
				}
			 }
		 ?>
	 ];

	 var myvalues_2 = [
		 <?php
			 foreach ($estadisticas["2años_anteriores"] as $vkey => $estadisticaQuesito) {
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
						 foreach ($estadisticas["año"] as $kl => $og_estas) {
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
				 foreach ($estadisticas["año"] as $kk => $og_esta) {
					 if ($kk != 'total'){
					 	echo '"'.$origenes->find($kk)->color.'", ';
					}
				}

			 ?>
		 ]

	 });

	 $('.quesitoClientes-1').sparkline(myvalues,
	 {
		 type: 'pie',
		 width: 250,
		 height: 250,
		 tooltipFormat: <?php echo "'{{offset:offset}} {{value:myvalues}}€ ({{percent.1}}%)'" ?>,
		 tooltipValueLookups: {
				 'offset': {
					 <?php
						 $i=0;
						 foreach ($estadisticas["año_anterior"] as $kl => $og_estas) {
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
				 foreach ($estadisticas["año_anterior"] as $kk => $og_esta) {
					if ($kk != 'total'){
					 echo '"'.$origenes->find($kk)->color.'", ';
				 	}
				}

			 ?>
		 ]

	 });

	 $('.quesitoClientes-2').sparkline(myvalues,
	 {
		 type: 'pie',
		 width: 250,
		 height: 250,
		 tooltipFormat: <?php echo "'{{offset:offset}} {{value:myvalues}}€ ({{percent.1}}%)'" ?>,
		 tooltipValueLookups: {
				 'offset': {
					 <?php
						 $i=0;
						 foreach ($estadisticas["2años_anteriores"] as $kl => $og_estas) {
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
				 foreach ($estadisticas["2años_anteriores"] as $kk => $og_esta) {
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
