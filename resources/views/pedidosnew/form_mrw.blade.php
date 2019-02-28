<div class="modal fade" id="modal-mrw" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="form-mrw" action="" method="post">
        <div class="modal-header">
          <div class="col-md-4">
            <img src="/img/pedidos/mrw.png" alt="mrw">
          </div>
          <div class="col-md-5">
            <div class="servicio-mrw" id="servicio-mrw">

            </div>
          </div>
          <div class="col-md-3">
            <div class="referencia-mrw" id="referencia-mrw">

            </div>

          </div>
        </div>
        <div class="modal-body">
            <div class="col-md-6">

              <input type="hidden" name="_token" class="form-control" value="{{ csrf_token() }}">
              Nombre>
              <input type="text" name="nombre-mrw" id="nombre-mrw" class="form-control" value="">
              Dirección>
              <input type="text" name="direccion-mrw" id="direccion-mrw" class="form-control" value="">
              Ciudad>
              <input type="text" name="ciudad-mrw" id="ciudad-mrw" class="form-control" value="">
              CP>
              <input type="text" name="cp-mrw" id="cp-mrw" class="form-control" value="">

              Teléfono>
              <input type="text" name="telefono-mrw" id="telefono-mrw" class="form-control" value="">

            </div>
            <div class="col-md-6">
              Codigo Pais>
              <input type="text" name="pais-mrw" id="pais-mrw" class="form-control" value="">
              Bulto>
              <input type="text" name="bultos-mrw" id="bultos-mrw" class="form-control" value="">
              Peso(kg)>
              <input type="text" name="kg-mrw" id="kg-mrw" class="form-control" value="">
              Fecha recogida>
              <input type="text" name="fecha-mrw" id="fecha-mrw" class="form-control" value="">
            </div>

        </div>
        <div class="modal-footer">
          <div class="col-md-12">
            <button type="submit" name="button" class="btn btn-primary btn-sm">Generar</button>
          </div>
        </div>
      </form>
    </div>

  </div>
</div>
