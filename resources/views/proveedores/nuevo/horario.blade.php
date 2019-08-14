<div class="box-body">
  <div class="col-lg-6 col-xs-12">
    <table class="table">
      <tbody>
        <tr>
          <td>Lunes:</td>
          <td><input name="hor_lunes" class="form-control" value="@if(isset($requestErr)){{$requestErr['hor_lunes']}}@endif"></td>
        </tr>
        <tr>
          <td>Martes:</td>
          <td><input name="hor_martes" class="form-control" value="@if(isset($requestErr)){{$requestErr['hor_martes']}}@endif"></td>
        </tr>
        <tr>
          <td>Miércoles:</td>
          <td><input name="hor_miercoles" class="form-control" value="@if(isset($requestErr)){{$requestErr['hor_miercoles']}}@endif"></td>
        </tr>
        <tr>
          <td>Jueves:</td>
          <td><input name="hor_jueves" class="form-control" value="@if(isset($requestErr)){{$requestErr['hor_jueves']}}@endif"></td>
        </tr>
        <tr>
          <td>Viernes:</td>
          <td><input name="hor_viernes" class="form-control" value="@if(isset($requestErr)){{$requestErr['hor_viernes']}}@endif"></td>
        </tr>
        <tr>
          <td>Sábado:</td>
          <td><input name="hor_sabado" class="form-control" value="@if(isset($requestErr)){{$requestErr['hor_sabado']}}@else Cerrado @endif"></td>
        </tr>
        <tr>
          <td>Domingo:</td>
          <td><input name="hor_domingo" class="form-control" value="@if(isset($requestErr)){{$requestErr['hor_domingo']}}@else Cerrado @endif"></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
