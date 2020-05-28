<?php
namespace App;
class DireccionModel
{
    private $idCliente;
    private $cuadrante;
    private $zonaGeo;
    private $direccion;
    private $numExt;
    private $numInt;
    private $tipoDireccion;
    private $cp;
    private $colonia;
    private $poblacion;
    private $estado;

    /**
     * DireccionModel constructor.
     * @param $idCliente
     * @param $cuadrante
     * @param $zonaGeo
     * @param $direccion
     * @param $numExt
     * @param $numInt
     * @param $tipoDireccion
     * @param $cp
     * @param $colonia
     * @param $poblacion
     * @param $estado
     */
    public function __construct($idCliente, $cuadrante, $zonaGeo, $direccion, $numExt, $numInt, $tipoDireccion, $cp, $colonia, $poblacion, $estado)
    {
        $this->idCliente = $idCliente;
        $this->cuadrante = strlen($cuadrante)==0 ? null : $cuadrante;
        $this->zonaGeo = strlen($zonaGeo)==0 ? null : $zonaGeo;
        $this->direccion = strlen($direccion)==0 ? null : $direccion;
        $this->numExt = $numExt=="SN" || $numExt =="S/N" || $numExt=="SINNUM.EXT.AVAL" || strlen($numExt)==0 ? null : $numExt;
        $this->numInt = $numInt=="SN" || $numInt =="S/N" || strlen($numInt)==0 ? null : $numInt;
        $this->tipoDireccion = $tipoDireccion;
        $this->cp = strlen($cp)==0 ? null : $cp;
        $this->colonia = strlen($colonia)==0 ? null : $colonia;
        $this->poblacion = strlen($poblacion)==0 ? null : $poblacion;
        $this->estado = strlen($estado)==0 || $estado=="SIN ESTADO" ?  null : $estado;
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
    public function getCuadrante()
    {
        return $this->cuadrante;
    }

    /**
     * @param mixed $cuadrante
     */
    public function setCuadrante($cuadrante): void
    {
        $this->cuadrante = $cuadrante;
    }

    /**
     * @return mixed
     */
    public function getZonaGeo()
    {
        return $this->zonaGeo;
    }

    /**
     * @param mixed $zonaGeo
     */
    public function setZonaGeo($zonaGeo): void
    {
        $this->zonaGeo = $zonaGeo;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getNumExt()
    {
        return $this->numExt;
    }

    /**
     * @param mixed $numExt
     */
    public function setNumExt($numExt): void
    {
        $this->numExt = $numExt;
    }

    /**
     * @return mixed
     */
    public function getNumInt()
    {
        return $this->numInt;
    }

    /**
     * @param mixed $numInt
     */
    public function setNumInt($numInt): void
    {
        $this->numInt = $numInt;
    }

    /**
     * @return mixed
     */
    public function getTipoDireccion()
    {
        return $this->tipoDireccion;
    }

    /**
     * @param mixed $tipoDireccion
     */
    public function setTipoDireccion($tipoDireccion): void
    {
        $this->tipoDireccion = $tipoDireccion;
    }

    /**
     * @return mixed
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * @param mixed $cp
     */
    public function setCp($cp): void
    {
        $this->cp = $cp;
    }

    /**
     * @return mixed
     */
    public function getColonia()
    {
        return $this->colonia;
    }

    /**
     * @param mixed $colonia
     */
    public function setColonia($colonia): void
    {
        $this->colonia = $colonia;
    }

    /**
     * @return mixed
     */
    public function getPoblacion()
    {
        return $this->poblacion;
    }

    /**
     * @param mixed $poblacion
     */
    public function setPoblacion($poblacion): void
    {
        $this->poblacion = $poblacion;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado): void
    {
        $this->estado = $estado;
    }
    public function __toString()
    {
        return "ID cliente: ".$this->idCliente." Cuadrante: ".$this->cuadrante." zonaGeo: ".$this->zonaGeo.
            " direccion: ".$this->direccion." num ext: ".$this->numExt." numInt: ".$this->numInt." tipo: ".$this->tipoDireccion.
            " cp: ".$this->cp." colonia: ".$this->colonia." poblacion: ".$this->poblacion." estado: ".$this->estado;
    }

    public function guardar() :int
    {
        return ConsultaSQL::getInstance()->insertaDireccion($this);
    }

    public function actualizar($idDireccion)
    {
        ConsultaSQL::getInstance()->actualizarDireccion($this,$idDireccion);
    }
}
