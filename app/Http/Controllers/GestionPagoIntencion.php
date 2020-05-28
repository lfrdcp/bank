<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\Cliente;
use App\Gestion;
use App\Intencion;
use App\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GestionPagoIntencion extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function store(Request $request)
    {
        try {

            $id_usuario = auth()->id();
            $username = auth()->user()->username;
            BaseDinamica::connexionDynamicSon();

            $auxFecha = $request->input('fechaContactar');
            $auxHora = $request->input('horaContactar');
            $fechaHora = $auxFecha . ' ' . $auxHora;
            $fechaActual = date('Y-m-d H:i:s', strtotime("now"));
            $id_gestion = DB::table('Gestion')->insertGetId(array(
                'folioGen' => 0,
                'username' => $username,
                'id_usuario' => $id_usuario,
                'id_cliente' => $request->input('id_cliente'),
                'fecha_hora_contactar' => $fechaHora,
                'comentario' => $request->input('comentario'),
                'tit_aval' => null,
                'id_tipo_gestion' => null,
                'id_tipo_gestion_ssl' => null,
                'id_gestionado' => null,
                'created_at' => $fechaActual,
                'updated_at' => $fechaActual
            ), 'id_gestion');


            /*
            PAGO INTENCION ACTIVO = 0
            PAGO INTENCION CANCELADO = 1
            PAGO INTENCION CUMPLIDO = 2
            */


            $intencion = new Intencion();
            $intencion->id_cliente = $request->input('id_cliente');
            $auxFecha = $request->input('fecha');
            $auxHora = $request->input('horaContactar');
            $intencion->fecha = $auxFecha . ' ' . $auxHora;
            $intencion->pago = $request->input('pago');
            $intencion->estado = '0';

            $intencion->folioGen = '';
            $intencion->id_gestion = $id_gestion;
            $intencion->save();


            return 'Guardado';
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function realizarPagoIntencion(Request $request, $id)
    {
        if ($request->ajax()) {
            BaseDinamica::connexionDynamicSon();
            $intencion = Intencion::obtenerIntencionUltimaCliente($id);

            if (is_null($intencion->pago_realizado)) {
                $intencion->pago_realizado = 0;
            }
            $deudaOriginal = $intencion->pago;
            $deudaPagada = $intencion->pago_realizado;
            $deudaPendiente = $intencion->pago - $intencion->pago_realizado;
            $totales = array('deudaOriginal' => $deudaOriginal, 'deudaPagada' => $deudaPagada, 'deudaTotal' => $deudaPendiente);
            $paquete = array('intencion' => $intencion, 'totales' => $totales);
            return response()->json($paquete, '200');
        } else {
            return false;
        }
    }

    public function guardarPagoIntencion(Request $request)
    {
        BaseDinamica::connexionDynamicSon();

        DB::table('Intencion')
            ->where('id_intencion', $request->id_intencion)
            ->update([
                    'estado' => '2',
                    'pago_realizado' => $request->pago_realizado,
                    'fecha_realizada' => $request->fecha_pago_realizada
                ]
            );
        return response()->json(true, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            BaseDinamica::connexionDynamicSon();
            $pago = Pago::obtenerPago($id);
            $cliente = Cliente::obtenerClientes($id);
            $info = array('nombre' => $cliente[0]->nombre, 'total' => $pago[0]->total);
            return response()->json($info, '200');
        } else {
            return Gestion::viewAndData('Gestion.pagoIntencion', $id);
        }
    }

    public function cancelarPagoIntencion($id_intencion)
    {

        BaseDinamica::connexionDynamicSon();
        DB::table('Intencion')
            ->where('id_intencion', $id_intencion)
            ->update([
                    'estado' => '1'
                ]
            );
        return response()->json(true, 200);

    }
}
