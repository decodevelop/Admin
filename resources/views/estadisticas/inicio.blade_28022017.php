@extends('layouts.backend')
@section('titulo','Incidencias > listado')
@section('titulo_h1','Incidencias')

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
			<dd>{{$origen_estadistica->nombre}} - {{$origen_estadistica->total}} €</dd>
			<?php $total += $origen_estadistica->total;  ?>
			@empty
			@endforelse
			</br>
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
			<dd>{{$origen_estadistica->nombre}} - {{$origen_estadistica->total}} €</dd>
			<?php $total += $origen_estadistica->total;  ?>
			@empty
			@endforelse
			</br>
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
              <h3 class="box-title">Ingresos año 2017</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
			<?php $total = ""  ?>
            @forelse($estadisticas_glob["año"] as $key => $origen_estadistica)
			<dd>{{$origen_estadistica->nombre}} - {{$origen_estadistica->total}} €</dd>
			<?php $total += $origen_estadistica->total;  ?>
			@empty
			@endforelse
			</br>
			<dd><b class="label label-danger " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
            </div>
            <!-- /.box-body -->
          </div>
		    <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Ingresos año 2016</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
			<?php $total = "" ?>
            @forelse($estadisticas_glob["añopasado"] as $key => $origen_estadistica)
			<dd>{{$origen_estadistica->nombre}} - {{$origen_estadistica->total}} €</dd>
			<?php $total += $origen_estadistica->total;  ?>
			@empty
			@endforelse
			</br>
			<dd><b class="label label-danger " style="text-transform: uppercase;">{{ "Total: ".$total }} €</b></dd>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        <!-- /.box -->
        </div>
		 <div class="col-md-6">
          <!-- AREA CHART -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Estadística Anual</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                  <div id="bar-chart" style="height: 300px;"></div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
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

<script>
 $(function () {
 /*
     * BAR CHART FLOT
     * ---------
     */

    var bar_data = {
      data: <?php ?>[/*["January", 10], ["February", 8], ["March", 4], ["April", 13], ["May", 17], ["June", 9]*/],
      color: "#3c8dbc"
    };
    $.plot("#bar-chart", [bar_data], {
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
    /* END BAR CHART */
  });
</script>
@endsection
