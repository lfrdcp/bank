<?php
namespace App;


class PagoModel
{
    private $idCliente;
    private $clasificacion;
    private $atrasoMax;
    private $saldo;
    private $moratorios;
    private $total;
    private $diaPago;
    private $importe_pago_ultimo;
    private $fechaPagoUltimo;

    /**
     * PagoModel constructor.
     * @param $idCliente
     * @param $clasificacion
     * @param $atrasoMax
     * @param $saldo
     * @param $moratorios
     * @param $total
     * @param $diaPago
     * @param $fechaPagoUltimo
     * @param $importe_pago_ultimo
     */
    public function __construct($idCliente, $clasificacion, $atrasoMax, $saldo, $moratorios, $total, $diaPago, $fechaPagoUltimo,$importe_pago_ultimo)
    {
        $this->idCliente = $idCliente;
        $this->clasificacion = strlen($clasificacion)==0 ? null : $clasificacion;
        $this->atrasoMax = strlen($atrasoMax)==0 ? null : $atrasoMax;
        $this->saldo = strlen($saldo)==0 ? null : str_replace("$","",$saldo);
        $this->moratorios = strlen($moratorios)==0 ? null : $moratorios;
        $this->total = strlen($total)==0 ? null : str_replace("$","",$total);
        $this->diaPago = strlen($diaPago)==0 ? null : $diaPago;
        $this->fechaPagoUltimo = strlen($fechaPagoUltimo)==0 ? null : $fechaPagoUltimo;
        $this->importe_pago_ultimo = strlen($importe_pago_ultimo)==0 ? null : $importe_pago_ultimo;
    }

    /**
     * @return mixed
     */
    public function getImportePagoUltimo()
    {
        return $this->importe_pago_ultimo;
    }

    /**
     * @param mixed $importe_pago_ultimo
     */
    public function setImportePagoUltimo($importe_pago_ultimo): void
    {
        $this->importe_pago_ultimo = $importe_pago_ultimo;
    }

    /**
     * @return mixed
     */
    public function getIdCliente()
    {
        return $this->idCliente;
    }

    /**
     * @param mixed $idCliente
     */
    public function setIdCliente($idCliente): void
    {
        $this->idCliente = $idCliente;
    }

    /**
     * @return mixed
     */
    public function getClasificacion()
    {
        return $this->clasificacion;
    }

    /**
     * @param mixed $clasificacion
     */
    public function setClasificacion($clasificacion): void
    {
        $this->clasificacion = $clasificacion;
    }

    /**
     * @return mixed
     */
    public function getAtrasoMax()
    {
        return $this->atrasoMax;
    }

    /**
     * @param mixed $atrasoMax
     */
    public function setAtrasoMax($atrasoMax): void
    {
        $this->atrasoMax = $atrasoMax;
    }

    /**
     * @return mixed
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * @param mixed $saldo
     */
    public function setSaldo($saldo): void
    {
        $this->saldo = $saldo;
    }

    /**
     * @return mixed
     */
    public function getMoratorios()
    {
        return $this->moratorios;
    }

    /**
     * @param mixed $moratorios
     */
    public function setMoratorios($moratorios): void
    {
        $this->moratorios = $moratorios;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total): void
    {
        $this->total = $total;
    }

    /**
     * @return mixed
     */
    public function getDiaPago()
    {
        return $this->diaPago;
    }

    /**
     * @param mixed $diaPago
     */
    public function setDiaPago($diaPago): void
    {
        $this->diaPago = $diaPago;
    }

    /**
     * @return mixed
     */
    public function getFechaPagoUltimo()
    {
        return $this->fechaPagoUltimo;
    }

    /**
     * @param mixed $fechaPagoUltimo
     */
    public function setFechaPagoUltimo($fechaPagoUltimo): void
    {
        $this->fechaPagoUltimo = $fechaPagoUltimo;
    }

    public function __toString()
    {
        return "IDCliente: ".$this->idCliente." Clasificacion: ".$this->clasificacion."Atraso Max: ".$this->atrasoMax." saldo: ".$this->saldo
            ."Moratorios: ".$this->moratorios."Total: ".$this->total."Dia pago: ".$this->diaPago."fecha ultimoPago: ".$this->fechaPagoUltimo.
            "Importe Pago último: ".$this->importe_pago_ultimo;
    }

    public function guardar()
    {
        ConsultaSQL::getInstance()->insertaPago($this);
    }

    public function actualizar()
    {
        ConsultaSQL::getInstance()->actualizaPago($this);
    }

}
