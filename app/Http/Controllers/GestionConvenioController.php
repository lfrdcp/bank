<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\Convenio;
use App\Gestion;
use App\Intencion;
use App\Telefono_Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GestionConvenioController extends Controller
{

    public function gestiones($id_cliente)
    {
        BaseDinamica::connexionDynamicSon();
        return response()->json(Gestion::obtenerFoliosConsecutivos($id_cliente));
    }


    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param $id_cliente
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function show(Request $request, $id_cliente)
    {
        BaseDinamica::connexionDynamicSon();
        if ($request->ajax()) {
            $convenios = Convenio::obtenerConveniosCliente($id_cliente);
            $intencion = Intencion::obtenerIntencionesCliente($id_cliente);
            $arregloConvenios = [];
            $arregloIntencion = [];
            for ($i = 0; $i < count($convenios); $i++) {

                if ($convenios[$i]->numero_pagos === 0) {
                    $numPago = 'Un solo pago';
                } else {
                    $numPago = $convenios[$i]->numero_pagos;
                }

                if (is_null($convenios[$i]->convenio_estado)) {
                    $estado = 'Pendiente';
                } else if ($convenios[$i]->convenio_estado) {
                    if ($convenios[$i]->deuda_total === 0) {
                        $estado = 'Liquidado';
                    } else {
                        $estado = 'Activo';
                    }
                } else {
                    $estado = 'Cancelado';
                }

                $arregloConvenios[$i] = array(
                    'folioGen' => trim($convenios[$i]->folioGen),
                    'estado' => $estado,
                    'numero_pagos' => $numPago,
                    'primer_pago_cantidad' => $convenios[$i]->primer_pago_cantidad,
                    'deuda_total_original' => $convenios[$i]->deuda_total_original,
                    'deuda_total' => $convenios[$i]->deuda_total
                );
            }

            for ($i = 0; $i < count($intencion); $i++) {
                $numPago = 'Un solo pago (Intención)';
                if ($intencion[$i]->estado == '0') {
                    $estado = 'Pendiente';
                } else if ($intencion[$i]->estado == '1') {
                    $estado = 'Cancelado';
                } else {
                    $estado = 'Pagado';
                }

                if ($intencion[$i]->pago_realizado == null) {
                    $debe = $intencion[$i]->pago;
                } else {
                    $debe = $intencion[$i]->pago - $intencion[$i]->pago_realizado;
                }
                $arregloIntencion[$i] = array(
                    'folioGen' => trim($intencion[$i]->folioGen),
                    'estado' => $estado,
                    'numero_pagos' => $numPago,
                    'primer_pago_cantidad' => 0,
                    'deuda_total_original' => $intencion[$i]->pago,
                    'deuda_total' => $debe
                );

            }

            $arregloConvenios = array_merge($arregloConvenios, $arregloIntencion);
            return response()->json($arregloConvenios);
        } else {
            return Gestion::viewAndData('GestionConvenio.show', compact('id_cliente'));
        }
    }

}
