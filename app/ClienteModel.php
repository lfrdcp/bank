<?php
namespace App;
use App\Traits\Util;

class ClienteModel
{
    use Util;
    private $clienteUnicoID;
    private $nombreCliente;
    private $rfcCliente;
    private $gerencia;
    private $encargado;
    private $numeroId;
    private $nombreGrupo;

    /**
     * ClienteModel constructor.
     * @param $clienteUnicoID
     * @param $nombreCliente
     * @param $rfcCliente
     * @param $gerencia
     * @param $encargado
     * @param $numeroId
     * @param $nombreGrupo
     */
    public function __construct($clienteUnicoID, $nombreCliente, $rfcCliente, $gerencia, $encargado, $numeroId, $nombreGrupo)
    {
        $this->clienteUnicoID = $clienteUnicoID;
        $this->nombreCliente = $nombreCliente;
        $this->rfcCliente =strlen($rfcCliente)==0 || $rfcCliente=="SIN INF" ? null : $rfcCliente;
        $this->gerencia =strlen($gerencia)==0 ? null : $gerencia;
        $this->encargado =strlen($encargado)==0 || $encargado =="OTRO - NO ASIGNADO OFICIALMENTE"? null : $encargado;
        $this->renombrarEncargado($this->encargado);
        $this->numeroId =strlen($numeroId)==0 ? null : $numeroId;
        $this->nombreGrupo =strlen($nombreGrupo)==0 ? null : $nombreGrupo;
    }

    /**
     * @return null
     */
    public function getNumeroId()
    {
        return $this->numeroId;
    }

    /**
     * @param null $numeroId
     */
    public function setNumeroId($numeroId): void
    {
        $this->numeroId = $numeroId;
    }

    /**
     * @return null
     */
    public function getNombreGrupo()
    {
        return $this->nombreGrupo;
    }

    /**
     * @param null $nombreGrupo
     */
    public function setNombreGrupo($nombreGrupo): void
    {
        $this->nombreGrupo = $nombreGrupo;
    }




    /**
     * @return mixed
     */
    public function getClienteUnicoID()
    {
        return $this->clienteUnicoID;
    }

    /**
     * @param mixed $clienteUnicoID
     */
    public function setClienteUnicoID($clienteUnicoID): void
    {
        $this->clienteUnicoID = $clienteUnicoID;
    }

    /**
     * @return mixed
     */
    public function getNombreCliente()
    {
        return $this->nombreCliente;
    }

    /**
     * @param mixed $nombreCliente
     */
    public function setNombreCliente($nombreCliente): void
    {
        $this->nombreCliente = $nombreCliente;
    }

    /**
     * @return mixed
     */
    public function getRfcCliente()
    {
        return $this->rfcCliente;
    }

    /**
     * @param mixed $rfcCliente
     */
    public function setRfcCliente($rfcCliente): void
    {
        $this->rfcCliente = $rfcCliente;
    }

    /**
     * @return mixed
     */
    public function getGerencia()
    {
        return $this->gerencia;
    }

    /**
     * @param mixed $gerencia
     */
    public function setGerencia($gerencia): void
    {
        $this->gerencia = $gerencia;
    }

    /**
     * @return mixed
     */
    public function getEncargado()
    {
        return $this->encargado;
    }

    /**
     * @param mixed $encargado
     */
    public function setEncargado($encargado): void
    {
        $this->encargado = $encargado;
    }

    public function __toString()
    {
        return "ID: ".$this->clienteUnicoID."<br /> Nombre: "
            .$this->nombreCliente."<br /> RFC: ".$this->rfcCliente.
            "<br /> Gerencia".$this->gerencia."<br />Encargado ".$this->encargado."<br />";
    }

    public function guardar($formato=null)
    {
        ConsultaSQL::getInstance()->insertaCliente($this,$formato);
    }
}
