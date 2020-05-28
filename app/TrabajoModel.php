<?php
namespace App;

class TrabajoModel
{
    private $num_tel;
    private $id_direccion;
    private $id_cliente;

    /**
     * TrabajoModel constructor.
     * @param $num_tel
     * @param $id_direccion
     * @param $id_cliente
     */
    public function __construct($num_tel, $id_direccion, $id_cliente)
    {
        $this->num_tel = strlen($num_tel)== 0 ? null : $num_tel;
        $this->id_direccion = $id_direccion;
        $this->id_cliente = $id_cliente;
    }

    /**
     * @return null
     */
    public function getNumTel()
    {
        return $this->num_tel;
    }

    /**
     * @param null $num_tel
     */
    public function setNumTel($num_tel): void
    {
        $this->num_tel = $num_tel;
    }

    /**
     * @return mixed
     */
    public function getIdDireccion()
    {
        return $this->id_direccion;
    }

    /**
     * @param mixed $id_direccion
     */
    public function setIdDireccion($id_direccion): void
    {
        $this->id_direccion = $id_direccion;
    }

    /**
     * @return mixed
     */
    public function getIdCliente()
    {
        return $this->id_cliente;
    }

    /**
     * @param mixed $id_cliente
     */
    public function setIdCliente($id_cliente): void
    {
        $this->id_cliente = $id_cliente;
    }

    public function __toString()
    {
        return "NumTel: ".$this->num_tel."ID Direccion".$this->id_direccion." IDCLIENTE".$this->id_cliente;
    }

    public function guardar()
    {
        ConsultaSQL::getInstance()->insertaTrabajoCliente($this);
    }

}
