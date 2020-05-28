<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Gest;
use App\Intencion;
use App\PDF;
use App\Relacion_Cliente_Aval;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Gestion;
use App\Cliente;
use App\Convenio;
use App\Pago;
use App\CalendarioPagos;
use App\Aval;
use App\Direccion;
use App\Trabajo;
use App\Tipo_Gestion;
use App\Telefono_Cliente;
use App\Tipo_Gestion_ssl;
use App\Gestionado;
use App\BaseDinamica;
use Illuminate\Support\Facades\Schema;
use mysql_xdevapi\Exception;

class GestionController extends Controller
{

    public function index()
    {
        BaseDinamica::connexionDynamicSon();
        return Gestion::viewAndData('Gestion.index');
    }

    public function create()
    {
        BaseDinamica::connexionDynamicSon();
        return Gestion::viewAndData('Gestion.create');
    }

    public function show($id)
    {
        BaseDinamica::connexionDynamicSon();

        return Gestion::viewAndData('Gestion.show', $data = $this::getDataSpe($id));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            try {
                $id_usuario = auth()->id();
                $username = auth()->user()->username;
                BaseDinamica::connexionDynamicSon();
                $gestion = new Gestion();
                $gestion->folioGen = 0;
                $gestion->username = $username;
                $gestion->id_usuario = $id_usuario;
                $gestion->id_cliente = $request->input('id_cliente');
                $gestion->tit_aval = $request->input('tit_aval');
                $gestion->id_tipo_gestion = ($request->input('id_tipo_gestion')) + 1;
                $gestion->id_tipo_gestion_ssl = ($request->input('id_tipo_gestion_ssl')) + 1;
                $gestion->id_gestionado = ($request->input('id_gestionado')) + 1;
                $auxFecha = $request->input('fechaContactar');
                $auxHora = $request->input('horaContactar');
                $fechaHora = $auxFecha . ' ' . $auxHora;
                $gestion->fecha_hora_contactar = $fechaHora;
                $gestion->comentario = $request->input('comentario');
                $gestion->save();

                if ($request->input('convenio') == true) {
                    Convenio::validarDatosConvenio();
                    $convenio = new Convenio();
                    $convenio->id_gestion = Gestion::obtenerUltimaGestion($id_usuario);
                    $convenio->id_cliente = $request->input('id_cliente');
                    $convenio->convenio_estado = $request->input('convenio');
                    $convenio->primer_pago_cantidad = $request->input('pagoInicial');
                    $convenio->primer_pago_estado = 0;
                    $auxFecha = $request->input('fechaInicial');
                    $auxHora = $request->input('horaContactar');
                    $fechaHora = $auxFecha . ' ' . $auxHora;
                    $convenio->primer_pago_fecha = $fechaHora;
                    $convenio->deuda_total = $request->input('deudaTotal');
                    $convenio->deuda_total_original = $request->input('deudaTotal');
                    $convenio->numero_pagos = $request->input('opcionPago');
                    $convenio->save();
                }
                return 'entro';
            } catch (\Exception $exception) {
                return $exception;
            }
        }

    }


    public function gestionConvenio(Request $request, $id)
    {

        if ($request->ajax()) {
            BaseDinamica::connexionDynamicSon();
            $clienteAux = Cliente::obtenerClientes($id);
            $pagoAux = Pago::obtenerPago($id);
            $tipoGestionAux = Tipo_Gestion::obtenerTipoGestion();
            $cliente = $clienteAux[0];
            $pago = $pagoAux[0];

            $extra = [];
            for ($i = 0; $i < count($tipoGestionAux); $i++) {
                $extra[$i] = $tipoGestionAux[$i]->nombre_gestion . '-' . $tipoGestionAux[$i]->descripcion_gestion;
                $tipoGestion[$i] = array('text' => $extra[$i], 'value' => $i);
            }

            $extra = [];
            $tipoGestionSslAux = Tipo_Gestion_ssl::obtenerTipoGestionSsl();
            for ($i = 0; $i < count($tipoGestionSslAux); $i++) {
                $extra[$i] = $i + 1 . '-' . $tipoGestionSslAux[$i]->descripcion_ssl;
                $tipoGestionSsl[$i] = array('text' => $extra[$i], 'value' => $i);
            }

            $extra = [];
            $gestionadoAux = Gestionado::obtenerTipoGestion();
            for ($i = 0; $i < count($gestionadoAux); $i++) {
                $extra[$i] = $gestionadoAux[$i]->nombre;
                $gestionado[$i] = array('text' => $extra[$i], 'value' => $i);
            }

            $extra = [];
            for ($i = 0; $i < 53; $i++) {
                if ($i == 0) {
                    $extra[$i] = "Un solo pago (Liquidación)";
                } else if ($i == 1) {
                    $extra[$i] = $i . ' ' . "semana";
                } else {
                    $extra[$i] = $i . ' ' . "semanas";
                }
                $itemOpcionPago[$i] = array('text' => $extra[$i], 'value' => $i);
            }

            $data = array(
                'cliente' => $cliente,
                'pago' => $pago,
                'tipoGestion' => $tipoGestion,
                'tipoGestionSsl' => $tipoGestionSsl,
                'gestionado' => $gestionado,
                'itemOpcionPago' => $itemOpcionPago
            );
            return response()->json($data, 200);
        }
        return Gestion::viewAndData('Gestion.crearGestionConvenioNota', $data = $this::getDataSpe($id));
    }

    public function agregarDatosAval(Request $request, $id_cliente)
    {
        if ($request->ajax()) {
            BaseDinamica::connexionDynamicSon();
            return response()->json(Aval::obtenerAvalesUnCliente($id_cliente));
        } else {
            return Gestion::viewAndData('Gestion.agregarDatosAval', $id_cliente);
        }
    }

    public function guardarDatosAval(Request $request)
    {
        BaseDinamica::connexionDynamicSon();//son => children :v y ta mal la gramatica v:

        if ($request->agregarDir) {

            $id_aval = Aval::existeDireccion($request);
            if (!is_null($id_aval)) {
                if ($id_aval == 'ya existe') {
                    return 'El cliente ya tiene este aval';
                } else {

                    if (!empty(Relacion_Cliente_Aval::tieneAval($request->id_cliente, $id_aval))) {
                        return 'El cliente ya tiene este aval';
                    } else {
                        try {
                            $r_c_a = new Relacion_Cliente_Aval();
                            $r_c_a->id_aval = $id_aval;
                            $r_c_a->id_cliente = $request->id_cliente;
                            $r_c_a->save();
                            return 'Se agrego (Otro cliente tambien lo tiene), ve a detalles para ver quien';
                        } catch (\Exception $exception) {
                            return "Exp 1: " . $exception;
                        }
                    }

                }
            } else {
                try {


                    /*   DB::insert('insert into "Direccion" (cuadrante, zona_geo,
                            direccion,num_ext,num_int,tipo_direccion,cp,colonia,poblacion,estado,id_cliente)
                            values (?, ?,?,?,?,?,?,?,?,?,?)',
                           [
                               $request->cuadrante,
                               $request->zona_geo,
                               $request->direccion,
                               $request->num_ext,
                               $request->num_int,
                               'aval',
                               $request->cp,
                               $request->colonia,
                               $request->poblacion,
                               $request->estado,
                               $request->id_cliente
                           ]);*/


                    $id_direccion = DB::table('Direccion')->insertGetId(array(
                        'cuadrante' => $request->cuadrante,
                        'zona_geo' => $request->zona_geo,
                        'direccion' => $request->direccion,
                        'num_ext' => $request->num_ext,
                        'num_int' => $request->num_int,
                        'tipo_direccion' => 'aval',
                        'cp' => $request->cp,
                        'colonia' => $request->colonia,
                        'poblacion' => $request->poblacion,
                        'estado' => $request->estado,
                        'id_cliente' => $request->id_cliente,
                    ), 'id_direccion');

                    $id_aval = DB::table('Aval')->insertGetId(array(
                        'id_direccion' => $id_direccion,
                        'nombre_aval' => $request->nombre_aval
                    ), 'id_aval');

                    DB::table('Relacion_Cliente_Aval')->insert(array(
                        'id_cliente' => $request->id_cliente,
                        'id_aval' => $id_aval
                    ));

                    if ($request->agregarTel) {
                        DB::table('Telefono_Aval')->insert(array(
                            'numero_tel' => $request->telefono,
                            'id_aval' => $id_aval
                        ));
                    }

                    return 'Se agregaron los datos correctamente';
                } catch (\Exception $exception) {
                    return "Exp 2: " . $exception;
                }
            }
        }
    }


    public function eliminarDireccionCliente(Request $request)
    {
        if ($request->ajax()) {
            BaseDinamica::connexionDynamicSon();
            Direccion::where(['id_direccion' => $request->id_direccion])->delete();
            return response()->json('Direccion de cliente eliminada', 200);
        }
    }


    public function agregarDatosCliente(Request $request, $id_cliente)
    {
        if ($request->ajax()) {
            BaseDinamica::connexionDynamicSon();
            return response()->json(Direccion::obtenerDireccionClienteCasa($id_cliente));
        } else {
            return Gestion::viewAndData('Gestion.agregarDatosCliente', $id_cliente);
        }
    }

    public function guardarDatosCliente(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        $respuesta = Direccion::compararDirecciones(0, $request);
        $msj = 'Ya existe esta direccion';

        if ($respuesta == null) {
            try {
                $direccion = new Direccion($request->all());
                $direccion->save();
                $msj = 'Agregado';
            } catch (\Exception $exception) {
                $msj = $exception;
            }
        }

        return response()->json($msj, 200);
    }

    public function buscarCliente(Request $request)
    {
        BaseDinamica::connexionDynamicSon();

        switch ($request->opcion) {
            case 1:
                ini_set('memory_limit', '9999999999999999999M');
                $data = Cliente::buscarClienteOpc1($request->buscarCliente);
                if (empty($data)) {
                    return redirect('gestion')->with('msj', 'No se encontro ningun id en la base');
                }
                break;

            case 2:
                Cliente::validarSoloLetras();
                ini_set('memory_limit', '9999999999999999999M');
                $data = Cliente::buscarClienteOpc2($request->buscarCliente);
                if (empty($data)) {
                    return redirect('gestion')->with('msj', 'No se encontro ningun nombre en la base');
                }
                break;

            case 3:
                Cliente::validarSoloNumeros();
                ini_set('memory_limit', '9999999999999999999M');
                $data = Cliente::buscarClienteOpc3($request->buscarCliente);
                if (empty($data)) {
                    return redirect('gestion')->with('msj', 'No se encontro ningun teléfono en la base');
                }
                break;

            case 4: //NUMERO DE GRUPO
                Cliente::validarSoloNumeros();
                ini_set('memory_limit', '9999999999999999999M');
                $data = Cliente::buscarClienteOpc4($request->buscarCliente);
                if (empty($data)) {
                    return redirect('gestion')->with('msj', 'No se encontro ningun número de grupo en la base');
                }
                break;

            case 5: //NOMBRE DE GRUPO
                ini_set('memory_limit', '9999999999999999999M');
                try {
                    $data = Cliente::buscarClienteOpc5($request->buscarCliente);
                } catch (\Exception $exception) {
                    dd($exception);
                }
                if (empty($data)) {
                    return redirect('gestion')->with('msj', 'No se encontro ningun nombre de grupo en la base');
                }
                break;
            case 6:
                Cliente::validarSoloNumeros();
                ini_set('memory_limit', '9999999999999999999M');
                $data = Cliente::buscarClienteOpc6($request->buscarCliente);
                if (empty($data)) {
                    return redirect('gestion')->with('msj', 'No se encontro ningun teléfono de aval en la base');
                }
                break;
        }

        return Gestion::viewAndData('Gestion.find', $data);
    }


    public function gestionPago(Request $request, $id)
    {
        BaseDinamica::connexionDynamicSon();
        $convenio = Convenio::obtenerConvenio($id);

        if (!is_null($convenio->id_convenio)) {

            $datosPago = CalendarioPagos::obtenerPagos($convenio->id_convenio);
            $totales = Convenio::calcularTotalPagado($convenio->id_gestion);
            $cliente = Cliente::obtenerClientes($id);
            $data = array(
                'convenio' => $convenio,
                'datosPago' => $datosPago,
                'id_cliente' => $id,
                'totales' => $totales,
                'cliente' => $cliente[0],
                'folio' => $convenio->id_convenio);

            if ($request->ajax()) {
                return response()->json($data);
            } else {
                return Gestion::viewAndData('Gestion.gestionPago', $data);
            }
        } else {
            return redirect()->action('GestionController@show', ['gestion' => $id])->with('msj', 'Ocurrio algo inesperado: No se encontraron pagos');
        }
    }


    public function gestionPagoGuardar(Request $request)
    {

        if ($request->ajax()) {
            try {
                BaseDinamica::connexionDynamicSon();
                $convenio = Convenio::obtenerConvenio($request->input('id_cliente'));
                $nueva_d_t = $convenio->deuda_total - $request->input('pago_realizado');

                $vacio = $request->input('folio_ingresado');
                if (empty($request->input('folio_ingresado'))) {
                    $vacio = "-";
                }

                DB::table('CalendarioPagos')
                    ->where('id_cliente', $request->input('id_cliente'))
                    ->where('id_calendario', $request->input('id_calendario'))
                    ->update(['fecha_pago_realizada' => $request->input('fecha_pago_realizada'),
                        'pagado' => TRUE,
                        'pago_realizado' => $request->input('pago_realizado'),
                        'folio_ingresado' => $vacio]);

                DB::table('Convenio')
                    ->where('id_gestion', $convenio->id_gestion)
                    ->update(['deuda_total' => $nueva_d_t, 'primer_pago_estado' => 1]);

                $datosPago = CalendarioPagos::obtenerPagos($convenio->id_convenio);
                $totales = Convenio::calcularTotalPagado($convenio->id_gestion);
                $cliente = Cliente::obtenerClientes($request->input('id_cliente'));
                $data = array(
                    'convenio' => $convenio,
                    'datosPago' => $datosPago,
                    'id_cliente' => $request->input('id_cliente'),
                    'totales' => $totales,
                    'cliente' => $cliente[0],
                    'folio' => $convenio->id_convenio);
                return response()->json($data);
            } catch (\Exception $exception) {
                return $exception;
            }
        }
    }


    public function cancelarConvenio(Request $request, $id_convenio, $id_cliente)
    {
        if ($request->ajax()) {
            BaseDinamica::connexionDynamicSon();
            DB::table('Convenio')
                ->where('id_convenio', $id_convenio)
                ->update(['convenio_estado' => FALSE]);
            return response()->json('Eliminado', 200);
        }
    }


    public function obtenerConvenio($id_cliente)
    {
        BaseDinamica::connexionDynamicSon();
        $folio = DB::SELECT('SELECT id_gestion FROM "Convenio" WHERE id_cliente = ? AND "estado_convenio" = ? AND "seLlegoAConvenio" = ?', [$id_cliente, TRUE, TRUE]);
        return $folio;
    }

    public function getDataSpe($id)
    {
        BaseDinamica::connexionDynamicSon();
        $convenio = Convenio::obtenerConvenio($id);
        $intencion = Intencion::obtenerIntencionUltimaCliente($id);
        $cliente = Cliente::obtenerClientes($id);
        $telefonos = Cliente::obtenerTelefonosXidCliente($id);

        $listaDatosClientesXTel = Cliente::obtenerClientesxTel($telefonos);

        $todoAval = Aval::obtenerTodoAvales($id);

        $pago = Pago::obtenerPago($id);
        $direcciones = Direccion::obtenerDireccionCliente($id);
        $trabajos = Trabajo::obtenerTrabajoCliente($id);
        $tipoGestion = Tipo_Gestion::obtenerTipoGestion();
        $tipoGestionSsl = Tipo_Gestion_ssl::obtenerTipoGestionSsl();
        $gestionado = Gestionado::obtenerTipoGestion();
        $pdfs = PDF::obtenerPDFs($id);
        $estado_intencion = NULL;

        /*CONVENIO = TRUE, INTENCION = FALSE, NADA = NULL*/

        if (is_null($convenio)) {
            if (is_null($intencion)) {
                $convenio_o_intencion = NULL;
            } else {
                $convenio_o_intencion = FALSE;
                $estado_intencion = $intencion->estado;
            }
        } else {
            if (is_null($intencion)) {
                $convenio_o_intencion = TRUE;
            } else {

                if ($convenio->created_at > $intencion->created_at) {
                    $convenio_o_intencion = TRUE;
                } else {
                    $convenio_o_intencion = FALSE;
                    $estado_intencion = $intencion->estado;
                }
            }
        }


        return $data = array(
            'cliente' => $cliente,
            'telefonos' => $telefonos,
            'datosAval' => $todoAval['datosAval'],
            'telefonosAval' => $todoAval['telefonosAval'],
            'pago' => $pago,
            'direcciones' => $direcciones,
            'trabajos' => $trabajos,
            'listaClientes' => $todoAval['listaClientes'],
            'auxListaClientes' => $todoAval['auxListaClientes'],
            'listaDatosClientesXTel' => $listaDatosClientesXTel,
            'tipoGestion' => $tipoGestion,
            'tipoGestionSsl' => $tipoGestionSsl,
            'gestionado' => $gestionado,
            'direccionAval' => $todoAval['direccionAval'],
            'convenio' => $convenio,
            'pdfs' => $pdfs,
            'convenio_o_intencion' => $convenio_o_intencion,
            'estado_intencion' => $estado_intencion
        );
    }

    public function tieneConvenioActivo($id_cliente)
    {
        BaseDinamica::connexionDynamicSon();
        return array('convenio'=>Convenio::obtenerConvenio($id_cliente));
    }
}
