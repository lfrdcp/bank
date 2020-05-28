<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\CalendarioPagos;
use App\Convenio;
use App\Intencion;
use App\User;
use Illuminate\Http\Request;
use App\Gestion;

class CalendarioController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    private function llenarNota($tipoUsuario, $id_usuario)
    {
        $notas = [];
        switch ($tipoUsuario) {
            case "Gestor":
                $gestiones = Gestion::obtenerGestionesGestor($id_usuario);
                $convenios = Convenio::obtenerConveniosGestor($id_usuario);
                $calendarioPagos = CalendarioPagos::obtenerCalendarioPagosGestor($id_usuario);
                $intencion = Intencion::obtenerIntenciones($id_usuario);

                $notasGestion = Gestion::generarNotas($gestiones, "Gestion");
                $notasContactar = Gestion::generarNotas($gestiones, "Contactar");
                $notasConvenio = Gestion::generarNotas($convenios, "Convenio");
                $notasCalendario = Gestion::generarNotas($calendarioPagos, "CalendarioPagos");
                $notasIntencion = Gestion::generarNotas($intencion, "Intencion");

                $notas = array_merge($notasConvenio, $notasContactar, $notasCalendario, $notasIntencion);
                break;
            case "Administrador":
            case "Supervisor":
                $gestiones = Gestion::obtenerGestionesSuperYAdmin();
                $convenios = Convenio::obtenerConveniosSuperYAdmin();
                $calendarioPagos = CalendarioPagos::obtenerCalendarioPagosSuperYAdmin();
                $intencion = Intencion::obtenerIntencionesSuperYAdmin();
                $notasGestion = Gestion::generarNotas($gestiones, "Gestion");
                $notasContactar = Gestion::generarNotas($gestiones, "Contactar");
                $notasConvenio = Gestion::generarNotas($convenios, "Convenio");
                $notasCalendario = Gestion::generarNotas($calendarioPagos, "CalendarioPagos");
                $notasIntencion = Gestion::generarNotas($intencion, "Intencion");
                //$notas=$notasGestion;
                $notas = array_merge($notasConvenio, $notasContactar, $notasCalendario, $notasIntencion);
                break;
        }
        return $notas;
    }


    public function prepararNota($tipoUsuario, $id_usuario)
    {
        $notas = $this::llenarNota($tipoUsuario, $id_usuario);
        $array = [];
        for ($i = 0; $i < count($notas); $i++) {
            $id_cliente = $notas[$i]->getIdCliente();
            $id = $notas[$i]->getId();
            $name = $notas[$i]->getName();
            $details = $notas[$i]->getDetails();
            $start = $notas[$i]->getStart();
            $end = $notas[$i]->getEnd();
            $hora = $notas[$i]->getHora();
            $folioGen = $notas[$i]->getFolioGen();
            $color = $notas[$i]->getColor();
            $tipo = $notas[$i]->getTipo();

            $array[$i] = array(
                'folioGen' => $folioGen,
                'id_cliente' => $id_cliente,
                'id' => $id,
                'name' => $name,
                'details' => $details,
                'start' => $start,
                'end' => $end,
                'color' => $color,
                'hora' => $hora,
                'tipo' => $tipo);
        }
        return $array;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $id = auth()->id();
        $tipo = auth()->user()->tipo;
        $despacho = auth()->user()->despacho;
        $usuarios = User::obtenerUsuariosGestores($despacho);
        $items = [];

        for ($i = 0; $i < count($usuarios); $i++) {
            $iniciales = substr($usuarios[$i]->name, 0, 1) . '' . substr($usuarios[$i]->last_name, 0, 1);
            $items[$i] = array(
                'nombre' => $usuarios[$i]->name . ' ' . $usuarios[$i]->last_name,
                'iniciales' => $iniciales,
                'id' => $usuarios[$i]->id,
                'tipo' => $usuarios[$i]->tipo);
        }


        BaseDinamica::connexionDynamicSon();

        if ($request->ajax()) {
            switch ($tipo) {
                case "Gestor":
                    return response()->json(
                        ['notas' => $this::prepararNota("Gestor", $id),
                            'filtro' => 0,
                            'tipoUsuario' => $tipo]);
                    break;


                case "Administrador":
                case "Supervisor":


                    if (empty($request->id)) {
                        return response()->json(
                            ['notas' => $this::prepararNota("Administrador", null),
                                'filtro' => 1,
                                'usuarios' => $items,
                                'tipoUsuario' => $tipo]);

                    } else {
                        return response()->json(
                            ['notas' => $this::prepararNota($request->tipo, $request->id),
                                'filtro' => 0,
                                'tipoUsuario' => $tipo]);
                    }
                    break;
            }
        } else {
            return Gestion::viewAndData('Calendario.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        BaseDinamica::connexionDynamicSon();
        $fecha = substr($request->start, 0, 10);
        $fecha_hora = $fecha . ' ' . $request->h;

        if ($request->tipo == "Contactar") {
            $fecha_hora = $request->f . ' ' . $request->h;

            Gestion::where('id_gestion', $id)
                ->update([
                    'comentario' => $request->com,
                    'fecha_hora_contactar' => $fecha_hora,
                ]);

        } else {
            CalendarioPagos::where('id_calendario', $id)
                ->update([
                    'comentario' => $request->com,
                    'fecha_pago_esperada' => $fecha_hora,
                ]);
        }


        $array = array(
            'folioGen' => $request->folioGen,
            'id_cliente' => $request->id_cliente,
            'tipo' => $request->tipo,
            'id' => $request->id,
            'name' => $request->nombre,
            'details' => $request->com,
            'start' => $fecha_hora,
            'end' => $fecha_hora,
            'color' => $request->color,
            'hora' => $request->h);
        return response()->json($array);
    }


}



