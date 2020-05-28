<?php

namespace App;
require_once 'ClienteModel.php';
require_once 'DataGeneral.php';

class Linea
{
    private $linea;
    private $mapa;

    /**
     * ProcesoLimpiarDatos constructor.
     * @param $linea
     */
    public function __construct($linea=null)
    {
        $this->linea = $linea;
        $this->mapa= array();
    }
    /**
     * @return string
     */
    public function getLinea() : String
    {
        return $this->linea;
    }
    /**
     * @param mixed $linea
     */
    public function setLinea($linea): void
    {
        $this->linea = $linea;
    }

    /**
     * @return array
     */
    public function getMapa(): array
    {
        return $this->mapa;
    }

    /**
     * @param array $mapa
     */
    public function setMapa(array $mapa): void
    {
        $this->mapa = $mapa;
    }

    public function limpiarLinea()
    {
        $this->linea=str_replace(",","",$this->linea);
        if($this->linea[0]=="\"")
        {
            $this->linea=substr($this->linea,1,strlen($this->linea));
        }
        if($this->linea[strlen($this->linea)-3]=="\"")
        {
            $this->linea=substr($this->linea,0,strlen($this->linea)-3);
        }
        $vectorPosiciones=[];
        $controlVector=0;
        $paso=false;
        for($i=0; $i<strlen($this->linea); $i++)
        {
            if($this->linea[$i]=="\"" && $this->linea[$i+1]=="\"")
            {
                if($paso)
                {
                    $controlVector=0;
                    $paso=false;
                }
                if ($controlVector == 0)
                {
                    $vectorPosiciones[0][$controlVector]=$i;
                }
                else
                {
                    $vectorPosiciones[0][$controlVector]=$i+1;
                }
                if($controlVector==1)
                {
                    $controlVector=0;
                    $paso=true;
                    $this->modificarLinea($vectorPosiciones);
                    $vectorPosiciones=[];
                }
                $controlVector=$controlVector+1;
            }
        }
        $this->linea=str_replace("$","",$this->linea);
        $this->linea=utf8_decode(utf8_encode($this->linea));
    }

    private function modificarLinea(Array $vectorPosiciones)
    {
        for($i=0; $i<count($vectorPosiciones);$i++)
        {
            $palabra=substr($this->linea,$vectorPosiciones[$i][0],($vectorPosiciones[$i][1]-$vectorPosiciones[$i][0]));
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("\"","",$palabra);
            $lineaAux1=substr($this->linea,0,$vectorPosiciones[$i][0]);
            $lineaAux2=substr($this->linea,$vectorPosiciones[$i][1]+1,strlen($this->linea));
            $this->linea=$lineaAux1.$palabra.$lineaAux2;
        }
    }

