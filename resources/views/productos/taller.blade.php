@extends('layouts.backend')
@section('titulo','Taller > listado')
@section('titulo_h1','Taller')

@section('estilos')
<!-- DataTables -->
<link rel="stylesheet" href="{{url('/plugins/datatables/dataTables.bootstrap.css')}}">
<style>
#dataTables_productos tr:hover {
    border-left: 2px solid #bcb83c;
    border-right: 2px solid #bcb83c;
    background-color: rgba(243,236,18,0.5);
    cursor: pointer;
}
#dataTables_productos .incidencia {
	/*background-color: rgba(255, 0, 0, 0.42);*/
    color: #ff0000;
	transition: all 0.5s;
}

#dataTables_productos .incidencia:hover {
	border-left:2px solid #ff0000;
	border-right:2px solid #ff0000;
	background-color: rgba(255, 0, 0, 0.10) !important;
}
.productos > hr {
	margin: 2px;
    border-color: #f4f4f4;
}
.productos {
	font-size: 12px;
}
.productos > hr:last-child {
	display:none;
}
.subrallado {
    background-color: rgba(243,236,18,0.25);
    cursor: pointer;
}

.ver_producto:nth-child(2n) {
	background-color: #f9fafc;
}
.uppercase{
	text-transform: uppercase;
	width: 170px;
}
#generar_excel_pdf{
	clear: left;
	display: block;
}
.generarBox{
	display: none;
}
#mostrarFormulario{
    width: 105px;
    margin-left: 10px;
    padding: 8px 5px;
    margin-bottom: 10px;
}
.rowBotonesExcel{
    padding-left: 0px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #f4f4f4;
}
#checkAllExcel{
    display: block;
    border-bottom: 1px solid #f4f4f4;
    margin-bottom: 12px;
}
.productoTaller{
    border: 1px solid #f4f4f4;
    background-color: #fff;
    padding-top: 5px;
    padding-bottom: 5px;
    min-height: 480px;
    margin-bottom: 10px;    
}
.titleProductotaller{
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 15px;
}
.imgRProductoTaller{
    width: 150px; 
    height: 150px;
}
.imgProductotaller{
    text-align: center;
    margin-bottom: 15px;
    height: 150px;
}
.descTitle{
    margin-bottom: 8px;
    font-weight: bold;
    border-bottom: 1px solid darkgray;
    padding-bottom: 8px;
}
.descripcionProductotaller{
    min-height: 160px;
    height: 160px;
    overflow: hidden;   
}
.buttonHecho{
    width: 100%;
    margin-top: 15px;
    position: absolute;
    bottom: 0px;
    left: 0;
    height: 60px;
    border-radius: 0px;
}
.btn-success.focus, 
.btn-success:focus{
    background-color: #00a65a;
    border-color: #008d4c;
}
.ticksEstado{
    max-width: 22px;
    position: absolute;
    top: 3px;
    right: 5px;
}
.descP{
    margin-bottom: 0px;
}
.descP span{
    font-weight: bold;
}
.cantidadProductotaller{
    margin-top: 10px;
}
</style>
@endsection

