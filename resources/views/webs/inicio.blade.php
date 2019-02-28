<?php
use App\Clientes;
use App\Direcciones;
?>
@extends('layouts.backend')

@section('titulo', 'Acceso webs')
@section('titulo_h1', 'Acceso webs')

@section('contenido')
<link rel="stylesheet" href="/css/custom.css">
<section class="content">
  <div class="row">


  <div class="col-md-4 web-shop">
    <div class="web-view cabeceros">
      <div class="content-view">
        <div class="name-web">
          Cabeceros
        </div>
        <div class="links-web">
          <div class="link-store col-md-6">
            <a href="https://www.cabeceros.com/" target="_blank">Web</a>
          </div>
          <div class="link-admin col-md-6">
            <a href="https://www.cabeceros.com/admin349lfku2o/" target="_blank">Admin</a>
          </div>
          <div class="link-stores col-md-12">
            <a href="https://www.ascabeceiras.com" target="_blank">PT</a>
            <a href="https://www.latetedelit.fr" target="_blank">FR</a>
            <a href="https://www.letestiere.com" target="_blank">IT</a>
            <a href="https://www.bettkopfteile.com" target="_blank">GE</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4 web-shop">
    <div class="web-view cajasdemadera">
      <div class="content-view">
        <div class="name-web">
          Cajas de madera
        </div>
        <div class="links-web">
          <div class="link-store col-md-6">
            <a href="https://www.cajasdemadera.com/" target="_blank">Web</a>
          </div>
          <div class="link-admin col-md-6">
            <a href="https://www.cajasdemadera.com/admin754i99ird/" target="_blank">Admin</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4 web-shop">
    <div class="web-view decowoodes">
      <div class="content-view">
        <div class="name-web">
          Decowood.es
        </div>
        <div class="links-web">
          <div class="link-store col-md-6">
            <a href="https://decowood.es/" target="_blank">Web</a>
          </div>
          <div class="link-admin col-md-6">
            <a href="https://decowood.es/admin854gd436e/" target="_blank">Admin</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4 web-shop">
    <div class="web-view foxandsockses">
      <div class="content-view">
        <div class="name-web">
          Foxandsocks.es
        </div>
        <div class="links-web">
          <div class="link-store col-md-6">
            <a href="https://foxandsocks.es/" target="_blank">Web</a>
          </div>
          <div class="link-admin col-md-6">
            <a href="https://foxandsocks.es/admin7286njpgd/" target="_blank">Admin</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4 web-shop">
    <div class="web-view foxandsockseu">
      <div class="content-view">
        <div class="name-web">
          Foxandsocks.eu
        </div>
        <div class="links-web">
          <div class="link-store col-md-6">
            <a href="https://foxandsocks.eu/" target="_blank">Web</a>
          </div>
          <div class="link-admin col-md-6">
            <a href="https://foxandsocks.eu/admin7286njpgd/" target="_blank">Admin</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4 web-shop">
    <div class="web-view cojines">
      <div class="content-view">
        <div class="name-web">
          Cojines
        </div>
        <div class="links-web">
          <div class="link-store col-md-6">
            <a href="https://cojines.es/" target="_blank">Web</a>
          </div>
          <div class="link-admin col-md-6">
            <a href="https://cojines.es/admin0069afj9q/" target="_blank">Admin</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12 acceso-webs">
    <h3>Acceso todas las webs</h3>
    <span>Usuario:</span>
    <button onclick="copiarDatos('web-user')"><span id="web-user">info@decowood.es</span> </button>
    <br>
    <span>Contraseña:</span>
    <button onclick="copiarDatos('web-password')"><span id="web-password">6647969208430</span> </button>
  </div>
  </div>
</section>
<script>
  function copiarDatos(id_copiar) {
    var aux = document.createElement("input");
    aux.setAttribute("value", document.getElementById(id_copiar).innerHTML);
    document.body.appendChild(aux);
    aux.select();
    document.execCommand("copy");
    document.body.removeChild(aux);
  }
</script>
@endsection
