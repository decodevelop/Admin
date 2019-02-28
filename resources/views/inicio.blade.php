<?php 
use App\Clientes;
use App\Direcciones;
?>
@extends('layouts.backend')

@section('title', 'Inicio')

@section('sidebar')
    @parent
    <p>This is appended to the master sidebar.</p>
@endsection

@section('contenido')
<section class="content">
<?php 
/* $date = new DateTime('2000-01-01');
$cliente = new Clientes(['nombre' => 'test.','dni' => '324323434X', 'nombre_apellidos' => 'torrencio maurelio',
'fecha_nacimiento' => $date, 'email' => 'test@lolc.com', 'telefono' => "6565564"]);
$cliente->save(); */

/* $clientes = Clientes::find(1);
$direccion = new Direcciones(['cp' => '05132','estado' => 'ESP','pais' => 'ESP',
'direccion' => 'calle huertas','clientes_id' => $clientes->id, 'comunidades_id' => 0, 'provincias_id' => 0,
'municipios_id' => 5]); */

/*$cliente = Clientes::find(1);
$direccionCliente = $cliente::find(1)->direcciones;
$attributosDireccionCliente = $direccionCliente->getAttributes();*/
?>

</section>
@endsection