<?php 
use App\Pedidos_wix_importados;
?>
@extends('layouts.backend')
@section('titulo','Catalogos > Lista')
@section('titulo_h1','Catalogos')

@section('estilos')
<!-- DataTables -->
<link rel="stylesheet" href="{{url('/plugins/datatables/dataTables.bootstrap.css')}}">
<style>
#dataTables_pedidos tr:hover {
    border-left: 2px solid #bcb83c;
    border-right: 2px solid #bcb83c;
    background-color: rgba(243,236,18,0.5);
    cursor: pointer;
}
#dataTables_pedidos .incidencia {
	/*background-color: rgba(255, 0, 0, 0.42);*/
    color: #ff0000;
	transition: all 0.5s;
}

#dataTables_pedidos .incidencia:hover {
	border-left:2px solid #ff0000;
	border-right:2px solid #ff0000;
	background-color: rgba(255, 0, 0, 0.10) !important;
}
.productos > hr {
	margin: 2px;
    border-color: #f4f4f4;
}
.productos {
	font-size: 12px;
}
.productos > hr:last-child {
	display:none;
}
.subrallado {
    background-color: rgba(243,236,18,0.25);
    cursor: pointer;
}
</style>
@endsection

@section('contenido')
<section class="content">
	<div class="row">
		
    </div>
    <!-- /.row -->
</section>
@endsection

@section('scripts')
<!-- DataTables -->

<script src="{{url('/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{url('/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script>
$(document).ready(function(){

});
</script>
@endsection
