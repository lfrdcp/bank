<?php
namespace App;

class TelefonoCliente
{
    private $tel1;
    private $tel2;
    private $tel3;
    private $tel4;
    private $idClietne;

    /**
     * TelefonoCliente constructor.
     * @param $tel1
     * @param $tel2
     * @param $tel3
     * @param $tel4
     * @param $idClietne
     */
    public function __construct($tel1, $tel2, $tel3, $tel4, $idClietne)
    {
        //
        $this->tel1 = $tel1 == "SIN INFORMACION" || $tel1 =='S/T' || $tel1 == "SININFORMACION" ? null : $tel1;
        $this->tel2 = $tel2 == "SIN INFORMACION" || $tel2 =='S/T' || $tel2 == "SININFORMACION" ? null : $tel2;
        $this->tel3 = $tel3 == "SIN INFORMACION" || $tel3 =='S/T' || $tel3 == "SININFORMACION" ? null : $tel3;
        $this->tel4 = $tel4 == "SIN INFORMACION" || $tel4 =='S/T' || $tel4 == "SININFORMACION" ? null : $tel4;
        $this->idClietne = $idClietne;
    }

    /**
     * @return mixed
     */
    public function getTel1()
    {
        return $this->tel1;
    }

    /**
     * @param mixed $tel1
     */
    public function setTel1($tel1): void
    {
        $this->tel1 = $tel1;
    }

    /**
     * @return mixed
     */
    public function getTel2()
    {
        return $this->tel2;
    }

    /**
     * @param mixed $tel2
     */
    public function setTel2($tel2): void
    {
        $this->tel2 = $tel2;
    }

    /**
     * @return mixed
     */
    public function getTel3()
    {
        return $this->tel3;
    }

    /**
     * @param mixed $tel3
     */
    public function setTel3($tel3): void
    {
        $this->tel3 = $tel3;
    }

    /**
     * @return mixed
     */
    public function getTel4()
    {
        return $this->tel4;
    }

    /**
     * @param mixed $tel4
     */
    public function setTel4($tel4): void
    {
        $this->tel4 = $tel4;
    }

    /**
     * @return mixed
     */
    public function getIdClietne()
    {
        return $this->idClietne;
    }

    /**
     * @param mixed $idClietne
     */
    public function setIdClietne($idClietne): void
    {
        $this->idClietne = $idClietne;
    }

    public function guardar()
    {
        if($this->tel1!=null)
        {
            $idTelC=ConsultaSQL::getInstance()->existeTelefonoIDCliente($this->tel1);
            if($idTelC==-1)
            {
                $idTel=ConsultaSQL::getInstance()->insertaTelefonoCliente($this->tel1);
                ConsultaSQL::getInstance()->insertaRelacionClienteTelefono($this->idClietne,$idTel);
            }
            else //existe el número de telefono
            {
                //sino tiene relacion con cliente, entonces relacionar
                if(ConsultaSQL::getInstance()->existeRelacionTelefonoIDCliente($idTelC,$this->idClietne)==0)
                {
                    ConsultaSQL::getInstance()->insertaRelacionClienteTelefono($this->idClietne,$idTelC);
                }
            }
        }
        if($this->tel2!=null)
        {
            $idTelC=ConsultaSQL::getInstance()->existeTelefonoIDCliente($this->tel2);
            if($idTelC==-1)
            {
                $idTel=ConsultaSQL::getInstance()->insertaTelefonoCliente($this->tel2);
                ConsultaSQL::getInstance()->insertaRelacionClienteTelefono($this->idClietne,$idTel);
            }
            else //existe el número de telefono
            {
                //sino tiene relacion con cliente, entonces relacionar
                if(ConsultaSQL::getInstance()->existeRelacionTelefonoIDCliente($idTelC,$this->idClietne)==0)
                {
                    ConsultaSQL::getInstance()->insertaRelacionClienteTelefono($this->idClietne,$idTelC);
                }
            }
        }
        if($this->tel3!=null)
        {
            //sino existe telefono entonces insertar
            $idTelC=ConsultaSQL::getInstance()->existeTelefonoIDCliente($this->tel3);
            if($idTelC==-1)
            {
                $idTel=ConsultaSQL::getInstance()->insertaTelefonoCliente($this->tel3);
                ConsultaSQL::getInstance()->insertaRelacionClienteTelefono($this->idClietne,$idTel);
            }
            else //existe el número de telefono
            {
                //sino tiene relacion con cliente, entonces relacionar de otra manera no hacer nada
                if(ConsultaSQL::getInstance()->existeRelacionTelefonoIDCliente($idTelC,$this->idClietne)==0)
                {
                    ConsultaSQL::getInstance()->insertaRelacionClienteTelefono($this->idClietne,$idTelC);
                }
            }
        }
        if($this->tel4!=null)
        {
            //sino existe telefono entonces insertar
            $idTelC=ConsultaSQL::getInstance()->existeTelefonoIDCliente($this->tel4);
            if($idTelC==-1)
            {
                $idTel=ConsultaSQL::getInstance()->insertaTelefonoCliente($this->tel4);
                ConsultaSQL::getInstance()->insertaRelacionClienteTelefono($this->idClietne,$idTel);
            }
            else //existe el número de telefono
            {
                //sino tiene relacion con cliente, entonces relacionar de otra manera no hacer nada
                if(ConsultaSQL::getInstance()->existeRelacionTelefonoIDCliente($idTelC,$this->idClietne)==0)
                {
                    ConsultaSQL::getInstance()->insertaRelacionClienteTelefono($this->idClietne,$idTelC);
                }
            }
        }

    }
}
