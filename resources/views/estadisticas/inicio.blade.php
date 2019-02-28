@extends('layouts.backend')
@section('titulo','Estadísticas > listado')
@section('titulo_h1','Estadísticas')

@section('estilos')
@endsection

@section('contenido')
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

			<div class="fila-estadisticas">
				<div class="col-md-6 semana-actual">

		          <!-- DONUT CHART -->
		          <div class="box box-primary">
		            <div class="box-header with-border">
		              <h3 class="box-title">Ingresos semana actual</h3>
		              <div class="box-tools pull-right">
		                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                </button>
		                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		              </div>
		            </div>
		            <div class="box-body">
					<?php $total = 0; ?>
		            @forelse($estadisticas_glob["semana"] as $key => $origen_estadistica)
					<dd>{{$origen_estadistica->nombre}} -
						<?php
							$price_noiva = ($origen_estadistica->total) / 1.21;
						 	$price_noiva = number_format( $price_noiva , 2, ',', '.' );
							echo $price_noiva ?> €</dd>


					<?php $total += $origen_estadistica->total; ?>
					@empty
					@endforelse
					</br>
					<?php
								$total_incidencias = ($productos_incidencia["semana"]["total"]) / 1.21;
								$total = ($total) / 1.21;
								$total -= $total_incidencias;
								$total_incidencias = number_format( $total_incidencias , 2, ',', '.' );
								$total = number_format( $total , 2, ',', '.' );  ?>
					<dd style="margin-bottom: 10px; font-size:17px;"><b class="label label-default " style="text-transform: uppercase;">{{ "Total incidencias: ".$total_incidencias }} €</b></dd>
					<dd><b class="label label-primary " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
		            </div>
		            <!-- /.box-body -->
		          </div>
		          <!-- /.box -->
		        <!-- /.box -->
		        </div>
				<div class="col-md-6 mes-actual">

		          <!-- DONUT CHART -->
		          <div class="box box-primary">
		            <div class="box-header with-border">
		              <h3 class="box-title">Ingresos mes actual</h3>
		              <div class="box-tools pull-right">
		                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                </button>
		                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		              </div>
		            </div>
		            <div class="box-body">
					<?php $total = 0; ?>
		            @forelse($estadisticas_glob["mes"] as $key => $origen_estadistica)
					<dd>{{$origen_estadistica->nombre}} - <?php
						$price_noiva = ($origen_estadistica->total) / 1.21;
						$price_noiva = number_format( $price_noiva , 2, ',', '.' );
						echo $price_noiva ?> €</dd>
					<?php $total += $origen_estadistica->total;?>
					@empty
					@endforelse
					</br>
					<?php
								$total_incidencias = ($productos_incidencia["mes"]["total"]) / 1.21;
								$total = ($total) / 1.21;
								$total -= $total_incidencias;
								$total_incidencias = number_format( $total_incidencias , 2, ',', '.' );
								$total = number_format( $total , 2, ',', '.' );  ?>
					<dd style="margin-bottom: 10px; font-size:17px;"><b class="label label-default " style="text-transform: uppercase;">{{ "Total incidencias: ".$total_incidencias }} €</b></dd>
					<dd><b class="label label-primary " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
		            </div>
		            <!-- /.box-body -->
		          </div>

		        <!-- /.box -->
		        </div>
			</div>
			<div class="fila-estadisticas">
				<div class="col-md-6 mes-anterior">
					<!-- DONUT CHART -->
		        <div class="box box-primary">
		          <div class="box-header with-border">
		            <h3 class="box-title">Ingresos {{date('m')-1}}-{{date('Y')}}</h3>
		            <div class="box-tools pull-right">
		              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		              </button>
		              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		            </div>
		          </div>
		          <div class="box-body">
								<?php $total = 0; ?>
						          @forelse($estadisticas_glob["mesAnterior"] as $key => $origen_estadistica)
						    <dd>{{$origen_estadistica->nombre}} - <?php
									$price_noiva = ($origen_estadistica->total) / 1.21;
									$price_noiva = number_format( $price_noiva , 2, ',', '.' );
									echo $price_noiva ?> €</dd>
								<?php $total += $origen_estadistica->total;?>
						    @empty
						    @endforelse
						    </br>
								<?php
											$total_incidencias = ($productos_incidencia["mesAnterior"]["total"]) / 1.21;
											$total = ($total) / 1.21;
											$total -= $total_incidencias;
											$total_incidencias = number_format( $total_incidencias , 2, ',', '.' );
											$total = number_format( $total , 2, ',', '.' );  ?>
								<dd style="margin-bottom: 10px; font-size:17px;"><b class="label label-default " style="text-transform: uppercase;">{{ "Total incidencias: ".$total_incidencias }} €</b></dd>
								<dd><b class="label label-primary " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
						          </div>
						          <!-- /.box-body -->
			        </div>
			        <!-- /.box -->
				</div>
				<div class="col-md-6 mes-año-anterior">
					<!-- DONUT CHART -->
		        <div class="box box-primary">
		          <div class="box-header with-border">
		            <h3 class="box-title">Ingresos {{date('m')}}-{{date('Y')-1}}</h3>
		            <div class="box-tools pull-right">
		              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		              </button>
		              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		            </div>
		          </div>
		          <div class="box-body">
							<?php $total = 0;?>
				          @forelse($estadisticas_glob["mesAñoAnterior"] as $key => $origen_estadistica)
				    <dd>{{$origen_estadistica->nombre}} - <?php
							$price_noiva = ($origen_estadistica->total) / 1.21;
							$price_noiva = number_format( $price_noiva , 2, ',', '.' );
							echo $price_noiva ?> €</dd>
						<?php $total += $origen_estadistica->total;?>
				    @empty
				    @endforelse
				    </br>
						<?php
									$total_incidencias = ($productos_incidencia["mesAñoAnterior"]["total"]) / 1.21;
									$total = ($total) / 1.21;
									$total -= $total_incidencias;
									$total_incidencias = number_format( $total_incidencias , 2, ',', '.' );
									$total = number_format( $total , 2, ',', '.' );  ?>
						<dd style="margin-bottom: 10px; font-size:17px;"><b class="label label-default " style="text-transform: uppercase;">{{ "Total incidencias: ".$total_incidencias }} €</b></dd>
						<dd><b class="label label-primary " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
				          </div>
				          <!-- /.box-body -->
		        </div>
		        <!-- /.box -->
				</div>
			</div>
			<div class="fila-estadisticas">
				<div class="col-md-6 ingresos-año">

		          <!-- DONUT CHART -->
		          <div class="box box-primary">
		            <div class="box-header with-border">
		              <h3 class="box-title">Ingresos año <?php echo date ("Y") ?></h3>
		              <div class="box-tools pull-right">
		                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                </button>
		                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		              </div>
		            </div>
		            <div class="box-body">
					<?php $total = 0;?>
		            @forelse($estadisticas_glob["año"] as $key => $origen_estadistica)
					<dd>{{$origen_estadistica->nombre}} - <?php
						$price_noiva = ($origen_estadistica->total) / 1.21;
						$price_noiva = number_format( $price_noiva , 2, ',', '.' );
						echo $price_noiva ?> €</dd>
					<?php $total += $origen_estadistica->total;?>
					@empty
					@endforelse
					</br>
					<?php
								$total_incidencias = ($productos_incidencia["año"]["total"]) / 1.21;
								$total = ($total) / 1.21;
								$total -= $total_incidencias;
								$total_incidencias = number_format( $total_incidencias , 2, ',', '.' );
								$total = number_format( $total , 2, ',', '.' );  ?>
					<dd style="margin-bottom: 10px; font-size:17px;"><b class="label label-default " style="text-transform: uppercase;">{{ "Total incidencias: ".$total_incidencias }} €</b></dd>
					<dd><b class="label label-primary " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
		            </div>
		            <!-- /.box-body -->
		          </div>
		        <!-- /.box -->
		      </div>
				<div class="col-md-6 ingresos-año">
						<div class="box box-primary">
		            <div class="box-header with-border">
		              <h3 class="box-title">Ingresos año <?php echo date ("Y")-1 ?></h3>
		              <div class="box-tools pull-right">
		                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                </button>
		                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		              </div>
		            </div>
		            <div class="box-body">
					<?php $total = 0;?>
		            @forelse($estadisticas_glob["añopasado"] as $key => $origen_estadistica)
					<dd>{{$origen_estadistica->nombre}} - <?php
						$price_noiva = ($origen_estadistica->total) / 1.21;
						$price_noiva = number_format( $price_noiva , 2, ',', '.' );
						echo $price_noiva ?> €</dd>
					<?php $total += $origen_estadistica->total;?>
					@empty
					@endforelse
					</br>
					<?php
								$total_incidencias = ($productos_incidencia["añopasado"]["total"]) / 1.21;
								$total = ($total) / 1.21;
								$total -= $total_incidencias;
								$total_incidencias = number_format( $total_incidencias , 2, ',', '.' );
								$total = number_format( $total , 2, ',', '.' );  ?>
					<dd style="margin-bottom: 10px; font-size:17px;"><b class="label label-default " style="text-transform: uppercase;">{{ "Total incidencias: ".$total_incidencias }} €</b></dd>
					<dd><b class="label label-primary " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
		        <?php if(date ("Y")-1 == 2016){ ?>
		          <dd><b class="label label-primary " style="text-transform: uppercase;">Total de enero a diciembre: 481.440,86 €</b></dd>
		        <?php } ?>
		            </div>
		            <!-- /.box-body -->
		          </div>
		          <!-- /.box -->
					</div>

				</div>
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
                      @forelse($estadisticas_glob["año"] as $kk => $og_esta)
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
                      @forelse($estadisticas_glob["añopasado"] as $kk => $og_esta)
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
                      @forelse($estadisticas_glob["2años"] as $kk => $og_esta)
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
		<div class="col-md-6 graficos">


		 <div class="col-md-12">
        <!-- AREA CHART -->
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#bars-{{date('Y')}}">{{date('Y')}}</a></li>
					<li><a data-toggle="tab" href="#bars-{{date('Y')-1}}">{{date('Y')-1}}</a></li>
					<li><a data-toggle="tab" href="#bars-{{date('Y')-2}}">{{date('Y')-2}}</a></li>
				</ul>
				<div class="tab-content">
					<div id="bars-{{date('Y')}}" class="tab-pane fade in active">
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
						</div>
	          	<!-- /.box -->
					<div id="bars-{{date('Y')-1}}" class="tab-pane fade in active" >
	          <div class="box box-primary">
		            <div class="box-header with-border">
		              <h3 class="box-title">Estadística Mensual - <?php echo date ("Y")-1 ?></h3>

		              <div class="box-tools pull-right">
		                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                </button>
		                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		              </div>
		            </div>
		            <div class="box-body">
		              <div class="chart">
		                 <div id="bar-chart-mensual-1" style="height: 300px;"></div>
		              </div>
		            </div>
		            <!-- /.box-body -->
		         </div>
					</div>
					<div id="bars-{{date('Y')-2}}" class="tab-pane fade in active">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Estadística Mensual - <?php echo date ("Y")-2 ?></h3>

								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<div class="chart">
									 <div id="bar-chart-mensual-2" style="height: 300px;"></div>
								</div>
							</div>
							<!-- /.box-body -->
					 </div>
					</div>
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
		var bar_data_mensual_1 = {
      data: [
      ["Enero", <?php echo $estadistica_mensual_final_1[0] ?>],
      ["Febrero", <?php echo $estadistica_mensual_final_1[1] ?>],
      ["Marzo", <?php echo $estadistica_mensual_final_1[2] ?>],
      ["Abril", <?php echo $estadistica_mensual_final_1[3] ?>],
      ["Mayo", <?php echo $estadistica_mensual_final_1[4] ?>],
      ["Junio", <?php echo $estadistica_mensual_final_1[5] ?>],
      ["Julio", <?php echo $estadistica_mensual_final_1[6] ?>],
      ["Agosto", <?php echo $estadistica_mensual_final_1[7] ?>],
      ["Septiembre", <?php echo $estadistica_mensual_final_1[8] ?>],
      ["Octubre", <?php echo $estadistica_mensual_final_1[9] ?>],
      ["Noviembre", <?php echo $estadistica_mensual_final_1[10] ?>],
      ["Diciembre", <?php echo $estadistica_mensual_final_1[11] ?>]
      ],
      color: "#3c8dbc"
    };
		var bar_data_mensual_2 = {
      data: [
      ["Enero", <?php echo $estadistica_mensual_final_2[0] ?>],
      ["Febrero", <?php echo $estadistica_mensual_final_2[1] ?>],
      ["Marzo", <?php echo $estadistica_mensual_final_2[2] ?>],
      ["Abril", <?php echo $estadistica_mensual_final_2[3] ?>],
      ["Mayo", <?php echo $estadistica_mensual_final_2[4] ?>],
      ["Junio", <?php echo $estadistica_mensual_final_2[5] ?>],
      ["Julio", <?php echo $estadistica_mensual_final_2[6] ?>],
      ["Agosto", <?php echo $estadistica_mensual_final_2[7] ?>],
      ["Septiembre", <?php echo $estadistica_mensual_final_2[8] ?>],
      ["Octubre", <?php echo $estadistica_mensual_final_2[9] ?>],
      ["Noviembre", <?php echo $estadistica_mensual_final_2[10] ?>],
      ["Diciembre", <?php echo $estadistica_mensual_final_2[11] ?>]
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
		$.plot("#bar-chart-mensual-1", [bar_data_mensual_1], {
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
		$.plot("#bar-chart-mensual-2", [bar_data_mensual_2], {
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
		var myvalues_1 = [
      <?php
        foreach ($estadisticas_glob["añopasado"] as $vkey => $estadisticaQuesito) {
         echo $estadisticaQuesito->total.', ';
        }
      ?>
    ];
		var myvalues_2 = [
      <?php
        foreach ($estadisticas_glob["2años"] as $vkey => $estadisticaQuesito) {
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
							foreach ($estadisticas_glob["año"] as $kl => $og_estas) {
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
					foreach ($estadisticas_glob["año"] as $kk => $og_esta) {
          	echo '"'.$og_esta->color.'", ';
 				 }

        ?>
      ]

    });


		$('.quesitoClientes-1').sparkline(myvalues_1,
    {
      type: 'pie',
      width: 250,
      height: 250,
			tooltipFormat: <?php echo "'{{offset:offset}} {{value:myvalues}}€ ({{percent.1}}%)'" ?>,
			tooltipValueLookups: {
					'offset': {
						<?php
							$i=0;
							foreach ($estadisticas_glob["añopasado"] as $kl => $og_estas) {
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
					foreach ($estadisticas_glob["añopasado"] as $kk => $og_esta) {
          	echo '"'.$og_esta->color.'", ';
 				 }

        ?>
      ]

    });

		$('.quesitoClientes-2').sparkline(myvalues_2,
    {
      type: 'pie',
      width: 250,
      height: 250,
			tooltipFormat: <?php echo "'{{offset:offset}} {{value:myvalues}}€ ({{percent.1}}%)'" ?>,
			tooltipValueLookups: {
					'offset': {
						<?php
							$i=0;
							foreach ($estadisticas_glob["2años"] as $kl => $og_estas) {
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
					foreach ($estadisticas_glob["2años"] as $kk => $og_esta) {
          	echo '"'.$og_esta->color.'", ';
 				 }

        ?>
      ]

    });

  });
</script>
@endsection
