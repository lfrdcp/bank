<?php
namespace App;

class AvalModel
{
    private $nombreAval;
    private $idDireccion;
    private $idCliente;
    private $telefono;

    /**
     * Aval constructor.
     * @param $nombreAval
     * @param $idDireccion
     * @param $idCliente
     * @param $telefono
     */
    public function __construct($nombreAval, $idDireccion, $idCliente, $telefono)
    {
        $this->nombreAval = $nombreAval;
        $this->idDireccion = $idDireccion;
        $this->idCliente = $idCliente;
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getNombreAval()
    {
        return $this->nombreAval;
    }

    /**
     * @param mixed $nombreAval
     */
    public function setNombreAval($nombreAval): void
    {
        $this->nombreAval = $nombreAval;
    }

    /**
     * @return mixed
     */
    public function getIdDireccion()
    {
        return $this->idDireccion;
    }

    /**
     * @param mixed $idDireccion
     */
    public function setIdDireccion($idDireccion): void
    {
        $this->idDireccion = $idDireccion;
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
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    public function guardar()
    {
        $id_aval=ConsultaSQL::getInstance()->insertaAval($this->nombreAval,$this->idDireccion);
        /**Insertar relación Cliente Aval***/
        ConsultaSQL::getInstance()->insertaRelacionClienteAval($id_aval,$this->idCliente);
        if($this->telefono!="SIN TELEFONO AVAL" && $this->telefono !='S/T')
        {
            //sino existe telefono entonces insertar
            $idTelc=ConsultaSQL::getInstance()->existeTelefonoIDAval($this->telefono);
            if($idTelc==-1)
            {
                if($this->telefono!="SIN INFORMACION" && $this->telefono!='S/T' && $this->telefono!= "SININFORMACION")
                {
                    ConsultaSQL::getInstance()->insertaTelefonoAval($id_aval,$this->telefono);
                }
            }
            else //existe el número de telefono
            {
                //sino tiene relacion con cliente, entonces relacionar //


                if(ConsultaSQL::getInstance()->existeRelacionTelefonoIDAval($idTelc,$id_aval)==0)
                {
                    ConsultaSQL::getInstance()->insertaRelacionClienteTelefono($id_aval,$idTelc);
                }
            }
        }
    }
}
