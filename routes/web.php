<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

function connexionDynamicFather()
{
    Config::set("database.connections.pgsql.host", 'localhost');
    Config::set("database.connections.pgsql.database", 'B4nC0');
    Config::set("database.connections.pgsql.username", 'B4nC0');
    Config::set("database.connections.pgsql.password", '');
    Schema::connection('pgsql')->getConnection()->reconnect();
}

Route::get('/probando', function () {
    connexionDynamicFather();
    return view('apiPrueba');
});

Route::get('/', function () {
    connexionDynamicFather();
    return redirect()->route('home');
});

Route::group(['middleware' => ['auth', 'SuperAdmin']], function () {
    connexionDynamicFather();
    Route::resource('despacho', 'DespachoController');
    Route::resource('usuario', 'UsuarioController');
});


Auth::routes();


Route::get('/debePagar', 'HomeController@debePagar')->name('debePagar');

Route::group(['middleware' => ['auth', 'checksinglesession', 'verificarPago']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
});


Route::group(['middleware' => ['auth', 'Gest', 'checksinglesession', 'verificarPago']], function () {
    connexionDynamicFather();
    Route::get('notificacion', 'NotificacionController@index')->name('notificacion');
    Route::put('notificacion_check', 'NotificacionController@check')->name('notificacion_check');
    Route::resource('documentacion', 'DocumentacionController');
    Route::resource('calendario', 'CalendarioController');

    Route::post('subirpdf', 'PDFController@subirpdf')->name('subirpdf');

    Route::get('gestionBuscarCliente', 'GestionController@buscarCliente');


    Route::resource('gestion', 'GestionController');
    Route::get('gestionConvenio/{id}', 'GestionController@gestionConvenio')->name('gestionConvenio');
    Route::resource('gestionPagoIntencion', 'GestionPagoIntencion');

    Route::get('realizarPagoIntencion/{id_cliente}', 'GestionPagoIntencion@realizarPagoIntencion');
    Route::post('guardarPagoIntencion', 'GestionPagoIntencion@guardarPagoIntencion');
    Route::get('cancelarPagoIntencion/{id_intencion}', 'GestionPagoIntencion@cancelarPagoIntencion');

    Route::get('gestionPago/{id}', 'GestionController@gestionPago');
    Route::get('tieneConvenioActivo/{id}', 'GestionController@tieneConvenioActivo');
    Route::post('gestionPago', 'GestionController@gestionPagoGuardar')->name('gestionPago');

    Route::get('agregarDatosCliente/{id_cliente}', 'GestionController@agregarDatosCliente')->name('agregarDatosCliente');
    Route::post('guardarDatosCliente', 'GestionController@guardarDatosCliente')->name('guardarDatosCliente');
    Route::delete('eliminarDireccionCliente', 'GestionController@eliminarDireccionCliente')->name('eliminarDireccionCliente');

    Route::get('agregarDatosAval/{id_cliente}', 'GestionController@agregarDatosAval')->name('agregarDatosAval');
    Route::post('guardarDatosAval', 'GestionController@guardarDatosAval')->name('guardarDatosAval');

    Route::post('agregarTelefono', 'AvalController@agregarTelefono')->name('agregarTelefono');

    Route::put('editarNombre', 'AvalController@editarNombre')->name('editarNombre');
    Route::put('editarDireccion', 'AvalController@editarDireccion')->name('editarDireccion');
    Route::put('editarTelefono', 'AvalController@editarTelefono')->name('editarTelefono');

    Route::resource('trabajo', 'TrabajoController');

    Route::delete('eliminarAval', 'AvalController@eliminarAval')->name('eliminarAval');

    Route::get('cancelarConvenio/{idConvenio}/{idCliente}', 'GestionController@cancelarConvenio');
    Route::resource('telefono', 'TelefonoController');
    Route::resource('gestion-convenio', 'GestionConvenioController');
    Route::get('gestiones/{id_cliente}', 'GestionConvenioController@gestiones')->name('gestiones');
    Route::group(['middleware' => ['Super']], function () {

        Route::resource('estadistica', 'EstadisticaController');

        Route::get('grafica_general', 'EstadisticaController@grafica_general');
        Route::get('grafica_general_pasada/{week}', 'EstadisticaController@grafica_general_pasada');
        Route::get('grafica_general_index', 'EstadisticaController@grafica_general_index');

        Route::post('grafica_encargado', 'EstadisticaController@grafica_encargado');
        Route::get('grafica_encargado_index', 'EstadisticaController@grafica_encargado_index');
        Route::get('grafica_encargado_pasado/{fecha}', 'EstadisticaController@grafica_encargado_pasado');

        Route::get('grafica_gestor_index', 'EstadisticaController@grafica_gestor_index');
        Route::get('grafica_gestor_pasado/{fecha}', 'EstadisticaController@grafica_gestor_pasado');

        Route::get('grafica_gestor_encargado_index', 'EstadisticaController@grafica_gestor_encargado_index');

        Route::get('grafica_todo', 'EstadisticaController@grafica_todo');

        Route::resource('reporte', 'ReporteController');
        Route::post('reporteSclCsv', 'ReporteController@reporteSclCsv')->name('reporteSclCsv');
        Route::post('reporteSclXlsx', 'ReporteController@reporteSclXlsx')->name('reporteSclXlsx');
        Route::get('reporteCyberCsv', 'ReporteController@reporteCyberCsv')->name('reporteCyberCsv');
        Route::get('reporteCyberXlsx', 'ReporteController@reporteCyberXlsx')->name('reporteCyberXlsx');
        Route::resource('reporte_llamada', 'ReporteLlamadaController');
        Route::post('reportepagos', 'ReporteController@reportepagos')->name('reportepagos');
        Route::post('reportePagosRecurrente', 'ReporteController@reportePagosRecurrente')->name('reportePagosRecurrente');
        Route::post('reportePagosNuevos', 'ReporteController@reportePagosNuevos')->name('reportePagosNuevos');

        Route::post('reporteConvenioNuevo', 'ReporteConvenioNuevoController@reporteConvenioNuevo')->name('reporteConvenioNuevo');
        Route::post('reporteConvenioRecurrente', 'ReporteConvenioRecurrenteController@reporteConvenioRecurrente')->name('reporteConvenioRecurrente');

        Route::post('reporteGestionGeneral', 'ReporteGestionGeneral@reporteGestionGeneral')->name('reporteGestionGeneral');

        Route::post('reporteSoloConvenios', 'ReporteConvenioNuevoController@reporteSoloConvenios')->name('reporteSoloConvenios');
        Route::post('reporteSoloPagoIntencion', 'ReporteConvenioNuevoController@reporteSoloPagoIntencion')->name('reporteSoloPagoIntencion');
        Route::post('reporteSoloPagoLiquidaciones', 'ReporteConvenioNuevoController@reporteSoloPagoLiquidaciones')->name('reporteSoloPagoLiquidaciones');
        Route::get('rutaPrueba', 'ReporteConvenioNuevoController@rutaPrueba')->name('rutaPrueba');


        Route::get('obtenerEncargados', 'ReporteController@obtenerEncargados')->name('obtenerEncargados');



        Route::get('obtenerUsuarios', 'ReporteConvenioNuevoController@obtenerUsuarios')->name('obtenerUsuarios');

        Route::get('obtenerIdDespacho', 'ReporteGestionGeneral@obtenerIdDespacho')->name('obtenerIdDespacho');

        Route::group(['middleware' => ['Admin']], function () {
            Route::post('vaciarBase', 'VaciarBaseDatosController@vaciarBase')->name('vaciarBase');

            Route::get('base_datos', 'BaseDatosController@index')->name('base_datos');
            Route::resource('gestionar_usuarios', 'GestionarUsuariosController');








            Route::get('gestiones_masivas', 'GestionesMasivasController@index')->name('gestiones_masivas');
            Route::get('otras_gestiones', 'GestionesMasivasController@otras_gestiones')->name('otras_gestiones');




        });
    });
});
