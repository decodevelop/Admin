<?php
use App\Clientes;
use App\Direcciones;
?>
@extends('layouts.backend')

@section('titulo', 'Generador etiquetas')
@section('titulo_h1', 'Generador etiquetas')

@section('contenido')
<section class="content">
	<div class="row">
	<!-- left column -->
  <div class="box-body">
    <div class="col-xs-12">


      <div class="col-lg-6 col-md-9 col-sm-12">
        {{-- Nombre, referencia, medidas, foto --}}
        <div class="col-sm-12">
          <form class="" action="" method="post">


            <table class="table">

              <tbody>

                <tr>
                  <td><input type="hidden" name="_token" class="form-control" value="{{ csrf_token() }}"></td>
                </tr>
                <tr>
                  <td>Nombre:</td>
                  <td> <input type="text" name="nombre" class="form-control" value=""> </td>
                </tr>
                <tr>
                  <td>referencia:</td>
                  <td> <input type="text" name="referencia" class="form-control" value=""> </td>
                </tr>
                <tr>
                  <td>medidas:</td>
                  <td> <input type="text" name="medidas" class="form-control" value=" x cm "> </td>
                </tr>
                <tr>
                  <td>url foto:</td>
                  <td> <input type="text" name="foto" class="form-control" placeholder="http://ejemplo/imagen.jpg" value=""> </td>
                </tr>
                <tr>
                  <td>Diseño:</td>
                  <td>
                    <ul style="list-style:none">
                      <li><label><input type="radio" name="diseño" value="8x21">  8x21</label></li>
                      <li><label><input type="radio" name="diseño" value="a4">  A4</label></li>
                      <li><label><input type="radio" name="diseño" value="2x21">  2,8x21</label></li>
                      <li><label><input type="radio" name="diseño" value="8x21_imagen">  8x21 imagen</label></li>
                    </ul>
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td> <button type="submit" class="btn btn-primary pull-right">generar etiqueta</button> </td>
                </tr>

              </tbody>
            </table>
          </form>
        </div>







      </div>
    </div>
	</div>
</section>
@endsection
