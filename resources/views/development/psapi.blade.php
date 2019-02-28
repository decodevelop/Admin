@extends('layouts.backend')
@section('titulo','PS API')
@section('titulo_h1','PS API')

@section('estilos')
  <link rel="stylesheet" href="/css/custom.css">
@endsection

@section('contenido')
<section class="content">
  <div class="row">

    @foreach ($resources as $nodeKey => $node)

      <div class="">
        {{$nodeKey}} = {{$resources->$nodeKey}}
      </div>

    @endforeach

  </div>
</section>
@endsection
