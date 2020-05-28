<?php

namespace App\modelosExcel;

use App\Convenio;
use App\Gestion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GestionGeneral implements FromCollection, ShouldAutoSize
{
    private $fecha;
    private $filtro_tiempo;
    private $filtro_persona;
    private $id_usuario;

    public function __construct(string $fecha, string $filtro_tiempo, string $filtro_persona, int $id_usuario = null)
    {
        $this->fecha = $fecha;
        $this->filtro_tiempo = $filtro_tiempo;
        $this->filtro_persona = $filtro_persona;
        $this->id_usuario = $id_usuario;
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
        $datos = Gestion::generarExcel($this->fecha, $this->filtro_tiempo, $this->filtro_persona, $this->id_usuario);
        $vector[0] = [$this->cabezera()];
        $datos=array_merge($vector,$datos);
        $datos=json_decode(json_encode($datos), true);
        return $datos;
    }

    public function cabezera()
    {
        return $dato = 'FOLIO GENERAL' .
            '|' . 'CODIGO ACCION' .
            '|' . 'CODIGO RESULTADO' .
            '|' . 'GESTION SCL' .
            '|' . 'GESTOR' .
            '|' . 'FECHA DE GESTION' .
            '|' . 'HORA DE GESTION' .
            '|' . 'CLIENTE UNICO' .
            '|' . 'NOMBRE CLIENTE' .
            '|' . 'SALDO TOTAL' .
            '|' . 'SALDO PLAN' .
            '|' . 'CANTIDAD DE PAGOS' .
            '|' . 'PAGO INICIAL' .
            '|' . 'FECHA PAGO INICIAL' .
            '|' . 'COMENTARIOS' .
            '|' . 'GERENCIA' .
            '|' . 'ENCARGADO';
    }
}
