<?php

namespace App\modelosExcel;

use App\Convenio;
use App\Gestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ConvenioNuevo implements FromCollection, ShouldAutoSize
{
    private $fecha;
    private $filtro_tiempo;
    private $filtro_persona;
    private $id_usuario;
    private $encargado;
    private $tipoReporte;

    public function __construct(int $tipoReporte,string $fecha, string $filtro_tiempo, string $filtro_persona, int $id_usuario = null, String $encargado = null)
    {
        $this->fecha = $fecha;
        $this->filtro_tiempo = $filtro_tiempo;
        $this->filtro_persona = $filtro_persona;
        $this->id_usuario = $id_usuario;
        $this->encargado = $encargado;
        $this->tipoReporte=$tipoReporte;
    }

    //

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
        $datos = Convenio::generarExcelNuevo($this->tipoReporte,$this->fecha, $this->filtro_tiempo, $this->filtro_persona, $this->id_usuario, $this->encargado);
        $vector[0] = [$this->cabezera()];
        $datos=array_merge($vector,$datos);
        $datos=json_decode(json_encode($datos), true);
        return $datos;
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
            '|' . 'ZONA';
    }

}
