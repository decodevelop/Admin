<div class="box-body">
  <div class="col-xs-12">
    <div id="product-combis" class="col-xs-12">
      @if(isset($requestErr['max']))
        @for ($i=0; $i < count($requestErr['max']); $i++)
          <div class="product-combi col-xs-12" style="border: solid 1px #d2d6de; margin: 10px 0px;padding: 15px;">
            <div class="col-sm-6 mt-5">
              <table class="table">
                <tbody>
                  <tr>
                    <td>Máximo:</td>
                    <td><input name="max[]" class="form-control" type="number" step="any" value="{{$requestErr['max'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Mínimo:</td>
                    <td><input name="min[]" class="form-control" type="number" step="any" value="{{$requestErr['min'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Condiciones:</td>
                    <td><input name="condiciones[]" data-type="text" class="form-control" value="{{$requestErr['condiciones'][$i]}}"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        @endfor
      @else
        <div  class="col-xs-12 product-combi" style="border: solid 1px #d2d6de; margin: 10px 0px;padding: 15px;">
          <div class="col-sm-6 mt-5">
            <table class="table">
              <tbody>
                <tr>
                  <td>Máximo:</td>
                  <td><input name="max[]" class="form-control" type="number" step="any"></td>
                </tr>
                <tr>
                  <td>Mínimo:</td>
                  <td><input name="min[]"class="form-control" type="number" step="any"></td>
                </tr>
                <tr>
                  <td>Condiciones:</td>
                  <td><input name="condiciones[]" data-type="text" class="form-control"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      @endif
    </div>
  </div>
  <div class="col-xs-12">
    <button data-placement="top" data-toggle="tooltip" title="Nuevo Rappel" type="button" id="verButton" class="addCombi btn btn-default">
      <i class="fa fa-plus"></i> Añadir otro rappel
    </button>
  </div>
</div>
</div>
