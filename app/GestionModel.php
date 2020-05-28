<?php

namespace App;


class GestionModel
{
    private $clienteUnico;
    private $idUsuario;
    private $comentario;
    private $titAval;
    private $idTipoGestion;
    private $idTipoGestionSSL;
    /**
     * GestionManual constructor.
     * @param $mapa
     * @param $idUsuario
     */
    public function __construct($mapa,$idUsuario)
    {
        $this->clienteUnico = isset($mapa[DataGeneral::CLIENTE_UNICO]) ? $mapa[DataGeneral::CLIENTE_UNICO] : null;
        if(isset($mapa[DataGeneral::CODIGO_ACCION]))
        {
            $auxCodigoAccion=$mapa[DataGeneral::CODIGO_ACCION];
            if($auxCodigoAccion=='LA')
            {
                $this->titAval = 0;
            }
            else if($auxCodigoAccion=='LT')
            {
                $this->titAval = 1;
            }
            else if($auxCodigoAccion=='OG')
            {
                $this->titAval = 2; // OG
            }
        }
        else if(isset($mapa[DataGeneral::ESTADO_LLAMADA]))
        {
            $this->titAval=1;
        }
        if(isset($mapa[DataGeneral::CODIGO_RESULTADO]))
        {
            $this->idTipoGestion = ConsultaSQL::getInstance()->consultaIDCodigoResultado($mapa[DataGeneral::CODIGO_RESULTADO]);
        }
        else if(isset($mapa[DataGeneral::ESTADO_LLAMADA]))
        {
            $estadoLlamada=trim(strtoupper($mapa[DataGeneral::ESTADO_LLAMADA]));
            switch ($estadoLlamada)
            {
                case "SUCCESS":
                    $this->idTipoGestion = ConsultaSQL::getInstance()->consultaIDCodigoResultado("MT");
                    $this->comentario="MENSAJE CON TERCEROS";
                    break;
                case "ABANDONED":
                case "FAILURE":
                    $this->idTipoGestion = ConsultaSQL::getInstance()->consultaIDCodigoResultado("OC");
                    $this->comentario="TELEFONO OCUPADO";
                    break;
                case "NOANSWER":
                    $this->idTipoGestion = ConsultaSQL::getInstance()->consultaIDCodigoResultado("NC");
                    $this->comentario="NO CONTESTA";
                    break;
                case "SHORTCALL":
                    $this->idTipoGestion = ConsultaSQL::getInstance()->consultaIDCodigoResultado("MQ");
                    $this->comentario="BUZON DE VOZ";
                    break;
                default:
                    $this->idTipoGestion = null;
            }
        }
        if(isset($mapa[DataGeneral::COMENTARIO]))
        {
            $this->comentario=$mapa[DataGeneral::COMENTARIO];
        }
        $this->idTipoGestionSSL =  isset($mapa[DataGeneral::ID_SCL]) ? $mapa[DataGeneral::ID_SCL]: null;
        $this->idUsuario=$idUsuario;
    }
    /**
     * @return mixed
     */
    public function getClienteUnico()
    {
        return $this->clienteUnico;
    }

    /**
     * @param mixed $clienteUnico
     */
    public function setClienteUnico($clienteUnico): void
    {
        $this->clienteUnico = $clienteUnico;
    }

    /**
     * @return mixed
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * @param mixed $idUsuario
     */
    public function setIdUsuario($idUsuario): void
    {
        $this->idUsuario = $idUsuario;
    }
    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return mixed
     */
    public function getTitAval()
    {
        return $this->titAval;
    }

    /**
     * @param mixed $titAval
     */
    public function setTitAval($titAval): void
    {
        $this->titAval = $titAval;
    }

    /**
     * @return mixed
     */
    public function getIdTipoGestion()
    {
        return $this->idTipoGestion;
    }

    /**
     * @param mixed $idTipoGestion
     */
    public function setIdTipoGestion($idTipoGestion): void
    {
        $this->idTipoGestion = $idTipoGestion;
    }

    /**
     * @return mixed
     */
    public function getIdTipoGestionSSL()
    {
        return $this->idTipoGestionSSL;
    }

    /**
     * @param mixed $idTipoGestionSSL
     */
    public function setIdTipoGestionSSL($idTipoGestionSSL): void
    {
        $this->idTipoGestionSSL = $idTipoGestionSSL;
    }

    public function guardar()
    {
        ConsultaSQL::getInstance()->insertarGestion($this);
    }

    public function __toString()
    {
        return "id cliente: ".$this->clienteUnico." \nComentario: ".$this->comentario.
            "\n TitAval: ".$this->titAval." \nidTipoGestion: ".$this->idTipoGestion." \ntipoGestion SSL: ".$this->idTipoGestionSSL;
    }
}
