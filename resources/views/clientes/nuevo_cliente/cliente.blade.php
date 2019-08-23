<div class="box-body">
  <div class="col-xs-12">
    <div class="col-lg-6" style="padding-left: 0!important">
      <table class="table">
        <tbody>
          <tr>
            <td style="width: 20%;">DNI:</td>
            <td><input name="dni" class="form-control" value="@if(isset($requestErr)){{$requestErr['dni']}}@endif"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="col-lg-6 col-xs-12">
    <table class="table">
      <tbody>
        <tr>
          <td style="border-top: 0px; width: 20%;"></td>
          <td style="border-top: 0px;"><strong>Datos de Facturación</strong></td>
        </tr>
        <tr>
          <td>Nombre y Apellidos:</td>
          <td><input name="nombre_facturacion" class="form-control" value="@if(isset($requestErr)){{$requestErr['nombre_facturacion']}}@endif"></td>
        </tr>
        <tr>
          <td>E-Mail:</td>
          <td><input name="email_facturacion" class="form-control" value="@if(isset($requestErr)){{$requestErr['email_facturacion']}}@endif"></td>
        </tr>
        <tr>
          <td>Teléfono:</td>
          <td><input name="telefono_facturacion" class="form-control" value="@if(isset($requestErr)){{$requestErr['telefono_facturacion']}}@endif"></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="col-lg-6 col-xs-12">
    <table class="table">
      <tbody>
        <tr>
          <td style="border-top: 0px; width: 20%;"></td>
          <td style="border-top: 0px;"><strong>Datos de Envío</strong></td>
        </tr>
        <tr>
          <td>Nombre y Apellidos:</td>
          <td><input name="nombre_envio" class="form-control" value="@if(isset($requestErr)){{$requestErr['nombre_envio']}}@endif"></td>
        </tr>
        <tr>
          <td>E-Mail:</td>
          <td><input name="email_envio" class="form-control" value="@if(isset($requestErr)){{$requestErr['email_envio']}}@endif"></td>
        </tr>
        <tr>
          <td>Teléfono:</td>
          <td><input name="telefono_envio" class="form-control" value="@if(isset($requestErr)){{$requestErr['telefono_envio']}}@endif"></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<style media="screen">
  .richText .richText-editor {
    height: 275px;
  }
</style>
