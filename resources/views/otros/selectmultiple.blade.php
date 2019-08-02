<select class="form-control selectpicker" data-live-search="true" data-width="100%" name="selectpickmult_{{$var}}" title="{{$var}}"
  @if ($var != 'origen')
    multiple
  @endif>
  @foreach ($atributos as $atributo)
    <option value="{{$atributo->id}}" title="{{$atributo->nombre}}"
      @if(isset($atributos_seleccionados))
        @if (!is_null($atributos_seleccionados))
          @forelse ($atributos_seleccionados as $atributo_seleccionado)
            @if ($atributo_seleccionado->$var->id == $atributo->id )
              selected
            @endif
          @empty
          @endforelse
        @endif
      @endif

      @if(isset($atributos_seleccionadosID))
        @if(!is_null($atributos_seleccionadosID))
          @forelse ($atributos_seleccionadosID as $atributo_seleccionado)
            @if ($atributo_seleccionado == $atributo->id )
              selected
            @endif
          @empty
          @endforelse
        @endif
      @endif>
      {{$atributo->nombre}}
    </option>
  @endforeach
</select>
<input name="{{$var}}" type="text" style="display:none">
<script>

</script>
