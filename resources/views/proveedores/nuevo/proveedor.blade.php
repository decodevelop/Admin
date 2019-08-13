<div class="box-body">
  <div class="col-lg-6 col-xs-12">
    <table class="table">
      <tbody>
        <tr>
          <td>Nombre:</td>
          <td><input name="nombre" class="form-control" value="@if(isset($requestErr)){{$requestErr['nombre']}}@endif"></td>
        </tr>
        <tr>
          <td>E-Mail:</td>
          <td><input name="email" class="form-control" value="@if(isset($requestErr)){{$requestErr['email']}}@endif"></td>
        </tr>
        <tr>
          <td>Teléfono:</td>
          <td><input name="telefono" class="form-control" value="@if(isset($requestErr)){{$requestErr['telefono']}}@endif"></td>
        </tr>
        <tr>
          <td>Plazo de entrega:</td>
          <td><input name="plazo_entrega" class="form-control" value="@if(isset($requestErr)){{$requestErr['plazo_entrega']}}@endif"></td>
        </tr>
        <tr>
          <td>Plazo de entrega Web:</td>
          <td><input name="plazo_entrega_web" class="form-control" value="@if(isset($requestErr)){{$requestErr['plazo_entrega_web']}}@endif"></td>
        </tr>
        <tr>
          <td>Envío:</td>
          <td><input name="envio" class="form-control" value="@if(isset($requestErr)){{$requestErr['envio']}}@endif"></td>
        </tr>
        <tr>
          <td>Método de pago:</td>
          <td><input name="metodo_pago" class="form-control" value="@if(isset($requestErr)){{$requestErr['metodo_pago']}}@endif"></td>
        </tr>
        <tr>
          <td>Precio especial campaña:</td>
          <td><input name="precio_esp_campana" class="form-control" value="@if(isset($requestErr)){{$requestErr['precio_esp_campana']}}@endif"></td>
        </tr>
        <tr>
          <td>Logística:</td>
          <td><input name="logistica" class="form-control" value="@if(isset($requestErr)){{$requestErr['logistica']}}@endif"></td>
        </tr>
        <tr>
          <td rowspan="2">Contrato:</td>
          <td><input name="contrato" class="form-control" value="@if(isset($requestErr)){{$requestErr['contrato']}}@endif"></td>
        </tr>
        <tr>
          <td><input type="file" name="contrato_pdf" accept="application/pdf"></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="col-lg-6 col-xs-12">
    <table class="table">
      <tbody>
        <tr>
          <td>Última visita:</td>
          <td><input type="date" name="ultima_visita" class="form-control" value="@if(isset($requestErr)){{$requestErr['ultima_visita']}}@endif"></td>
        </tr>
        <tr>
          <td>Vacaciones inicio:</td>
          <td><input type="date" name="vacaciones_inicio" class="form-control" value="@if(isset($requestErr)){{$requestErr['vacaciones_inicio']}}@endif"></td>
        </tr>
        <tr>
          <td>Vacaciones fin:</td>
          <td><input type="date" name="vacaciones_fin" class="form-control" value="@if(isset($requestErr)){{$requestErr['vacaciones_fin']}}@endif"></td>
        </tr>
        <tr>
          <td>Observaciones:</td>
          <td>
            @if(isset($requestErr['observaciones']))
              <div contenteditable="true" data-inputname="observaciones" class="textarea-transform-init textarea-observaciones" rows="3" cols="20">
                {!! $requestErr['observaciones'] !!}
              </div>
            @else
              <textarea data-inputname="observaciones" class="textarea-transform-init textarea-observaciones" rows="3" cols="20"></textarea>
            @endif
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<style media="screen">
  .richText .richText-editor {
    height: 220px;
  }
</style>
