@extends('layouts.backend')
@section('titulo','Estadísticas > pedidos')
@section('titulo_h1','Estadísticas: Pedidos')

@section('estilos')
<style type="text/css">
	.progress{
		-webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.1)!important;
    	box-shadow: inset 0 1px 2px rgba(0,0,0,.1)!important;
	}
	.info-box-icon img {
    width: 25px;
	}
	.info-box-text {
    text-transform: inherit;
	}
	.products-list .product-img img {
    width: 25px;
    height: 25px;
	}
	.label{
		font-size: 85% !important;
	}
</style>


@endsection

@section('contenido')
<link rel="stylesheet" href="/css/custom.css">
<section class="content">
	<div class="row">
		<div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Filtro por semana {{date('W')}}</h3>
        </div>

        <div class="box-body">
          <form class="buscar-semana" method="post">
            <div class="col-md-12">
              <div class="col-md-2">
                FECHA
              </div>
              <div class="col-md-1">
                DESCARGAR
              </div>
            </div>
            <div class="col-md-12">

              <div class="col-md-2">

								<input type="date" name="filtro_fecha" class="form-control input-sm" value="{{$filtro_fecha}}">
              </div>
              <div class="col-md-1">
                <input type="checkbox" name="excel" id="switch"><label for="switch">Toggle</label>
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
	</div>
	<!-- title row -->
	<div class="row">
			<div class="col-md-6">
				@foreach ($pedidos as $key => $pedido)
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon " style="border-left:10px solid {{$pedido->color}}" style=""><img src="{{$pedido->web}}/img/favicon.ico" alt="{{$pedido->o_csv}}"></span>

            <div class="info-box-content">
              <span class="info-box-text">{{$pedido->web}}</span>
              <span class="info-box-number">{{$pedido->total}}€</span>
							<span class="progress-description">
                  {{$pedido->pedidos}} pedidos.
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
				@endforeach
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
										<span class="quesitoClientes col-xs-12" style="margin-top: 20px; margin-bottom: 20px;text-align: center;"></span>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
			<?php $count = 0; ?>
			@foreach ($dias_semana as $key => $dia_semana)
				<?php $count++; ?>
				@if ($count == 1 || $count == 5)
					<div class="col-md-12 col-sm-12 col-xs-12">
				@endif
				<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{$dia_semana->fecha_pedido}}</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>

						@foreach ($pedidos_dia_semana as $key => $pedido_dia_semana)

						@if ($pedido_dia_semana->fecha_pedido == $dia_semana->fecha_pedido )


            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <li class="item">
                  <div class="product-img">
                    <img src="{{$pedido_dia_semana->web}}/img/favicon.ico" alt="{{$pedido_dia_semana->o_csv}}">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">{{$pedido_dia_semana->web}}
                    <span class="label label-success pull-right">{{$pedido_dia_semana->total}}€</span></a>
										<span class="product-description">
                        {{$pedido_dia_semana->pedidos}} pedidos
                        </span>
									</div>
                </li>
                <!-- /.item -->
              </ul>
            </div>
            <!-- /.box-body -->
						@endif
						@endforeach
          </div>
					</div>
					@if ($count == 4 || $count == 7)
						</div>
					@endif

						@endforeach
			</div>
</section>
@endsection
@section('scripts')
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


    /* END INFORME MENSUAL */

    /*
     * INFORME MENSUAL
     * ---------
    */




    /* Quesito */
    var myvalues = [
      <?php
        foreach ($pedidos as $vkey => $estadisticaQuesito) {
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
							foreach ($pedidos as $kl => $og_estas) {
		          	echo $i.': "'.$og_estas->web.'",';
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
					foreach ($pedidos as $kk => $og_esta) {
          	echo '"'.$og_esta->color.'", ';
 				 }

        ?>
      ]

    });

  });
</script>


@endsection
