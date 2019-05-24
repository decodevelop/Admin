@extends('layouts.frontend')
@section('titulo','Amazon > imprimir')
@section('titulo_h1','Amazon')

@section('contenido')

<style>
p {
    FONT-SIZE: 20PX;
}
  body {
    background-color: white !important;
  }

  .containerImgBarcode {
    text-align: center;
    display: flex;
    justify-content: center;
    width: 6cm;
    height: 2.5cm;
    margin: 0cm 0.7cm 0cm 0cm;
    align-items: center;
    float: left;
    /*border-radius: 50%;
			border: 1px solid black;*/
  }

  p {
    margin-bottom: 0px;
  }

  .imgBarcode {

    /*width: 200px;*/
  }

  .nombreProductoBarcode {
    font-size: 14px;
  }

  .referenciaProductoBarcode {
    height: 20px;
  }

  .nombreProductoBarcode {
    height: 20px;
  }

  .page-break {
    display: block;
    page-break-before: always;
    /*height: 1.8cm;
	    width: 100%;
	    float: left;*/
  }

  .nombreProducto {
    position: relative;
    bottom: -44px;
    font-size: 10px;
  }

  @mediaprint {
    .page-break {
      display: block;
      page-break-before: always;
    }

    @page {
      margin: 0.3cm;
      margin-top: 0cm;
      margin-bottom: 0px;
    }

    body {
      margin-top: 0cm;
      margin-bottom: 0cm;
    }
  }
</style>
<div class="" style="margin:1cm 0 0 0.7cm;display: block;float: left;">


  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_1</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_2</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_3</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_4</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_5</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_6</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_7</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_8</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_1</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_2</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_3</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_4</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_5</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_6</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_7</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_8</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_9</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_10</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_11</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_12</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>LAPTOP_1</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_9</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_10</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_11</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_12</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_13</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_14</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>PC_15</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_13</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_14</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_15</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_16</p>

    </div>

  </div>
  <div class="containerImgBarcode">
    <div class="container-qr">

      <p>MAC_17</p>

    </div>

  </div>

</div>
<script src="{{url('/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<script>
  $(document).ready(function() {
    window.print();
  });
</script>
@endsection
