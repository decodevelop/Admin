<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();
Route::get('/', 'HomeController@index');
Route::get('barcode', 'HomeController@barcode');

Route::get('/administracion/clientes', 'HomeController@administrar_clientes');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/enviar-correo-prueba', 'EnviarcorreoController@enviar');

Route::get('/plantilla-correo', function(){

		return view('mail.informar_envio_plantilla');


});

// Rutas para usuarios autentificados
//# hay que incorporar los niveles mediante middlewares.
Route::group(['middleware' => 'auth'], function() {
	Route::get('/administracion', 'HomeController@administracion');
	Route::get('/', 'HomeController@index');


		/* APARTADO CATALOGOS - (No acabado) */
		Route::get('/catalogos', 'CatalogosController@index'); // Inicio catalogos

		/* USER ACCOUNT MANAGEMENT - (No acabado) */
		Route::get('/mis-datos', 'UserconfigController@index'); // Inicio configs perfil

		/* APARTADO AMAZON */
		Route::get('/amazon', 'AmazonController@index'); // Inicio Amazon
		Route::post('/amazon/imprimirEtiquetas', 'AmazonController@imprimirEtiquetas'); // Imprimir Etiquetas Amazon
		Route::post('/amazon/guardarCarrito' , 'AmazonController@guardarCarrito'); // Guardar producto en carrito de compra
		Route::get('/amazon/eliminarIndividual/{codigo_ean}' , 'AmazonController@eliminarIndividual'); // Eliminar producto del carrito
		Route::get('/amazon/eliminarCarrito' , 'AmazonController@eliminarCarrito'); // Eliminar todo el carrito
		Route::get('/amazon/imprimirCarritoCompra' , 'AmazonController@imprimirCarritoCompra'); // Imprimir el carrito entero
		Route::get('/amazon/importar_csv_amazon' , 'AmazonController@importar_csv_amazon'); // Formulario de subida ficheros csv
		Route::post('/amazon/importar_csv_amazon_subida', 'AmazonController@importar_csv_amazon_subida'); //Subida del csv seleccionado.
		//Route::get('/amazon/subidaAmazon' , 'AmazonController@subidaAmazon'); // Subida productos amazon desde carpeta public (subida manual antigua)
		Route::get('/amazon/descargar' , 'AmazonController@descargar_excel');
		Route::get('/amazon/subirExcel', 'AmazonController@subir_excel_view'); // subida excel
		Route::post('/amazon/subirExcel', 'AmazonController@subir_excel');
		Route::get('/amazon/pantilla', 'AmazonController@descargar_plantilla' );
		Route::post('/amazon/modificarProducto', 'AmazonController@modificar_producto' );
		Route::get('/amazon/modificarProducto', 'AmazonController@modificar_producto' );
		Route::get('/amazon/borrarProducto/{ean}', 'AmazonController@borrar_producto' );

		Route::get('/amazon/importar_csv_amazon_new' , 'AmazonController@importar_csv_amazon_new'); // Formulario de subida ficheros csv
		Route::post('/amazon/gen_albaran_amazon', 'AmazonController@gpdf_albaran'); //Subida del csv seleccionado.
		Route::post('/amazon/importar_csv_amazon_subida_new', 'AmazonController@importar_csv_amazon_subida_new'); //Subida del csv seleccionado.
		Route::get('/amazon/importar_actualizacion', 'AmazonController@subir_excel_view'); //Subida del csv seleccionado.
		Route::post('/amazon/importar_actualizacion', 'AmazonController@subir_actualizacion'); //Subida del csv seleccionado.


		Route::get('/amazon/barcode' , function(){

				return view('amazon.etiquetasPersonalizadas');

		});
		/* APARTADO CATALOGO */
		Route::get('/catalogo/subirExcelProductos', 'CatalogoController@formularioSubidaProductos'); // Formulario subir productos
		Route::post('/catalogo/subirExcelProductos', 'CatalogoController@subirExcelProductos'); // Subir los productos a la base de datos

		/* APARTADO PRODUCTOS */
		Route::get('/productos', 'ProductosController@index'); // Inicio Productos
		Route::get('/productos/detalle/{id}', 'ProductosController@detalle'); // vista detalle producto
		Route::get('/productos/modificar/{id}', 'ProductosController@modificar'); // vista modificar detalle producto
		Route::get('/productos/eliminarPrincipal/{id}/{img}', 'ProductosController@eliminarPrincipal'); // vista eliminar fotoprincipal detalle producto
		Route::get('/productos/eliminarSecundaria/{id}/{skuActual}/{img}', 'ProductosController@eliminarSecundaria'); // vista eliminar fotosecundaria detalle producto
		Route::post('/productos/actualizar_detalle/', 'ProductosController@actualizar_detalle'); // vista actualizar datos del detalle producto
		Route::post('/productos/subirFoto/{id}/{skuActual}/{tipo}', 'ProductosController@subirFoto'); // vista subir foto detalle producto
		Route::post('/productos/gen_excel', 'ProductosController@gexcel_productos'); // Generamos excel
		Route::get('/productos/taller', 'ProductosController@taller'); // Pedidos del taller
		Route::post('/productos/actEstadoTaller/{id_pedido_taller}/{clase_Taller}', 'ProductosController@actEstadoTaller'); // Actualizar el estado del pedido taller a hecho
		Route::get('/productos/formularioSubidaProductos', 'ProductosController@formularioSubidaProductos'); // Formulario subir productos
		Route::post('/productos/subirExcelProductos', 'ProductosController@subirExcelProductos'); // Subir los productos a la base de datos

		Route::get('/productos/stock_web', 'ProductosController@verStock' );
		Route::post('/productos/stock_web', 'ProductosController@subirCambioStock');

		Route::get('/productos/ver_stock_web', 'ProductosController@cambiosStock');
		Route::get('/productos/guardar_stock', 'ProductosController@guardarStock');

		Route::get('/productos/generar_stock_web', 'ProductosController@generarCsvStock');
		//enviarComanda
		Route::get('/productos/enviarComanda', 'ProductosController@enviarComanda');
		///productos/stock_recibido
		Route::get('/productos/stock_recibido', 'ProductosController@comandaRecibida');
		/* WEBS */
		Route::get('/webs', 'WebsController@index');

		/*Prestashop APIs*/
		Route::get('/prestashop/api/actualizar_pedidos', 'PsController@actualizar_pedidos');

		Route::get('/estadisticas/inicio', 'EstadisticasNewController@estadisticas');
		Route::post('/estadisticas/inicio', 'EstadisticasNewController@filtrarEstadisticas');
		Route::get('/estadisticas/pedidos', 'EstadisticasNewController@pedidos');
		Route::post('/estadisticas/pedidos', 'EstadisticasNewController@pedidos');
		Route::get('/estadisticas/incidencias', 'EstadisticasNewController@incidencias');
		Route::post('/estadisticas/incidencias', 'EstadisticasNewController@incidencias');

		Route::get('/campanas', 'CampController@inicio');
		Route::get('/campanas/crear', 'CampController@crear');
		Route::get('/campanas/productos/{id_campana}', 'CampController@viewProductos');
		Route::get('/campanas/palets/{id_campana}', 'CampController@viewPalets');

		Route::get('/campanas/subirExcelVp', 'CampController@subirProductosVP'); // subida excel
		Route::post('/campanas/subirExcelVp', 'CampController@subirProductosVP_post');

		Route::get('/campanas/subirVentasVP/{id_campana}', 'CampController@subirVentasVP'); // subida excel
		Route::post('/campanas/subirVentasVP/{id_campana}', 'CampController@subirVentasVP_post');

		Route::post('/campanas/guardarCarrito' , 'CampController@guardarCarrito'); // Guardar producto en carrito de compra
		Route::get('/campanas/eliminarIndividual/{id_campana}/{ean}' , 'CampController@eliminarIndividual'); // Eliminar producto del carrito
		Route::get('/campanas/eliminarCarrito' , 'CampController@eliminarCarrito'); // Eliminar todo el carrito

		Route::get('/campanas/guardarPalet/{id_campana}' , 'CampController@guardarPalet');

		Route::get('/campanas/palets/etiquetas/{id_palet}' , 'CampController@generarEtiquetas');
		Route::get('/campanas/palets/albaran/{id_palet}' , 'CampController@generarAlbaran');

		Route::get('/campanas/palets/eliminar/{id_palet}' , 'CampController@eliminarPalet');
		Route::get('/campanas/palets/modificar/{id_palet}' , 'CampController@modificarPaletView');

		Route::get('/campanas/palets/excel/{id_campana}' , 'CampController@excelPalets');


		Route::post('/campanas/palets/modificar/{id_palet}' , 'CampController@modificarPalet');

		Route::post('/campanas/palets/cargarProductosPalets' , 'CampController@cargarProductosPalets');
		Route::post('/campanas/palets/cargarProductosCampana' , 'CampController@cargarProductosCampana');

		Route::post('/campanas/palets/addPaletModificado' , 'CampController@addPaletModificado');


		Route::post('/campanas/palets/cambioPalet' , 'CampController@cambioPalet');


		Route::get('/campanas/etiquetas/{id_campana}' , 'CampController@generarEtiquetasCampana');

		Route::post('/campanas/palets/eliminarProductoPalet' , 'CampController@eliminarProductoPalet');

		Route::get('/pruebacron', 'PedidosNewController@pruebacron'); // Inicio Productos

		/* new pedidos */
		Route::get('/pedidos/adaptar', 'PedidosNewController@adaptar');
		Route::get('/pedidos/adaptar_productos', 'PedidosNewController@adaptar_productos');
		Route::get('/pedidos/adaptar_incidencias', 'PedidosNewController@adaptar_incidencias');
		Route::get('/pedidos', 'PedidosNewController@index');

		Route::get('/pedidos/importar_csv', 'PedidosNewController@importar_csv');
		Route::post('/pedidos/importar_csv', 'PedidosNewController@importar_csv_post');
		Route::get('/pedidos/detalle/{id}', 'PedidosNewController@detalle'); // vista detalle producto
		Route::post('/pedidos/detalle/{id}', 'PedidosNewController@crear_observacion'); // vista detalle producto

		Route::get('/pedidos/modificar/{id}', 'PedidosNewController@modificar');
		Route::post('/pedidos/modificar/{id}', 'PedidosNewController@actualizar');

		Route::get('/pedidos/duplicar/{id}', 'PedidosNewController@duplicar');
		Route::post('/pedidos/duplicar/{id}', 'PedidosNewController@guardar_duplicado');
		//-----------------------
		Route::get('/pedidos/eliminar/{id}', 'PedidosNewController@eliminar');
		Route::post('/pedidos/eliminar/{id}', 'PedidosNewController@eliminar'); //

		Route::get('/pedidos/enviar_pedido/{id}', 'PedidosNewController@enviar_pedido'); //aceptar envío pedido

		// Añadir pedidos
		Route::get('/pedidos/nuevo', 'PedidosNewController@nuevo'); // formulario añadir nuevo
		Route::post('/pedidos/nuevo', 'PedidosNewController@guardar'); // guardar + redirección con id a detalle.

		// Rutas extras para actualizar, modificar pequeños detalles de los pedidos/bultos.
		Route::post('/pedidos/crear_observacion_bultos/{id}', 'PedidosNewController@crear_observacion_bultos');
		Route::post('/pedidos/seguimiento_pedido/{id}', 'PedidosNewController@seguimiento_pedido'); //Actualizar observacion con un texto por defecto de bultos.

		Route::get('/pedidos/producto/modificar/','PedidosNewController@modificar_producto');
		Route::get('/pedidos/proveedor/alta/','PedidosNewController@alta_proveedor');
		Route::get('/pedidos/transportista/alta/','PedidosNewController@alta_transportista');
		Route::get('/pedidos/origen/alta/','PedidosNewController@alta_origen');


		// Rutas para generar PDFs pedidos (multiples/unicos/bultos)
		Route::get('/pedidos/albaran/{idm}', 'PedidosNewController@gpdf_albaran'); // Generamos albaran PDF único/bultos
		Route::post('/pedidos/albaran/{idm}', 'PedidosNewController@gpdf_albaran'); // Generamos albaran PDF único/bultos
		Route::post('/pedidos/albaranes', 'PedidosNewController@gpdf_albaranes'); // Generamos albaran PDF Múltiple

		Route::get('/pedidos/transportista/{nombre_transportista}', 'PedidosNewController@pedidos_transportista'); // Generamos albaran PDF único/bultos

		Route::get('/pedidos/transportista/mrw/csv/{id}/{generar_csv}', 'PedidosNewController@csv_mrw'); // vista detalle pedido
		Route::post('/pedidos/transportista/mrw/csv/{id}', 'PedidosNewController@csv_mrw_post');

		Route::get('/pedidos/transportista/tipsa/csv/{id}/{generar_csv}', 'PedidosNewController@csv_tipsa'); // vista detalle pedido
		Route::post('/pedidos/transportista/tipsa/csv/{id}', 'PedidosNewController@csv_tipsa_post');

		Route::get('/pedidos/proveedor/{nombre_proveedor}','PedidosNewController@view_proveedor');
		//Route::get('/pedidos/aviso_dups','PedidosNewController@aviso_dups');

		Route::get('/pedidos/aviso_proveedor/{nombre_proveedor}','PedidosNewController@aviso_proveedor');

		Route::get('/pedidos/no_enviados','PedidosNewController@no_enviados');

		Route::get('/pedidos/aviso_retraso','PedidosNewController@aviso_retraso');

		Route::post('/pedidos/gen_excel','PedidosNewController@gexcel_pedidos');

		Route::get('/incidencias','IncidenciasNewController@index');

		/* Actualizar incidencia  */
		Route::post('/incidencias/actualizar/{id}', 'IncidenciasNewController@actualizar_incidencia' );
		Route::get('/incidencias/detalle/{id}', 'IncidenciasNewController@detalle' );
		Route::get('/incidencias/nueva/{id}', 'IncidenciasNewController@nueva' );

		Route::post('/incidencias/nueva/{id}', 'IncidenciasNewController@guardar' );

		Route::get('/incidencias/reposicion/{id}', 'PedidosNewController@duplicar');
		Route::post('/incidencias/reposicion/{id}', 'PedidosNewController@guardar_reposicion ');
		/* development tools */
		Route::get('/development', 'ToolsController@index');
		Route::get('/development/calculadora', function(){

				return view('development.calculadora');

		});
		Route::get('/development/pruebas', 'ToolsController@pruebas');
		Route::post('/development', 'ToolsController@generarConsultaEan');

		Route::post('/development/categorias_sql', 'ToolsController@generarConsultaCategoriasSql');
		Route::post('/development/precios_sql', 'ToolsController@generarConsultaPreciosSql');
		Route::post('/development/fabricante_sql', 'ToolsController@generarConsultaManufacturerSql');
		Route::get('/development/clientes', 'ToolsController@exportarClientesWebs');


		Route::get('/development/instagram' , function(){

				return view('development.instagram');

		});
		Route::get('/development/probas' , function(){

				return view('webs.prueba');

		});

		Route::get('/development/mails', 'PedidosController@excel_mails');


		route::get('/consola/', function(){
			// --- Renombrar una tabla ---
			//Schema::rename('talleres', 'taller');

			// --- Crear archivo de migración
			//Artisan::call('make:migration', ['name' => 'alter_taller_table', '--table' => 'taller']);

			// --- Ejecutar archivo migración

			// --- crear modelo (permiso denegado)
			//Artisan::call('make:model', ['name' => 'Proveedores']);


			// https://styde.net/modificando-migraciones-en-laravel/
		});

});
