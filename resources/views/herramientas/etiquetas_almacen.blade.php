<link rel="stylesheet" href="{{url('/css/fonts.css')}}" type="text/css" />


@if ($datos["diseño"] == "8x21")
  <div class="" style="display: block;float: left;text-transform: uppercase;width:100%;height:8cm">
    <div class="cartel" style="height: 100%;margin: 0 5%;display: flex;align-items: center;justify-content: center;">
      <div class="texto" style="padding:0 1cm;">
        <h1 style="font-size: 1.2cm;font-family: avenir;line-height: 1.4cm;letter-spacing: 0.1cm;margin: 0;text-align:center">{{$datos["nombre"] }}</h1>
        <h3 style="font-family: avenir;font-size: 0.8cm;text-align: center;margin: 0.3cm 0vw 0 0;font-weight: normal;">{{$datos["medidas"] }}</h3>
        <img src="https://decowood.es/img/decowood-logo-1525771794.jpg" style="width: 22vw;margin: auto;text-align: center;display: block;margin-top: 1.6cm;" >
      </div>

    </div>
  </div>
@elseif ($datos["diseño"] == "a4")
  <div class="" style="display: block;float: left;     text-transform: uppercase;width:100%">
    <div class="cartel" style="text-align:center;height: 100%;margin: 0 5%;display: flex;align-items: center;justify-content: center;">
      <div class="texto" style="padding: 2vw;">
        <h1 style="font-size: 7vw;font-family: avenir;line-height: 7vw;    letter-spacing: 0.2vw;">{{$datos["nombre"] }}</h1>
        <img src="https://decowood.es/img/decowood-logo-1525771794.jpg" style="width: 25vw;position: absolute;margin: auto;bottom: 10%;left: 0;right: 0;" >
      </div>

    </div>
  </div>
@elseif ($datos["diseño"] == "2x21")
  <div class="" style="display: block;float: left;text-transform: uppercase;width:100%;height:2.8cm">
    <div class="cartel" style="height: 100%;margin: 0 2%;display: flex;align-items: center;justify-content: center;">
      <div class="texto" style="padding:0 0vw;">
        <h1 style="font-size: 3vw;font-family: avenir;letter-spacing: 0.2vw;margin: 0;">{{$datos["nombre"] }}</h1>
        <img src="https://decowood.es/img/decowood-logo-1525771794.jpg" style="width: 9vw;margin: auto;text-align: center;display: block;margin-top: 0.2cm;" >
      </div>

    </div>
  </div>
@elseif ($datos["diseño"] == "8x21_imagen")
  <div class="" style="display: block;float: left;text-transform: uppercase;width:100%;height:8cm">
    <div class="cartel" style="height: 100%;margin: 0 5%;display: flex;align-items: center;justify-content: center;">
      <div class="texto" style="width:65%">
        <h1 style="font-size: 1cm;font-family: avenir;line-height: 1.1cm;letter-spacing: 0.1cm;margin: 0;text-align:left; margin-bottom: 1.5cm;">{{$datos["nombre"] }}</h1>
        <h3 style="font-family: avenir;font-size: 0.7cm;text-align: left;margin: 0.3cm 0vw 0 0;font-weight: normal;">{{$datos["medidas"] }}</h3>
        <h3 style="font-family: avenir;font-size: 0.7cm;text-align: left;margin: 0.1cm 0vw 0 0;font-weight: normal;">{{$datos["referencia"] }}</h3>
      </div>
      <div class="texto" style="width:35%;    text-align: center;">
        <img src="{{$datos["foto"] }}" alt="" style="width:95%;margin-top: -1.2cm;">
        <img src="https://decowood.es/img/decowood-logo-1525771794.jpg" style="width: 4.5cm;margin: auto;text-align: center;display: block;" >
      </div>


    </div>
  </div>
@endif
