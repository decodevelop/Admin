<?php
use App\Clientes;
use App\Direcciones;
?>
@extends('layouts.backend')

@section('titulo', 'Generador de consulta')
@section('titulo_h1', 'Generador de consulta')

@section('contenido')
<section class="content">
	<div class="row">
		<div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Cálculo de precio base</h3>
        </div>
        <div class="box-body">
					<div class="col-xs-3">
						Precio final <input type="number" name="pFinal1" id="pFinal1" value="">
					</div>
					<div class="col-xs-3">
						Descuento <input type="number" name="pDescuento1" id="pDescuento1" value="60">
					</div>
					<div class="col-xs-3">
						Precio base <input type="number" name="pBase1" id="pBase1" value="">
					</div>
				</div>
				<div class="box-footer">
					<!--input type="submit" name="calculBase" id="calculBase" value="Calcular"-->
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@section('scripts')
	<script>
	$(document).ready(function(){
		$('#calculBase').click(function(){
			calcul_preuBase();
		});

		$('#pFinal1').keyup(function(){
			calcul_preuBase();
		});

		$('#pBase1').keyup(function(){
			calcul_preuFinal();
		});
	});

	function calcul_preuBase(){
		var resultado = 0;
		var precioFinal = $('#pFinal1').val();
		var descuento = 100-$('#pDescuento1').val();
		resultado = precioFinal/(descuento/100);

		 $('#pBase1').val(Math.round(resultado*1000)/1000);
	}
	function calcul_preuFinal(){
		var resultado = 0;
		var precioFinal = $('#pBase1').val();
		var descuento = 100-$('#pDescuento1').val();
		resultado = precioFinal*(descuento/100);

		 $('#pFinal1').val(Math.round(resultado*1000)/1000);
	}
	</script>

@endsection
