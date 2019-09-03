<div class="box-body">
  <div class="col-xs-12">
    <div id="direcciones" class="col-xs-12">
      @if(isset($requestErr['cont_direcciones']) && $requestErr['cont_direcciones'] > 1)
        @for ($i=0; $i < $requestErr['cont_direcciones']; $i++)
          <div class="product-combi col-xs-12">
            <div class="col-lg-6 col-xs-12">
              <table class="table">
                <tbody>
                  <tr>
                    <td style="border-top: 0px; width: 20%;"></td>
                    <td style="border-top: 0px;"><strong>Facturación</strong></td>
                  </tr>
                  <tr>
                    <td>Dirección:</td>
                    <td><input name="direccion_facturacion[]" data-type="text" class="form-control"  value="{{$requestErr['direccion_facturacion'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Código Postal:</td>
                    <td><input name="cp_facturacion[]" type="number" class="form-control"  value="{{$requestErr['cp_facturacion'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Ciudad:</td>
                    <td><input name="ciudad_facturacion[]" data-type="text" class="form-control"  value="{{$requestErr['ciudad_facturacion'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Estado:</td>
                    <td><input name="estado_facturacion[]" data-type="text" class="form-control" value="{{$requestErr['estado_facturacion'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>País:</td>
                    <td><input name="pais_facturacion[]" data-type="text" class="form-control" value="{{$requestErr['pais_facturacion'][$i]}}"></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-lg-6 col-xs-12">
              <table class="table">
                <tbody>
                  <tr>
                    <td style="border-top: 0px; width: 20%;"></td>
                    <td style="border-top: 0px;"><strong>Envío</strong></td>
                  </tr>
                  <tr>
                    <td>Dirección:</td>
                    <td><input name="direccion_envio[]" data-type="text" class="form-control"  value="{{$requestErr['direccion_envio'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Código Postal:</td>
                    <td><input name="cp_envio[]" type="number" class="form-control"  value="{{$requestErr['cp_envio'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Ciudad:</td>
                    <td><input name="ciudad_envio[]" data-type="text" class="form-control"  value="{{$requestErr['ciudad_envio'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Estado:</td>
                    <td><input name="estado_envio[]" data-type="text" class="form-control" value="{{$requestErr['estado_envio'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>País:</td>
                    <td><input name="pais_envio[]" data-type="text" class="form-control" value="{{$requestErr['pais_envio'][$i]}}"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        @endfor
      @else
        <div class="product-combi col-xs-12">
          <div class="col-lg-6 col-xs-12">
            <table class="table">
              <tbody>
                <tr>
                  <td style="border-top: 0px; width: 20%;"></td>
                  <td style="border-top: 0px;"><strong>Facturación</strong></td>
                </tr>
                <tr>
                  <td>Dirección:</td>
                  <td><input name="direccion_facturacion[]" data-type="text" class="form-control"  value="@if(isset($requestErr)){{$requestErr['direccion_facturacion'][0]}}@endif"></td>
                </tr>
                <tr>
                  <td>Código Postal:</td>
                  <td><input name="cp_facturacion[]" type="number" class="form-control"  value="@if(isset($requestErr)){{$requestErr['cp_facturacion'][0]}}@endif"></td>
                </tr>
                <tr>
                  <td>Ciudad:</td>
                  <td><input name="ciudad_facturacion[]" data-type="text" class="form-control"  value="@if(isset($requestErr)){{$requestErr['ciudad_facturacion'][0]}}@endif"></td>
                </tr>
                <tr>
                  <td>Estado:</td>
                  <td><input name="estado_facturacion[]" data-type="text" class="form-control" value="@if(isset($requestErr)){{$requestErr['estado_facturacion'][0]}}@endif"></td>
                </tr>
                <tr>
                  <td>País:</td>
                  <td><input name="pais_facturacion[]" data-type="text" class="form-control" value="@if(isset($requestErr)){{$requestErr['pais_facturacion'][0]}}@endif"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-lg-6 col-xs-12">
            <table class="table">
              <tbody>
                <tr>
                  <td style="border-top: 0px; width: 20%;"></td>
                  <td style="border-top: 0px;"><strong>Envío</strong></td>
                </tr>
                <tr>
                  <td>Dirección:</td>
                  <td><input name="direccion_envio[]" data-type="text" class="form-control"  value="@if(isset($requestErr)){{$requestErr['direccion_envio'][0]}}@endif"></td>
                </tr>
                <tr>
                  <td>Código Postal:</td>
                  <td><input name="cp_envio[]" type="number" class="form-control"  value="@if(isset($requestErr)){{$requestErr['cp_envio'][0]}}@endif"></td>
                </tr>
                <tr>
                  <td>Ciudad:</td>
                  <td><input name="ciudad_envio[]" data-type="text" class="form-control"  value="@if(isset($requestErr)){{$requestErr['ciudad_envio'][0]}}@endif"></td>
                </tr>
                <tr>
                  <td>Estado:</td>
                  <td><input name="estado_envio[]" data-type="text" class="form-control" value="@if(isset($requestErr)){{$requestErr['estado_envio'][0]}}@endif"></td>
                </tr>
                <tr>
                  <td>País:</td>
                  <td><input name="pais_envio[]" data-type="text" class="form-control" value="@if(isset($requestErr)){{$requestErr['pais_envio'][0]}}@endif"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      @endif
    </div>
  </div>
  <div class="col-xs-12">
    @if(isset($requestErr))
      <input id="cont_direcciones" class="hidden" type="number" name="cont_direcciones" value="{{$requestErr['cont_direcciones'][0]}}">
    @else
      <input id="cont_direcciones" class="hidden" type="number" name="cont_direcciones" value="1">
    @endif
    <button data-placement="top" data-toggle="tooltip" type="button" id="añadirDireccionButton" class="addPers btn btn-default">
      <i class="fa fa-plus"></i> Añadir otra direccion
    </button>
  </div>
</div>
<style media="screen">
.product-combi{
  border: solid 1px #d2d6de;
  margin: 10px 0px;
  padding: 15px;
}
</style>
