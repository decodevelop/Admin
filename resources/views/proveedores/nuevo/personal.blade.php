<div class="box-body">
  <div class="col-xs-12">
    <div id="personal" class="col-xs-12">
      <div class="product-combi col-xs-12">
        <div class="col-sm-6 mt-5">
          <table class="table">
            <tbody>
              <tr>
                <td>Cargo:</td>
                <td><input name="pers_cargo[]" type="text" class="form-control"  value="Contabilidad" readonly></td>
              </tr>
              <tr>
                <td>Nombre:</td>
                <td><input name="pers_nombre[]" type="text" class="form-control"  value="@if(isset($requestErr['pers_nombre'][0])){{$requestErr['pers_nombre'][0]}}@endif"></td>
              </tr>
              <tr>
                <td>Correo:</td>
                <td><input name="pers_correo[]" data-type="text" class="form-control" value="@if(isset($requestErr['pers_correo'][0])){{$requestErr['pers_correo'][0]}}@endif"></td>
              </tr>
              <tr>
                <td>Teléfono:</td>
                <td><input name="pers_telefono[]" data-type="number" class="form-control" value="@if(isset($requestErr['pers_telefono'][0])){{$requestErr['pers_telefono'][0]}}@endif"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="product-combi col-xs-12">
        <div class="col-sm-6 mt-5">
          <table class="table">
            <tbody>
              <tr>
                <td>Cargo:</td>
                <td><input name="pers_cargo[]" type="text" class="form-control"  value="Comercial" readonly></td>
              </tr>
              <tr>
                <td>Nombre:</td>
                <td><input name="pers_nombre[]" type="text" class="form-control"  value="@if(isset($requestErr['pers_nombre'][1])){{$requestErr['pers_nombre'][1]}}@endif"></td>
              </tr>
              <tr>
                <td>Correo:</td>
                <td><input name="pers_correo[]" data-type="text" class="form-control" value="@if(isset($requestErr['pers_correo'][1])){{$requestErr['pers_correo'][1]}}@endif"></td>
              </tr>
              <tr>
                <td>Teléfono:</td>
                <td><input name="pers_telefono[]" data-type="number" class="form-control" value="@if(isset($requestErr['pers_telefono'][1])){{$requestErr['pers_telefono'][1]}}@endif"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="product-combi col-xs-12">
        <div class="col-sm-6 mt-5">
          <table class="table">
            <tbody>
              <tr>
                <td>Cargo:</td>
                <td><input name="pers_cargo[]" type="text" class="form-control"  value="Pedidos" readonly></td>
              </tr>
              <tr>
                <td>Nombre:</td>
                <td><input name="pers_nombre[]" type="text" class="form-control"  value="@if(isset($requestErr['pers_nombre'][2])){{$requestErr['pers_nombre'][2]}}@endif"></td>
              </tr>
              <tr>
                <td>Correo:</td>
                <td><input name="pers_correo[]" data-type="text" class="form-control" value="@if(isset($requestErr['pers_correo'][2])){{$requestErr['pers_correo'][2]}}@endif"></td>
              </tr>
              <tr>
                <td>Teléfono:</td>
                <td><input name="pers_telefono[]" data-type="number" class="form-control" value="@if(isset($requestErr['pers_telefono'][2])){{$requestErr['pers_telefono'][2]}}@endif"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      @if(isset($requestErr['pers_cargo'][3]))
        @for ($i=3; $i < count($requestErr['pers_cargo']); $i++)
          <div class="product-combi col-xs-12">
            <div class="col-sm-6 mt-5">
              <table class="table">
                <tbody>
                  <tr>
                    <td>Cargo:</td>
                    <td><input name="pers_cargo[]" type="text" class="form-control"  value="{{$requestErr['pers_cargo'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Nombre:</td>
                    <td><input name="pers_nombre[]" type="text" class="form-control"  value="{{$requestErr['pers_nombre'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Correo:</td>
                    <td><input name="pers_correo[]" data-type="text" class="form-control" value="{{$requestErr['pers_correo'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Teléfono:</td>
                    <td><input name="pers_telefono[]" data-type="number" class="form-control" value="{{$requestErr['pers_telefono'][$i]}}"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        @endfor
      @else

      @endif
    </div>
  </div>
  <div class="col-xs-12">
    <button data-placement="top" data-toggle="tooltip" title="Nuevo personal" type="button" id="añadirPersonalButton" class="addPers btn btn-default">
      <i class="fa fa-plus"></i> Añadir otro personal
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
