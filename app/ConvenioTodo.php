<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ConvenioTodo extends Model
{
    private $fecha;
    private $filtro_tiempo;
    private $filtro_persona;
    private $id_usuario;
    private $encargado;

    public function __construct(string $fecha, string $filtro_tiempo, string $filtro_persona, int $id_usuario = null, String $encargado = null)
    {
        $this->fecha = $fecha;
        $this->filtro_tiempo = $filtro_tiempo;
        $this->filtro_persona = $filtro_persona;
        $this->id_usuario = $id_usuario;
        $this->encargado = $encargado;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        // TODO: Implement collection() method.
        $collection = collect($this::prepararDatos());
        return $collection;
    }

    public function prepararDatos()
    {
        $vector = [];
        $arreglo = Convenio::generarExcelRecurrente($this->fecha, $this->filtro_tiempo, $this->filtro_persona, $this->id_usuario, $this->encargado);
        $datos = $arreglo['unico'];
        $pagos = $arreglo['pagos'];

        $vector[0] = [$this->cabezera()];
        for ($i = 0; $i < count($datos); $i++) {
            $dato =
                $datos[$i]->folioGen .
                '|' . $datos[$i]->convenio_liquidacion .
                '|' . $datos[$i]->tipo_gestion .
                '|' . $datos[$i]->gestor .
                '|' . $datos[$i]->semana .
                '|' . $datos[$i]->fechagestion .
                '|' . $datos[$i]->id_cliente .
                '|' . $datos[$i]->nombre .
                '|' . $datos[$i]->saldototal .
                '|' . $datos[$i]->saldoplan .
                '|' . $datos[$i]->cantidadpagos .
                '|' . $datos[$i]->primer_pago_cantidad .
                '|' . $datos[$i]->fechapagoinicial .
                '|' . $datos[$i]->pagosemanal .
                '|' . $datos[$i]->estatus .
                '|' . $datos[$i]->avancedelplan .
                '|' . $datos[$i]->porpagar .
                '|' . $datos[$i]->gerencia .
                '|' . $datos[$i]->encargado .
                '|' . $datos[$i]->zona .
                $pagos[$i];
            $vector[$i + 1] = [$dato];
        }
        return $vector;
    }

    public function cabezera()
    {
        return $dato = 'FOLIO GENERAL' .
            '|' . 'CONVENIO O LIQUIDACION' .
            '|' . 'TIPO DE GESTION' .
            '|' . 'GESTOR' .
            '|' . 'SEMANA' .
            '|' . 'FECHA DE GESTION' .
            '|' . 'CLIENTE UNICO' .
            '|' . 'NOMBRE CLIENTE' .
            '|' . 'SALDO TOTAL' .
            '|' . 'SALDO PLAN' .
            '|' . 'CANTIDAD DE PAGOS' .
            '|' . 'PAGO INICIAL' .
            '|' . 'FECHA PAGO INICIAL' .
            '|' . 'PAGO SEMANAL' .
            '|' . 'ESTATUS PLAN' .
            '|' . 'AVANCE DEL PLAN' .
            '|' . 'POR PAGAR' .
            '|' . 'GERENCIA' .
            '|' . 'ENCARGADO' .
            '|' . 'ZONA' .
            self::cabecera_generar_num_pagos();
    }

    public function cabecera_generar_num_pagos()
    {
        $cabecera_pagos = '';
        for ($i = 0; $i < 53; $i++) {
            if ($i == 0) {
                $cabecera_pagos = $cabecera_pagos . '|' . 'Fecha de pago inicial' . '|' . 'Primer pago' . '|' . 'Estatus de primer pago';
            } else {
                $cabecera_pagos = $cabecera_pagos . '|' . 'Fecha: ' . $i . '|' . 'Monto: ' . $i . '|' . 'Estatus: ' . $i;
            }
        }
        return $cabecera_pagos;
    }
}