@section('contenido')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box DataTableBox">
                <div class="box-header with-border">
                    <h3 class="box-title">Listado de productos</h3>
                    <div class="box-tools pull-right">
                    <a href="{{url('/nuevoPedidoTaller')}}" type="button" class="btn btn-block btn-default btn-sm"><i class="fa fa-plus"></i>Nuevo Pedido</a>
                    </div>
                    <div>
                            <form id="filtros_datatable" method="get">
                                <table class="table table-bordered">
                                    <thead>
                                        <th style="">
                                            <div style="width: 49%; display:inline-block;">
                                                <label>Fecha inicio</label>
                                                <input class="form-control input-sm filterProducts" type="date" name="fecha_pedido" placeholder="Fecha pedido" value="{{@$_GET['fecha_pedido']}}">
                                            </div>
                                            <div style="width: 49%; display:inline-block;">
                                                <label>Fecha fin</label>
                                                <input class="form-control input-sm filterProducts" type="date" name="fecha_pedido_fin" placeholder="Fecha pedido fin" value="{{@$_GET['fecha_pedido_fin']}}">
                                            </div>
                                        </th>
                                        <th style="">
                                            <label>Estado</label><br>
                                            <select name="estadoPedido">
                                                <option value="1" {{(@$_GET['estadoPedido']=='1') ? 'selected' : ''}}>Hecho</option>
                                                <option value="0" {{(!isset($_GET['estadoPedido']) || @$_GET['estadoPedido']=='0') ? 'selected' : ''}}>Por hacer</option>
                                            </select>
                                        </th>
                                        <th style="width: 168px;">
                                            <button type="submit" class="btn btn-primary btn-sm">FILTRAR</button>
                                            <a href="{{url('/productos/taller')}}" class="btn btn-primary btn-sm">Reinicializar</a>
                                        </th>
                                    </thead>
                                </table>
                            </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @forelse($productosTaller as $keyProductos => $producto)
                @if ($imgActual = '') @endif

                @if(File::exists(public_path('/imgProductos/'.$producto["skuActual"].'.jpg')))
                    @php ($imgActual = $producto["skuActual"].'.jpg') 
                @elseif(File::exists(public_path('/imgProductos/'.$producto["skuActual"].'.jpeg')))
                    @php ($imgActual = $producto["skuActual"].'.jpeg') 
                @elseif(File::exists(public_path('/imgProductos/'.$producto["skuActual"].'.png')))
                    @php ($imgActual = $producto["skuActual"].'.png') 
                @endif
                <div class="col-md-3 col-xs-12 productoTaller prodTaller-{{$producto['id_producto_taller']}}">
                    <input type="hidden" name="idPedidoTaller" class="idPedidoTaller" value="{{$producto['id_producto_taller']}}">
                    <div class="titleProductotaller">{{$producto["nombre"]}}</div>
                    <div class="imgProductotaller">
                        @if($imgActual != '')
                            <img src="{{ asset('/imgProductos/'.$imgActual) }}" class="imgRProductoTaller"/>
                        @endif
                    </div>
                    <div class="descripcionProductotaller">
                        <div class="refTitle"><b>REF:</b> {{$producto["skuActual"]}}</div>
                        <div class="descTitle">Descripción: </div>
                        <p class="descP"><span>Material:</span> {{$producto["material"]}}</p>
                        <p class="descP"><span>Acabado:</span> {{$producto["acabado"]}}</p>
                        <p class="descP"><span>Largo:</span> {{$producto["largo"]}}</p>
                        <p class="descP"><span>Ancho:</span> {{$producto["ancho"]}}</p>
                        <p class="descP"><span>Alto:</span> {{$producto["alto"]}}</p>                        
                    </div>
                    <div class="cantidadProductotaller">CANTIDAD: {{$producto["cantidad"]}}</div>
                    <div class="botonHechoProductotaller"><button class="btn btn-success buttonHecho {{($producto['estado'] != 1) ? 'buttonHechoClick' : ''}}">MARCAR HECHO</button></div>
                    <div class="estadoPedido">
                        @if($producto["estado"]==1)
                            <img src="{{ asset('/img/tickVerde.png') }}" class="ticksEstado"/>
                        @else
                            <img src="{{ asset('/img/tickRojo.png') }}" class="ticksEstado"/>
                        @endif
                    </div>
                </div>
            @empty
                <p>No hay datos.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection

@section('scripts')
<!-- DataTables -->

<script src="{{url('/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{url('/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script>
    $(document).ready(function(){
    
        /* Al checkear el input global, marcamos todos y desmarcamos al uncheck. */
        $("[name='check_all']").click(function(){
             if($(this).is(":checked")) {
                //var checkarray = $("[name='pedido']:checked").serializeArray();
                $("[name='producto']").each(function(){
                    if(!$(this).is(":checked")) {
                        $(".num-"+$(this).val()).addClass("subrallado");
                        $(this).click();
                    }
                });
                //$("[name='pedido']").click();
            } else {
                $("[name='producto']").each(function(){
                    if($(this).is(":checked")){
                        $(".num-"+$(this).val()).removeClass("subrallado");
                        $(this).click();
                    }
                });
            }
        });
        
        /* Al seleccionar cada pedido de forma independiente, marcamos y añadimos o eliminamos clase subrallado, */
        $("[name='producto']").click(function(){
            if($(this).is(":checked")) {
                $(".num-"+$(this).val()).addClass("subrallado");
            } else {
                $(".num-"+$(this).val()).removeClass("subrallado");
            }
        });
        
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

        /* Marcar como hecho un pedido */
        $('.buttonHechoClick').click(function(){
            var_idPedidoTaller = $(this).parent().parent().find('.idPedidoTaller').val();
            var_clase_Taller = $(this).parent().parent().attr("class").split(' ');
            var_clase_Taller = var_clase_Taller[var_clase_Taller.length-1];

            $.ajax({
                url: "/productos/actEstadoTaller/"+var_idPedidoTaller+"/"+var_clase_Taller,
                method: "POST",
            data: { "_token": "{{ csrf_token() }}", id: var_idPedidoTaller}
            }).done(function(mensaje){
                console.log(mensaje.resultado);
                if(mensaje.resultado==1){
                    $("."+mensaje.clase_taller+" .ticksEstado").attr("src","/img/tickVerde.png");
                    apprise("Pedido hecho");
                }
                else{
                    $("."+mensaje.clase_taller+" .ticksEstado").attr("src","/img/tickRojo.png");
                    apprise("Pedido no hecho");
                }
            });
        });
    });
</script>
@endsection