    public function crearMapaSCL()
    {
        $palabra="";
        $contadorPuntoYcoma=0;
        $this->linea=$this->linea."|";
        for($i=0; $i<strlen($this->linea); $i++)
        {
            if($this->linea[$i]!="|")
            {
                $palabra=$palabra.$this->linea[$i];
            }
            else
            {
                $contadorPuntoYcoma=$contadorPuntoYcoma+1;
                $palabra=str_replace("  "," ",$palabra);
                $palabra=trim($palabra);
                switch ($contadorPuntoYcoma)
                {
                    case 1:
                        $idCliente=array(DataGeneral::CLIENTE_UNICO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$idCliente);
                        break;
                    case 2:
                        $nombreCliente=array(DataGeneral::NOMBRE_DEL_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$nombreCliente);
                        break;
                    case 3:
                        $cuadrante=array(DataGeneral::CUADRANTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$cuadrante);
                        break;
                    case 4:
                        $zonaGeo=array(DataGeneral::ZONA_GEO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$zonaGeo);
                        break;
                    case 5:
                        $rfcCliente=array(DataGeneral::RFC_DEL_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$rfcCliente);
                        break;
                    case 6:
                        //ID DESPACHO
                        //$telefono=array(DataGeneral::TELEFONO=>$palabra);
                        //$this->mapa=array_merge($this->mapa,$telefono);
                        break;
                    case 7:
                        $domicilioCliente=array(DataGeneral::DIRECCION_DOMICILIO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$domicilioCliente);
                        break;
                    case 8:
                        $numExteriorCliente=array(DataGeneral::NUM_EXTERIOR_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numExteriorCliente);
                        break;
                    case 9:
                        $numInteriorCliente=array(DataGeneral::NUM_INTERIOR_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numInteriorCliente);
                        break;
                    case 10:
                        $cpCliente=array(DataGeneral::CP_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$cpCliente);
                        break;
                    case 11:
                        $coloniaCliente=array(DataGeneral::COLONIA_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$coloniaCliente);
                        break;
                    case 12:
                        $poblacionCliente=array(DataGeneral::POBLACION_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$poblacionCliente);
                        break;
                    case 13:
                        $estadoCliente=array(DataGeneral::ESTADO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$estadoCliente);
                        break;
                    case 14:
                        $clasificacion=array(DataGeneral::CLASIFICACION_DEL_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$clasificacion);
                        break;
                    case 15:
                        $atrasoMax=array(DataGeneral::ATRASO_MAXIMO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$atrasoMax);
                        break;
                    case 16:
                        $saldo=array(DataGeneral::SALDO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$saldo);
                        break;
                    case 17:
                        $moratorio=array(DataGeneral::MORATORIOS=>$palabra);
                        $this->mapa=array_merge($this->mapa,$moratorio);
                        break;
                    case 18:
                        $saldoTotal=array(DataGeneral::SALDO_TOTAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$saldoTotal);
                        break;
                    case 19:
                        $diaPago=array(DataGeneral::DIA_DE_PAGO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$diaPago);
                        break;
                    case 20:
                        $fechaUltimoPago=array(DataGeneral::FECHA_ULTIMO_PAGO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$fechaUltimoPago);
                        break;
                    case 21:
                        $importeUltimoPago=array(DataGeneral::IMPORTE_ULTIMO_PAGO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$importeUltimoPago);
                        break;
                    case 22:
                        $direccionEmpleoCliente=array(DataGeneral::DIRECCION_EMPLEO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$direccionEmpleoCliente);
                        //$telefonoTrabajo=array(DataGeneral::TELEFONO_TRABAJO=>$palabra);
                        //$this->mapa=array_merge($this->mapa,$telefonoTrabajo);
                        break;
                    case 23:
                        $numExteriorEmpleoCliente=array(DataGeneral::NUM_EXTERIOR_EMPLEO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numExteriorEmpleoCliente);
                        break;
                    case 24:
                        $numInteriorEmpleoCliente=array(DataGeneral::NUM_INTERIOR_EMPLEO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numInteriorEmpleoCliente);
                        break;
                    case 25:
                        $coloniaEmpleoCliente=array(DataGeneral::COLONIA_EMPLEO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$coloniaEmpleoCliente);
                        break;
                    case 26:
                        $poblacionClienteEmpleo=array(DataGeneral::POBLACION_CLIENTE_EMPLEO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$poblacionClienteEmpleo);
                        break;
                    case 27:
                        $estadoEmpleoCliente=array(DataGeneral::ESTADO_EMPLEO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$estadoEmpleoCliente);
                        break;
                    case 28:
                        $nombreAval=array(DataGeneral::NOMBRE_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$nombreAval);
                        break;
                    case 29:
                        $telefonoAval=array(DataGeneral::TELEFONO_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$telefonoAval);
                        break;
                    case 30:
                        $direccionAval=array(DataGeneral::DIRECCION_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$direccionAval);
                        break;
                    case 31:
                        $numExterioAval=array(DataGeneral::NUM_EXTERIOR_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numExterioAval);
                        break;
                    case 32:
                        $numColoniaAval=array(DataGeneral::NUM_COLONIA_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numColoniaAval);
                        break;
                    case 33:
                        $cpAval=array(DataGeneral::CP_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$cpAval);
                        break;
                    case 34:
                        $poblacionAval=array(DataGeneral::POBLACION_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$poblacionAval);
                        break;
                    case 35:
                        $estadoAval=array(DataGeneral::ESTADO_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$estadoAval);
                        break;
                    //case 36:
                    //Día de pago repetido
                    //  break;
                    case 37:
                        $tel1=array(DataGeneral::TELEFONO_1=>$palabra);
                        $this->mapa=array_merge($this->mapa,$tel1);

                        break;
                    /*case 38:
                        $telefonoCelular=array(DataGeneral::TELEFONO_CELULAR=>$palabra);
                        $this->mapa=array_merge($this->mapa,$telefonoCelular);
                        break;*/
                    case 39:
                        $tel2=array(DataGeneral::TELEFONO_2=>$palabra);
                        $this->mapa=array_merge($this->mapa,$tel2);
                        break;
                    //case 40:break;
                    case 41:
                        $tel3=array(DataGeneral::TELEFONO_3=>$palabra);
                        $this->mapa=array_merge($this->mapa,$tel3);
                        break;
                    case 43:
                        $tel4=array(DataGeneral::TELEFONO_4=>$palabra);
                        $this->mapa=array_merge($this->mapa,$tel4);
                        break;
                    case 49:
                        $encargado=array(DataGeneral::ENCARGADO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$encargado);
                        break;
                }
                $palabra="";
            }
        }
    }

