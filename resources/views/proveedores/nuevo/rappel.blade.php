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
                    <td>Max:</td>
                    <td><input name="max[]" class="form-control" value="{{$requestErr['max'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Min:</td>
                    <td><input name="min[]" class="form-control" value="{{$requestErr['min'][$i]}}"></td>
                  </tr>
                  <tr>
                    <td>Condiciones:</td>
                    <td>
                      <!--<div contenteditable="true" data-inputname="condiciones" class="textarea-transform-init textarea-condiciones" rows="3" cols="20" >-->
                      <textarea name="condiciones[]" rows="8" cols="80">
                        {!! $requestErr['condiciones'][$i] !!}
                      </textarea>
                    </td>
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
                  <td>Max:</td>
                  <td><input name="max[]" class="form-control"></td>
                </tr>
                <tr>
                  <td>Min:</td>
                  <td><input name="min[]" class="form-control"></td>
                </tr>
                <tr>
                  <td>Condiciones:</td>
                  <td>
                    <!--<textarea data-inputname="condiciones[]" class="textarea-transform-init textarea-condiciones-combi" rows="3" cols="80"></textarea>-->
                    <textarea name="condiciones[]" rows="8" cols="80"></textarea>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      @endif
    </div>
  </div>
  <div class="col-xs-12">
    <button type="button" name="addCombi" data-count="1" class="addCombi"> Añadir rappel</button>
  </div>
</div>
</div>
