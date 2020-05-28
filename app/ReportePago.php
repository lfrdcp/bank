<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use phpDocumentor\Reflection\Types\Boolean;


class ReportePago extends Model implements FromCollection, ShouldAutoSize
{
    private $fecha;
    private $filtrado;
    private $id_usuario;
    private $encargado;
    private $filtro_persona;
    private $esNuevo;
    private $esRecurrente;
    private $filtrado3;

    /**
     * @return string
     */
    public function getFecha(): string
    {
        return $this->fecha;
    }

    /**
     * @param string $fecha
     */
    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return string
     */
    public function getFiltrado(): string
    {
        return $this->filtrado;
    }

    /**
     * @param string $filtrado
     */
    public function setFiltrado(string $filtrado): void
    {
        $this->filtrado = $filtrado;
    }

    /**
     * @return int
     */
    public function getIdUsuario(): int
    {
        return $this->id_usuario;
    }

    /**
     * @param int $id_usuario
     */
    public function setIdUsuario(int $id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }

    /**
     * @return String
     */
    public function getEncargado(): String
    {
        return $this->encargado;
    }

    /**
     * @param String $encargado
     */
    public function setEncargado(String $encargado): void
    {
        $this->encargado = $encargado;
    }

    /**
     * @return string
     */
    public function getFiltroPersona(): string
    {
        return $this->filtro_persona;
    }

    /**
     * @param string $filtro_persona
     */
    public function setFiltroPersona(string $filtro_persona): void
    {
        $this->filtro_persona = $filtro_persona;
    }

    /**
     * @return bool
     */
    public function isEsNuevo(): bool
    {
        return $this->esNuevo;
    }

    /**
     * @param bool $esNuevo
     */
    public function setEsNuevo(bool $esNuevo): void
    {
        $this->esNuevo = $esNuevo;
    }

    /**
     * @return bool
     */
    public function isEsRecurrente(): bool
    {
        return $this->esRecurrente;
    }

    /**
     * @param bool $esRecurrente
     */
    public function setEsRecurrente(bool $esRecurrente): void
    {
        $this->esRecurrente = $esRecurrente;
    }

    public function __construct(string $filtrado3,string $filtrado, string $fecha, string $filtro_persona, int $id_usuario=null, String $encargado=null,bool $esNuevo=false,bool $esRecurrente=false)
    {
        $this->filtrado = $filtrado;
        $this->fecha = $fecha;
        $this->id_usuario = $id_usuario;
        $this->encargado = $encargado;
        $this->filtro_persona=$filtro_persona;
        $this->esNuevo=$esNuevo;
        $this->esRecurrente=$esRecurrente;
        $this->filtrado3=$filtrado3;
    }

    /**
     * @return string
     */
    public function getFiltrado3(): string
    {
        return $this->filtrado3;
    }

    /**
     * @param string $filtrado3
     */
    public function setFiltrado3(string $filtrado3): void
    {
        $this->filtrado3 = $filtrado3;
    }




    /**
     * @return Collection
     */
    public
    function collection()
    {
        // TODO: Implement collection() method.
        $collection = collect($this->prepararDatos());
        return $collection;
    }


    public function prepararDatos()
    {
        $vector = [];
        $datos = Gestion::reportePago($this);
        $vector[0] = [$this->cabezera()];

        $datos=array_merge($vector,$datos);
        $datos=json_decode(json_encode($datos), true);

        return $datos;
    }

    public function cabezera()
    {
        return $dato =
            'Fecha de pago' .
            '|' . 'Semana' .
            '|' . 'Cliente' .
            '|' . 'Nombre' .
            '|' . 'Importe' .
            '|' . 'Nuevo / Recurrente' .
            '|' . 'Estatus convenio' .
            '|' . 'ADC' .
            '|' . 'Gerencia' .
            '|' . 'Encargado / zona';
    }

}
