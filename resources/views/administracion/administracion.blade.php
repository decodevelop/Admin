@extends('layouts.backend')

@section('contenido')
<section class="content">
	<!-- title row -->
	<div class="row">
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3>{{$total_pedidos}}</h3>
					<p>Total pedidos</p>
				</div>
				<div class="icon">
					<i class="ion ion-bag"></i>
				</div>
				<a href="{{url('/pedidos')}}" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->
		<div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-green">
		<div class="inner">
		<h3>{{$pedidos_entregados}}<sup style="font-size: 20px">%</sup></h3>

		<p>Pedidos entregados</p>
		</div>
		<div class="icon">
		<i class="ion ion-stats-bars"></i>
		</div>
		<a href="{{url('/estadisticas/pedidos')}}" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
		</div>
		</div>
		<!-- ./col -->
		<div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-yellow">
		<div class="inner">
		<h3>{{$clientes}}</h3>

		<p>Total clientes</p>
		</div>
		<div class="icon">
		<i class="ion ion-person-add"></i>
		</div>
		<a href="#" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
		</div>
		</div>
		<!-- ./col -->
		<div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
		<div class="inner">
		<h3>{{number_format($ganancias, 2,",",".")}}€</h3>

		<p>Beneficios</p>
		</div>
		<div class="icon">
		<i class="ion ion-pie-graph"></i>
		</div>
		<a href="{{url('/estadisticas')}}" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
		</div>
		</div>
		<!-- ./col -->
	</div>
</section>
@endsection