    public function crearMapaCYBER()
    {
        $palabra="";
        $contadorPuntoYcoma=0;
        $this->linea=$this->linea."|";
        for($i=0; $i<strlen($this->linea); $i++)
        {

            if($this->linea[$i]!="|")
            {
                $palabra=$palabra.$this->linea[$i];
            }
            else
            {
                $contadorPuntoYcoma=$contadorPuntoYcoma+1;
                $palabra=str_replace("  "," ",$palabra);
                $palabra=trim($palabra);
                switch ($contadorPuntoYcoma)
                {
                    case 1:
                        $idCliente=array(DataGeneral::CLIENTE_UNICO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$idCliente);
                        break;
                    case 2:
                        $nombreCliente=array(DataGeneral::NOMBRE_DEL_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$nombreCliente);
                        break;
                    case 3:
                        $cuadrante=array(DataGeneral::CUADRANTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$cuadrante);
                        break;
                    case 4:
                        $zonaGeo=array(DataGeneral::ZONA_GEO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$zonaGeo);
                        break;
                    case 5:
                        $rfcCliente=array(DataGeneral::RFC_DEL_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$rfcCliente);
                        break;
                    case 6:
                        //ID DESPACHO
                        //$telefono=array(DataGeneral::TELEFONO=>$palabra);
                        //$this->mapa=array_merge($this->mapa,$telefono);
                        $domicilioCliente=array(DataGeneral::DIRECCION_DOMICILIO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$domicilioCliente);
                        break;
                    case 7:
                        $numExteriorCliente=array(DataGeneral::NUM_EXTERIOR_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numExteriorCliente);
                        break;
                    case 8:
                        $numInteriorCliente=array(DataGeneral::NUM_INTERIOR_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numInteriorCliente);
                        break;
                    case 9:
                        $cpCliente=array(DataGeneral::CP_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$cpCliente);
                        break;
                    case 10:
                        $coloniaCliente=array(DataGeneral::COLONIA_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$coloniaCliente);
                        break;
                    case 11:
                        $poblacionCliente=array(DataGeneral::POBLACION_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$poblacionCliente);
                        break;
                    case 12:
                        $estadoCliente=array(DataGeneral::ESTADO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$estadoCliente);
                        break;
                    /*case 13:
                        Es referencia de domicilio
                        $estadoCliente=array(DataGeneral::ESTADO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$estadoCliente);
                        break;*/
                    case 14:
                        $clasificacion=array(DataGeneral::CLASIFICACION_DEL_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$clasificacion);
                        break;
                    case 15:
                        $atrasoMax=array(DataGeneral::ATRASO_MAXIMO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$atrasoMax);
                        break;
                    /*case 16:
                    Días de atraso
                        $saldo=array(DataGeneral::SALDO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$saldo);
                        break*/
                    /*case 17:
                        saldo capital
                        break;*/
                    case 18:
                        $saldo=array(DataGeneral::SALDO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$saldo);
                        break;
                    case 19:
                        $moratorio=array(DataGeneral::MORATORIOS=>$palabra);
                        $this->mapa=array_merge($this->mapa,$moratorio);
                        break;
                    case 20:
                        $saldoTotal=array(DataGeneral::SALDO_TOTAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$saldoTotal);
                        break;
                    case 21:
                        $diaPago=array(DataGeneral::DIA_DE_PAGO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$diaPago);
                        break;
                    case 22:
                        $fechaUltimoPago=array(DataGeneral::FECHA_ULTIMO_PAGO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$fechaUltimoPago);
                        //$telefonoTrabajo=array(DataGeneral::TELEFONO_TRABAJO=>$palabra);
                        //$this->mapa=array_merge($this->mapa,$telefonoTrabajo);
                        break;
                    case 23:
                        $importeUltimoPago=array(DataGeneral::IMPORTE_ULTIMO_PAGO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$importeUltimoPago);
                        break;
                    case 24:
                        $direccionEmpleoCliente=array(DataGeneral::DIRECCION_EMPLEO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$direccionEmpleoCliente);
                        break;
                    case 25:
                        $numExteriorEmpleoCliente=array(DataGeneral::NUM_EXTERIOR_EMPLEO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numExteriorEmpleoCliente);
                        break;
                    case 26:
                        $numInteriorEmpleoCliente=array(DataGeneral::NUM_INTERIOR_EMPLEO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numInteriorEmpleoCliente);
                        break;
                    case 27:
                        $coloniaEmpleoCliente=array(DataGeneral::COLONIA_EMPLEO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$coloniaEmpleoCliente);
                        break;
                    case 28:
                        $poblacionClienteEmpleo=array(DataGeneral::POBLACION_CLIENTE_EMPLEO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$poblacionClienteEmpleo);
                        break;
                    case 29:
                        $estadoEmpleoCliente=array(DataGeneral::ESTADO_EMPLEO_CLIENTE=>$palabra);
                        $this->mapa=array_merge($this->mapa,$estadoEmpleoCliente);
                        break;
                    case 30:
                        $nombreAval=array(DataGeneral::NOMBRE_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$nombreAval);
                        break;
                    case 31:
                        $telefonoAval=array(DataGeneral::TELEFONO_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$telefonoAval);
                        break;
                    case 32:
                        $direccionAval=array(DataGeneral::DIRECCION_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$direccionAval);
                        break;
                    case 33:
                        $numExterioAval=array(DataGeneral::NUM_EXTERIOR_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numExterioAval);
                        break;
                    case 34:
                        $numInteriorAval=array(DataGeneral::NUM_INTERIOR_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numInteriorAval);
                        break;
                    case 35:
                        $numColoniaAval=array(DataGeneral::NUM_COLONIA_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$numColoniaAval);
                        break;
                    case 36:
                        $cpAval=array(DataGeneral::CP_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$cpAval);
                        break;
                    case 37:
                        $poblacionAval=array(DataGeneral::POBLACION_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$poblacionAval);
                        break;
                    case 38:
                        $estadoAval=array(DataGeneral::ESTADO_DEL_AVAL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$estadoAval);
                        /*$telefonoCelular=array(DataGeneral::TELEFONO_CELULAR=>$palabra);
                        $this->mapa=array_merge($this->mapa,$telefonoCelular);*/
                        break;
                    case 39:
                        $tel1=array(DataGeneral::TELEFONO_1=>$palabra);
                        $this->mapa=array_merge($this->mapa,$tel1);
                        break;
                    case 40:
                        $tel2=array(DataGeneral::TELEFONO_2=>$palabra);
                        $this->mapa=array_merge($this->mapa,$tel2);
                        break;
                    case 41:
                        $tel3=array(DataGeneral::TELEFONO_3=>$palabra);
                        $this->mapa=array_merge($this->mapa,$tel3);
                        break;
                    case 42:
                        $tel4=array(DataGeneral::TELEFONO_4=>$palabra);
                        $this->mapa=array_merge($this->mapa,$tel4);
                        break;
                    case 43:
                        $gerencia=array(DataGeneral::GERENCIA=>$palabra);
                        $this->mapa=array_merge($this->mapa,$gerencia);
                        break;
                    case 54:
                        $idGrupo=array(DataGeneral::ID_GRUPO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$idGrupo);
                        break;
                    case 55:
                        $nombreGrupo=array(DataGeneral::NOMBRE_GRUPO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$nombreGrupo);
                        break;
                    /*
                case 43:
                    break;*/
                }
                $palabra="";
            }
        }
    }
    public function crearMapaManual()
    {
        $palabra="";
        $contadorPuntoYcoma=0;
        $this->linea=$this->linea."|";
        for($i=0; $i<strlen($this->linea); $i++)
        {
            if($this->linea[$i]!="|")
            {
                $palabra=$palabra.$this->linea[$i];
            }
            else
            {
                $contadorPuntoYcoma=$contadorPuntoYcoma+1;
                $palabra=str_replace("  "," ",$palabra);
                $palabra=trim($palabra);
                switch ($contadorPuntoYcoma)
                {
                    case 1:
                        $idCliente=array(DataGeneral::CLIENTE_UNICO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$idCliente);
                        break;
                    case 2:
                        $codigoAccion=array(DataGeneral::CODIGO_ACCION=>$palabra);
                        $this->mapa=array_merge($this->mapa,$codigoAccion);
                        break;
                    case 3:
                        $codigoResultado=array(DataGeneral::CODIGO_RESULTADO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$codigoResultado);
                        break;
                    case 4:
                        $idScl=array(DataGeneral::ID_SCL=>$palabra);
                        $this->mapa=array_merge($this->mapa,$idScl);
                        break;
                    case 5:
                        $comentario=array(DataGeneral::COMENTARIO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$comentario);
                        break;
                }
                $palabra="";
            }
        }
    }
    public function crearMapaAutomatico()
    {
        $palabra="";
        $contadorPuntoYcoma=0;
        $this->linea=$this->linea."|";
        for($i=0; $i<strlen($this->linea); $i++)
        {
            if($this->linea[$i]!="|")
            {
                $palabra=$palabra.$this->linea[$i];
            }
            else
            {
                $contadorPuntoYcoma=$contadorPuntoYcoma+1;
                $palabra=str_replace("  "," ",$palabra);
                $palabra=trim($palabra);
                switch ($contadorPuntoYcoma)
                {
                    case 2:
                        $estadoLLamada=array(DataGeneral::ESTADO_LLAMADA=>$palabra);
                        $this->mapa=array_merge($this->mapa,$estadoLLamada);
                        break;
                    case 9:
                        $idCliente=array(DataGeneral::CLIENTE_UNICO=>$palabra);
                        $this->mapa=array_merge($this->mapa,$idCliente);
                        break;
                }
                $palabra="";
            }
        }
        $idScl=array(DataGeneral::ID_SCL=>"9");
        $this->mapa=array_merge($this->mapa,$idScl);
    }
}
