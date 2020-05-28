<?php

namespace App;

use http\Exception;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\VarDumper\Cloner\Data;

class ProcesadorData extends Model
{
    private $archivo;
    const TIPOSLC=1;
    const TIPOSCYBER=2;
    private $tipoArchivo;
    private $formatoArchivo;
    private $mapa;
    private $idUsuario;
    private $todoSeInserto;
    private $direccionesCasa;
    private $direccionesAval;
    private $direccionesTrabajo;
    private $listaPagos;
    private $todosAvales;

    /**
     * ProcesadorData constructor.
     * @param $archivo
     * @param $formatoArchivo
     * @param $idUsuario
     */
    public function __construct($archivo=null,$formatoArchivo=null,$idUsuario=null)
    {
        if($archivo!=null)
        {
            $this->formatoArchivo=$formatoArchivo;
            $this->archivo=$archivo;
            $this->tipoArchivo=$this->obtenerExtension($archivo);
            $this->idUsuario=$idUsuario;
        }
    }

    /**
     * @return mixed
     */
    public function getTodoSeInserto()
    {
        return $this->todoSeInserto;
    }

    /**
     * @param mixed $todoSeInserto
     */
    public function setTodoSeInserto($todoSeInserto): void
    {
        $this->todoSeInserto = $todoSeInserto;
    }

    /**
     * @return mixed
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * @param mixed $archivo
     */
    public function setArchivo($archivo): void
    {
        $this->archivo = $archivo;
    }

    /**
     * @return mixed
     */
    public function getTipoArchivo()
    {
        return $this->tipoArchivo;
    }

    /**
     * @param mixed $tipoArchivo
     */
    public function setTipoArchivo($tipoArchivo): void
    {
        $this->tipoArchivo = $tipoArchivo;
    }

    /**
     * @return mixed
     */
    public function getFormatoArchivo()
    {
        return $this->formatoArchivo;
    }

    /**
     * @param mixed $formatoArchivo
     */
    public function setFormatoArchivo($formatoArchivo): void
    {
        $this->formatoArchivo = $formatoArchivo;
    }

    /**
     * @return mixed
     */
    public function getMapa()
    {
        return $this->mapa;
    }

    /**
     * @param mixed $mapa
     */
    public function setMapa($mapa): void
    {
        $this->mapa = $mapa;
    }

    private function obtenerExtension(String $cadena): string
    {
        $auxDato=substr($cadena,strpos($cadena,".")+1);
        $auxDato1=strtoupper(substr($auxDato,0,1));
        $auxDato=substr($auxDato,1);
        return $auxDato1.$auxDato;
    }

    private function insertarDatos():void
    {
        if(($this->formatoArchivo==DataGeneral::TIPOSLC) || ($this->formatoArchivo==DataGeneral::TIPOSCYBER) || ($this->formatoArchivo==DataGeneral::PASAR_BASE))
        {
            if(!DataGeneral::PASAR_BASE)
            {
                $this->insertarDatosCliente();
            }
            else
            {
                if(ConsultaSQL::getInstance()->existeCliente($this->mapa[DataGeneral::CLIENTE_UNICO]))
                {
                    if(strlen($this->mapa[DataGeneral::NOMBRE_DEL_CLIENTE])>0)
                    {
                        //TODO, cambio en línea 145 ProcesadorData
                        //todo, se crear método en consultaSQL
                        $zonaGeo=str_replace("''","",$this->mapa[DataGeneral::ZONA_GEO]);
                        $cuadrante=str_replace("''","",$this->mapa[DataGeneral::CUADRANTE]);
                        if($zonaGeo!="" && $cuadrante!="")
                        {
                            //ConsultaSQL::getInstance()->actualizarCliente($zonaGeo,$cuadrante,$this->mapa[DataGeneral::CLIENTE_UNICO]);
                            ConsultaSQL::getInstance()->actualizarDireccionU($this->mapa[DataGeneral::CLIENTE_UNICO]);
                        }
                        //else
                        {
                            //todo en servidor
                            //ConsultaSQL::getInstance()->actualizarCliente(null,null,$this->mapa[DataGeneral::CLIENTE_UNICO]);
                        }
                        /*
                         $str = $this->mapa[DataGeneral::NOMBRE_DEL_CLIENTE];
                        $tel = (int) filter_var($str, FILTER_SANITIZE_NUMBER_INT);
                        if($tel!=0)
                        {
                            $idTelC=ConsultaSQL::getInstance()->existeTelefonoIDCliente($tel);
                            if($idTelC==-1)
                            {
                                $idTel=ConsultaSQL::getInstance()->insertaTelefonoCliente($tel);
                                ConsultaSQL::getInstance()->insertaRelacionClienteTelefono($this->mapa[DataGeneral::CLIENTE_UNICO],$idTel);
                            }
                        }*/
                    }
                }
                else
                {
                    //$this->insertarDatosCliente();
                }
            }
        }
        else
        {

             if(ConsultaSQL::getInstance()->existeCliente($this->mapa[DataGeneral::CLIENTE_UNICO]))
            {
                $gestion=new GestionModel($this->mapa,$this->idUsuario);
                $gestion->guardar();
            }
        }
    }

    private function insertarDatosCliente()
    {
        /**Insertar cliente***/
        $this->insertarCliente();
        /**Direccion del cliente***/
        //revisar si existe el cliente en la tabla de dirección y tipo casa si existe entonces actualizar sino insertar
        //$idDireccion=ConsultaSQL::getInstance()->existeDireccionDeCliente($this->mapa[DataGeneral::CLIENTE_UNICO],"casa");
        //dd($this->direccionesCasa["1-1-4983-46621"][0]["id_direccion"]);
        if(isset($this->direccionesCasa[$this->mapa[DataGeneral::CLIENTE_UNICO]]))
        {
            $idDireccion=$this->direccionesCasa[DataGeneral::CLIENTE_UNICO][0]["id_direccion"];
            $this->actualizarDireccion($this->mapa[DataGeneral::CLIENTE_UNICO],$this->mapa[DataGeneral::CUADRANTE],
            $this->mapa[DataGeneral::ZONA_GEO],$this->mapa[DataGeneral::DIRECCION_DOMICILIO_CLIENTE],
            $this->mapa[DataGeneral::NUM_EXTERIOR_CLIENTE],$this->mapa[DataGeneral::NUM_INTERIOR_CLIENTE],
            DataGeneral::DIRECCION_TIPO_CASA, $this->mapa[DataGeneral::CP_CLIENTE],
            $this->mapa[DataGeneral::COLONIA_CLIENTE],$this->mapa[DataGeneral::POBLACION_CLIENTE],$this->mapa[DataGeneral::ESTADO_CLIENTE],$idDireccion);
        }
        else //insertar
        {
            $this->insertarDireccion($this->mapa[DataGeneral::CLIENTE_UNICO],$this->mapa[DataGeneral::CUADRANTE],
                $this->mapa[DataGeneral::ZONA_GEO],$this->mapa[DataGeneral::DIRECCION_DOMICILIO_CLIENTE],
                $this->mapa[DataGeneral::NUM_EXTERIOR_CLIENTE],$this->mapa[DataGeneral::NUM_INTERIOR_CLIENTE],
                DataGeneral::DIRECCION_TIPO_CASA, $this->mapa[DataGeneral::CP_CLIENTE],
                $this->mapa[DataGeneral::COLONIA_CLIENTE],$this->mapa[DataGeneral::POBLACION_CLIENTE],$this->mapa[DataGeneral::ESTADO_CLIENTE]);
        }
        if(isset($this->direccionesTrabajo[$this->mapa[DataGeneral::CLIENTE_UNICO]]))
        {
            $idDireccion=$this->direccionesTrabajo[DataGeneral::CLIENTE_UNICO][0]["id_direccion"];
            $this->actualizarEmpleo($idDireccion);
        }
        else
        {
            /**Insertar trabajo**/
            $this->insertarEmpleo();
        }
        if(isset($this->listaPagos[$this->mapa[DataGeneral::CLIENTE_UNICO]]))
        {
            $this->actualizarPago();
        }
        else
        {
            /**Insertar pago***/
            $this->insertarPago();
        }
        /**Insertar Aval del cliente***/
        //obtener id aval
        if(isset($this->todosAvales[$this->mapa[DataGeneral::CLIENTE_UNICO]]))
        {
            $idAval=$this->todosAvales[DataGeneral::CLIENTE_UNICO][0]["id_aval"];
            ConsultaSQL::getInstance()->actualizaAval($this->mapa[DataGeneral::NOMBRE_DEL_AVAL],$idAval);
            $idDireccionAval=ConsultaSQL::getInstance()->obtenerIdDireccionPorAval($idAval);
            if($this->mapa[DataGeneral::DIRECCION_DEL_AVAL]!="SIN INFORMACION" && $this->mapa[DataGeneral::DIRECCION_DEL_AVAL]!="")
            {
                $this->actualizarDireccion($this->mapa[DataGeneral::CLIENTE_UNICO],"","",$this->mapa[DataGeneral::DIRECCION_DEL_AVAL]
                    ,$this->mapa[DataGeneral::NUM_EXTERIOR_DEL_AVAL], "", DataGeneral::DIRECCION_TIPO_AVAL,
                    $this->mapa[DataGeneral::CP_DEL_AVAL], $this->mapa[DataGeneral::NUM_COLONIA_DEL_AVAL],
                    $this->mapa[DataGeneral::POBLACION_DEL_AVAL], $this->mapa[DataGeneral::ESTADO_DEL_AVAL],$idDireccionAval);
            }
        }
        else
        {
            $this->insertarAval();
        }
        /**Insertar Telefonos del cliente***/
        $this->insertaTelefonosCliente();
    }


    private function insertarCliente() : void
    {
        /**Cliente***/
        $cliente =new ClienteModel(
            isset($this->mapa[DataGeneral::CLIENTE_UNICO]) ? $this->mapa[DataGeneral::CLIENTE_UNICO]:null,
            isset($this->mapa[DataGeneral::NOMBRE_DEL_CLIENTE])? $this->mapa[DataGeneral::NOMBRE_DEL_CLIENTE]: null,
            isset($this->mapa[DataGeneral::RFC_DEL_CLIENTE]) ? $this->mapa[DataGeneral::RFC_DEL_CLIENTE]: null,
            isset($this->mapa[DataGeneral::GERENCIA]) ? $this->mapa[DataGeneral::GERENCIA] : "",
            isset($this->mapa[DataGeneral::ENCARGADO]) ? $this->mapa[DataGeneral::ENCARGADO] : "",
            isset($this->mapa[DataGeneral::ID_GRUPO]) ? $this->mapa[DataGeneral::ID_GRUPO] : "",
            isset($this->mapa[DataGeneral::NOMBRE_GRUPO]) ? $this->mapa[DataGeneral::NOMBRE_GRUPO] : "");
        $cliente->guardar($this->formatoArchivo);
    }

    private function insertarDireccion($idCliente,$cuadrante,$zonaGeo,$direccionDomicilio,$numExt,$numInt,$tipoDireccion,$cp,$colonia,$poblacion,$estado) : int
    {
        $direccion=new DireccionModel($idCliente,$cuadrante,$zonaGeo,$direccionDomicilio,$numExt,$numInt,$tipoDireccion,$cp,$colonia,$poblacion,$estado);
        return $direccion->guardar();
    }

    private function actualizarDireccion($idCliente,$cuadrante,$zonaGeo,$direccionDomicilio,$numExt,$numInt,$tipoDireccion,$cp,$colonia,$poblacion,$estado,$idDireccion)
    {
        $direccion=new DireccionModel($idCliente,$cuadrante,$zonaGeo,$direccionDomicilio,$numExt,$numInt,$tipoDireccion,$cp,$colonia,$poblacion,$estado);
        $direccion->actualizar($idDireccion);
    }

    private function insertarEmpleo()
    {
        $calleEmpleo=$this->mapa[DataGeneral::DIRECCION_EMPLEO_CLIENTE];
        $numExt=$this->mapa[DataGeneral::NUM_EXTERIOR_EMPLEO_CLIENTE];
        $numInt=$this->mapa[DataGeneral::NUM_INTERIOR_EMPLEO_CLIENTE];
        $colonia=$this->mapa[DataGeneral::COLONIA_EMPLEO_CLIENTE];
        $poblacion=$this->mapa[DataGeneral::POBLACION_CLIENTE_EMPLEO];
        $estado=$this->mapa[DataGeneral::ESTADO_EMPLEO_CLIENTE];
        if($calleEmpleo!="SIN CALLE EMPLEO" || $numExt!="S/N" || $numInt!="S/N" || $colonia!="SIN COLONIA EMPLEO"
            || $poblacion!="SIN POBLACION" || $estado!="SIN ESTADO EMPLEO")
        {
            $id_dir=$this->insertarDireccion($this->mapa[DataGeneral::CLIENTE_UNICO],"","",$calleEmpleo,$numExt,$numInt,
                DataGeneral::DIRECCION_TIPO_TRABAJO,"",$colonia,$poblacion,$estado);

            $trabajo=new TrabajoModel("",$id_dir,$this->mapa[DataGeneral::CLIENTE_UNICO]);
            $trabajo->guardar();
        }
    }

    private function actualizarEmpleo($idDireccion)
    {
        $calleEmpleo=$this->mapa[DataGeneral::DIRECCION_EMPLEO_CLIENTE];
        $numExt=$this->mapa[DataGeneral::NUM_EXTERIOR_EMPLEO_CLIENTE];
        $numInt=$this->mapa[DataGeneral::NUM_INTERIOR_EMPLEO_CLIENTE];
        $colonia=$this->mapa[DataGeneral::COLONIA_EMPLEO_CLIENTE];
        $poblacion=$this->mapa[DataGeneral::POBLACION_CLIENTE_EMPLEO];
        $estado=$this->mapa[DataGeneral::ESTADO_EMPLEO_CLIENTE];
        if($calleEmpleo!="SIN CALLE EMPLEO" || $numExt!="S/N" || $numInt!="S/N" || $colonia!="SIN COLONIA EMPLEO"
            || $poblacion!="SIN POBLACION" || $estado!="SIN ESTADO EMPLEO")
        {
            //$idCliente,$cuadrante,$zonaGeo,$direccionDomicilio,$numExt,$numInt,$tipoDireccion,$cp,$colonia,$poblacion,$estado,$idDireccion
            $this->actualizarDireccion($this->mapa[DataGeneral::CLIENTE_UNICO],"","",$calleEmpleo,$numExt,$numInt,
                DataGeneral::DIRECCION_TIPO_TRABAJO,"",$colonia,$poblacion,$estado,$idDireccion);
        }
    }




    private function insertaTelefonosCliente()
    {
        $telefono=new TelefonoCliente($this->mapa[DataGeneral::TELEFONO_1],$this->mapa[DataGeneral::TELEFONO_2],$this->mapa[DataGeneral::TELEFONO_3],
            $this->mapa[DataGeneral::TELEFONO_4],$this->mapa[DataGeneral::CLIENTE_UNICO]);
        $telefono->guardar();
    }


    private function insertarPago():void
    {
        $pago=new PagoModel($this->mapa[DataGeneral::CLIENTE_UNICO],$this->mapa[DataGeneral::CLASIFICACION_DEL_CLIENTE],$this->mapa[DataGeneral::ATRASO_MAXIMO],$this->mapa[DataGeneral::SALDO],
            $this->mapa[DataGeneral::MORATORIOS],$this->mapa[DataGeneral::SALDO_TOTAL],$this->mapa[DataGeneral::DIA_DE_PAGO],$this->mapa[DataGeneral::FECHA_ULTIMO_PAGO],$this->mapa[DataGeneral::IMPORTE_ULTIMO_PAGO]);
        $pago->guardar();
    }

    private function actualizarPago()
    {
        $pago=new PagoModel($this->mapa[DataGeneral::CLIENTE_UNICO],$this->mapa[DataGeneral::CLASIFICACION_DEL_CLIENTE],$this->mapa[DataGeneral::ATRASO_MAXIMO],$this->mapa[DataGeneral::SALDO],
            $this->mapa[DataGeneral::MORATORIOS],$this->mapa[DataGeneral::SALDO_TOTAL],$this->mapa[DataGeneral::DIA_DE_PAGO],$this->mapa[DataGeneral::FECHA_ULTIMO_PAGO],$this->mapa[DataGeneral::IMPORTE_ULTIMO_PAGO]);
        $pago->actualizar();
    }


    private function insertarAval():void
    {
        if($this->mapa[DataGeneral::NOMBRE_DEL_AVAL]!="SIN AVAL" && $this->mapa[DataGeneral::NOMBRE_DEL_AVAL]!="SIN INFORMACION" && $this->mapa[DataGeneral::NOMBRE_DEL_AVAL]!="")
        {
            $id_Dir=null;
            /**Insertar direccion del Aval***/
            if($this->mapa[DataGeneral::DIRECCION_DEL_AVAL]!="SIN INFORMACION" && $this->mapa[DataGeneral::DIRECCION_DEL_AVAL]!="")
            {
                $id_Dir=$this->insertarDireccion($this->mapa[DataGeneral::CLIENTE_UNICO],"","",$this->mapa[DataGeneral::DIRECCION_DEL_AVAL]
                    ,$this->mapa[DataGeneral::NUM_EXTERIOR_DEL_AVAL], "", DataGeneral::DIRECCION_TIPO_AVAL,
                    $this->mapa[DataGeneral::CP_DEL_AVAL], $this->mapa[DataGeneral::NUM_COLONIA_DEL_AVAL],
                    $this->mapa[DataGeneral::POBLACION_DEL_AVAL], $this->mapa[DataGeneral::ESTADO_DEL_AVAL]);
            }
            /**Insertar Aval***/
            $aval=new AvalModel($this->mapa[DataGeneral::NOMBRE_DEL_AVAL],$id_Dir,$this->mapa[DataGeneral::CLIENTE_UNICO],$this->mapa[DataGeneral::TELEFONO_DEL_AVAL]);
            $aval->guardar();
        }
    }

    public function insertaGestionesdeBDvieja($bdHija)
    {
        //permitr group by
        ConsultaSQL::getInstance()->permitirGoupbyTodo();
        //consultar encargado
        $listaEncargados=ConsultaSQL::getInstance()->obtenerEncargados();
        //insertar encargado
        ConsultaSQL::getInstance()->insertaEncargado($listaEncargados);

        //insertar gestores
        //$listaGestores=ConsultaSQL::getInstance()->obtenerGestores();
        //ConsultaSQL::getInstance()->insertarGestores($bdHija,$listaGestores);

        //deshabiltiar triggers
        ConsultaSQL::getInstance()->deshabilitarTriggers();
        //obtener array asociativo de gestores
        $listaGestoresAux=ConsultaSQL::getInstance()->consultarListaIDGestor($bdHija);
        //insertar gestiones
        $this->insertarGestionesConveniodBDVieja($listaGestoresAux);
        ConsultaSQL::getInstance()->habilitarTriggers();
    }

    private function insertarGestionesConveniodBDVieja($listaGestoresAux)
    {
        $listaGestiones=ConsultaSQL::getInstance()->consultaGestionesCompletas();

        $tam=count($listaGestiones);
        for($i=0; $i<$tam; $i++)
        {
            if(!empty($listaGestiones[$i]["gestor"]))
            {
                $idGestor=$this->obtenerIdGestor($listaGestoresAux,$listaGestiones[$i]["gestor"]);
                if($idGestor!=-1)
                {
                    if(ConsultaSQL::getInstance()->existeGestion($listaGestiones[$i]["folio_gestion"])>0)
                    {
                        $idConvenio=ConsultaSQL::getInstance()->obtenerIdConvenio($listaGestiones[$i]["folio_convenio"]);
                        if(count($idConvenio)>0)
                        {
                            ConsultaSQL::getInstance()->insertarCalendarioPagos($listaGestiones[$i],$idConvenio);
                            //consulta calendariPagos donde el idConvenio = folio y pagado = falso -> si >2 false
                            //actuailizar convenio y actualizar calendario pagos donde idConvenio set falso pago
                            $fecha=date('Y-m-d H:i:s', strtotime("now"));

                            if(strtotime($listaGestiones[$i]["fecha_entrega"])<strtotime($fecha))
                            {
                                if(ConsultaSQL::getInstance()->noHapagadoMasDedos($idConvenio[0]["id_convenio"])>1)
                                {
                                    ConsultaSQL::getInstance()->actualizaConvenio($idConvenio[0]["id_convenio"]);
                                    ConsultaSQL::getInstance()->actualizaCalendario($idConvenio[0]["id_convenio"]);
                                }
                            }
                        }
                    }
                    else
                    {
                        $id_gestion=ConsultaSQL::getInstance()->insertaGestionesBdVieja($listaGestiones[$i],$idGestor);
                        if($listaGestiones[$i]["convenio"]=="Si")
                        {
                            $idCliente=$listaGestiones[$i]["contrato"];
                            $idCliente=str_replace(" ","",$idCliente);
                            $idCliente=str_replace("*","",$idCliente);
                            ConsultaSQL::getInstance()->cancelarConvenio($idCliente);
                            ConsultaSQL::getInstance()->insertarConvenio($listaGestiones[$i],$id_gestion);
                            $idConvenio=ConsultaSQL::getInstance()->obtenerIdConvenio($listaGestiones[$i]["folio_convenio"]);
                            if(count($idConvenio)>0)
                            {
                                ConsultaSQL::getInstance()->insertarCalendarioPagos($listaGestiones[$i],$idConvenio);
                            }
                        }
                    }
                }
            }
        }
    }

    private function obtenerIdGestor($listaGestoresAux,$gestor) : int
    {
        $tam=count($listaGestoresAux);
        for($i=0; $i<$tam; $i++)
        {
            if(isset($listaGestoresAux[$i]["username"]))
            {
                if(strpos($listaGestoresAux[$i]["username"],$gestor)!==false)
                {
                    return $listaGestoresAux[$i]["id"];
                }
            }
        }
        return -1;
    }

    public function procesarDatos() : void
    {
        //ConexionSQL::getInstance()->getConexion()->beginTransaction();
        //ConexionSQL::getInstance()->setSeInsertoTodo(true);
        $this->direccionesCasa=ConsultaSQL::getInstance()->obtenerDireccionesPorTipo(DataGeneral::CASA);
        //dd($this->direccionesCasa["1-1-4983-46621"][0]["id_direccion"]);
        $this->direccionesAval=ConsultaSQL::getInstance()->obtenerDireccionesPorTipo(DataGeneral::AVAL);
        $this->direccionesTrabajo=ConsultaSQL::getInstance()->obtenerDireccionesPorTipo(DataGeneral::TRABAJO);
        $this->listaPagos=ConsultaSQL::getInstance()->obtenerPagos();
        $this->todosAvales=ConsultaSQL::getInstance()->obtenerRelacionClienteAval();

        if($this->formatoArchivo==DataGeneral::PASAR_BASE)
        {
            $datosBd=ConsultaSQL::getInstance()->consultaClientesBDMySQL();
            $tam=count($datosBd);
            for($i=0; $i<$tam; $i++)
            {
                $this->crearMapaBd($datosBd[$i]);
                $this->insertarDatos();
                //unset($datosBd[$i]);
            }
            dd("termino");
        }
        else
        {
            try
            {
                if($this->formatoArchivo==DataGeneral::TIPOSLC || $this->formatoArchivo==DataGeneral::TIPOSCYBER)
                {
                    if($this->tipoArchivo=="Xlsx")
                    {
                        $this->leerExcel(false);
                        $this->todoSeInserto=true;
                    }
                    if ($this->tipoArchivo=="Csv")
                    {
                        $this->leerArchivo(false);
                        $this->todoSeInserto=true;
                    }
                }
                else
                {
                    $this->leerExcel(false);
                    $this->todoSeInserto=true;
                    /*
                      if(ConexionSQL::getInstance()->getSeInsertoTodo())
                     {
                         $this->todoSeInserto=true;
                         ConexionSQL::getInstance()->getConexion()->commit();
                     }
                     else
                     {
                         $this->todoSeInserto=false;
                         ConexionSQL::getInstance()->getConexion()->rollBack();
                     }*/
                }
            }
            catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e)
            {
                echo "error en primer catch: ".$e;
            }
            catch (\PhpOffice\PhpSpreadsheet\Exception $e)
            {
                echo "error en segundo catch: ".$e;
            }
        }
    }

    private function crearMapaBd($datos)
    {
        $this->mapa=[];
        if(isset($datos["id"]))
        {
            $palabra=$datos["id"];
            $palabra=str_replace(" ","",$palabra);
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::CLIENTE_UNICO=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["nombre"]))
        {
            $palabra=$datos["nombre"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::NOMBRE_DEL_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["cuadrante"]))
        {
            $palabra=$datos["cuadrante"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::CUADRANTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["zona_geo"]))
        {
            $palabra=$datos["zona_geo"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::ZONA_GEO=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["rfc"]))
        {
            $palabra=$datos["rfc"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::RFC_DEL_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["direccion"]))
        {
            $palabra=$datos["direccion"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::DIRECCION_DOMICILIO_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["num_ext"]))
        {
            $palabra=$datos["num_ext"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::NUM_EXTERIOR_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["num_int"]))
        {
            $palabra=$datos["num_int"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::NUM_INTERIOR_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["cp"]))
        {
            $palabra=$datos["cp"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::CP_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["colonia"]))
        {
            $palabra=$datos["colonia"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::COLONIA_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["poblacion"]))
        {
            $palabra=$datos["poblacion"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::POBLACION_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["estado"]))
        {
            $palabra=$datos["estado"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::ESTADO_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["clasificacion"]))
        {
            $palabra=$datos["clasificacion"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::CLASIFICACION_DEL_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["atraso_max"]))
        {
            $palabra=$datos["atraso_max"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::ATRASO_MAXIMO=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["saldo"]))
        {
            $palabra=$datos["saldo"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::SALDO=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["moratorios"]))
        {
            $palabra=$datos["moratorios"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::MORATORIOS=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["total"]))
        {
            $palabra=$datos["total"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::SALDO_TOTAL=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["dia_de_pago"]))
        {
            $palabra=$datos["dia_de_pago"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::DIA_DE_PAGO=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["fecha_pago_ultimo"]))
        {
            $palabra=$datos["fecha_pago_ultimo"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::FECHA_ULTIMO_PAGO=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["importe_pago_ultimo"]))
        {
            $palabra=$datos["importe_pago_ultimo"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::IMPORTE_ULTIMO_PAGO=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["telefono_trabajo"]))
        {
            $palabra=$datos["telefono_trabajo"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::TELEFONO_TRABAJO=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["direccion_trabajo"]))
        {
            $palabra=$datos["direccion_trabajo"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::DIRECCION_EMPLEO_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["ext_trabajo"]))
        {
            $palabra=$datos["ext_trabajo"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::NUM_EXTERIOR_EMPLEO_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["int_trabajo"]))
        {
            $palabra=$datos["int_trabajo"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::NUM_INTERIOR_EMPLEO_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["colonia_trabajo"]))
        {
            $palabra=$datos["colonia_trabajo"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::COLONIA_EMPLEO_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["poblacion_trabajo"]))
        {
            $palabra=$datos["poblacion_trabajo"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::POBLACION_CLIENTE_EMPLEO=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["estado_trabajo"]))
        {
            $palabra=$datos["estado_trabajo"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::ESTADO_EMPLEO_CLIENTE=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["aval"]))
        {
            $palabra=$datos["aval"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::NOMBRE_DEL_AVAL=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["telefono_aval"]))
        {
            $palabra=$datos["telefono_aval"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::TELEFONO_DEL_AVAL=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["direccion_aval"]))
        {
            $palabra=$datos["direccion_aval"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::DIRECCION_DEL_AVAL=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["ext_aval"]))
        {
            $palabra=$datos["ext_aval"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::NUM_EXTERIOR_DEL_AVAL=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["colonial_aval"]))
        {
            $palabra=$datos["colonial_aval"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::NUM_COLONIA_DEL_AVAL=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["cp_aval"]))
        {
            $palabra=$datos["cp_aval"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::CP_DEL_AVAL=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["poblacion_aval"]))
        {
            $palabra=$datos["poblacion_aval"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::POBLACION_DEL_AVAL=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["estado_aval"]))
        {
            $palabra=$datos["estado_aval"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::ESTADO_DEL_AVAL=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["tel1"]))
        {
            $palabra=$datos["tel1"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::TELEFONO_1=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["tel2"]))
        {
            $palabra=$datos["tel2"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::TELEFONO_2=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["tel3"]))
        {
            $palabra=$datos["tel3"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::TELEFONO_3=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["tel4"]))
        {
            $palabra=$datos["tel4"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::TELEFONO_4=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
        if(isset($datos["gerencia"]))
        {
            $palabra=$datos["gerencia"];
            $palabra=str_replace(";","",$palabra);
            $palabra=str_replace("$","",$palabra);
            $palabra=utf8_decode($palabra);
            $arregloAux=array(DataGeneral::GERENCIA=>$palabra);
            $this->mapa=array_merge($this->mapa,$arregloAux);
        }
    }


    private function leerArchivo($soloCabecera)
    {
        $dato=new Linea();
        $fp=fopen($this->archivo,"r");
        $linea=fgets($fp); //Leer cabeceras
        if($soloCabecera)
        {
            $dato->setLinea($linea);
            $dato->limpiarLinea();
            $linea=$dato->getLinea();
            if(DataGeneral::TIPOSLC==$this->formatoArchivo)
            {
                $cadena="CLIENTE UNICO|NOMBRE DEL CLIENTE|CUADRANTE|ZONA GEO|RFC DEL CLIENTE|ID DESPACHO|DIRECCION DOMICILIO CLIENTE|# EXTERIOR CLIENTE|# INTERIOR CLIENTE|CP CLIENTE|COLONIA CLIENTE|POBLACION CLIENTE|ESTADO CLIENTE|CLASIFICACION DEL CLIENTE|ATRASO MAXIMO|SALDO|MORATORIOS|SALDO TOTAL|DIA DE PAGO|FECHA ULTIMO PAGO|IMPORTE ULTIMO PAGO|DIRECCION EMPLEO CLIENTE|# EXTERIOR EMPLEO CLIENTE|# INTERIOR EMPLEO CLIENTE|COLONIA EMPLEO CLIENTE|POBLACION CLIENTE|ESTADO EMPLEO CLIENTE|NOMBRE DEL AVAL|TELEFONO DEL AVAL|DIRECCION DEL AVAL|# EXTERIOR DEL AVAL|# COLONIA DEL AVAL|CP DEL AVAL|POBLACION DEL AVAL|ESTADO DEL AVAL|DIA DE PAGO|TELEFONO 1|TIPO TELEFONO|TELEFONO 2|TIPO TELEFONO|TELEFONO 3|TIPO TELEFONO|TELEFONO 4|TIPO TELEFONO|MIGRADO A CYBER|LATITUD|LONGITUD|GERENCIA|ENCARGADO|CLASIFICACION ESPECIAL";
                return $cadena==$linea;
            }
            else if(DataGeneral::TIPOSCYBER==$this->formatoArchivo)
            {
                $linea=str_replace(" ","",$linea);
                $linea=str_replace (array("\r\n", "\n", "\r"), ' ', $linea);
                $linea=str_replace(" ","",$linea);
                $cadena="CLIENTE UNICO |NOMBRE DEL CLIENTE |CUADRANTE |ZONA GEO |RFC DEL CLIENTE |DIRECCION DOMICILIO CLIENTE |# EXTERIOR CLIENTE |# INTERIOR CLIENTE |CP CLIENTE |COLONIA CLIENTE |POBLACION CLIENTE |ESTADO CLIENTE |REFERENCIAS DOMICILIO |CLASIFICACION DEL CLIENTE |ATRASO MAXIMO |DIAS DE ATRASO |SALDO CAPITAL |SALDO |MORATORIOS |SALDO TOTAL |DIA DE PAGO |FECHA ULTIMO PAGO |IMPORTE ULTIMO PAGO |DIRECCION EMPLEO CLIENTE |# EXTERIOR EMPLEO CLIENTE |# INTERIOR EMPLEO CLIENTE |COLONIA EMPLEO CLIENTE|POBLACION CLIENTE |ESTADO EMPLEO CLIENTE |NOMBRE DEL AVAL |TELEFONO DEL AVAL |DIRECCION DEL AVAL |# EXTERIOR DEL AVAL |# INTERIOR DEL AVAL |# COLONIA DEL AVAL |CP DEL AVAL |POBLACION DEL AVAL |ESTADO DEL AVAL |TELEFONO 1 |TELEFONO 2 |TELEFONO 3 |TELEFONO 4 |GERENCIA |CLASIFICACION CLIENTE |DESC CLASIFICACION CLIENTE |SEXO DEL CLIENTE |CALIFICACION CLIENTE |NIVEL RIESGO |SCORE |CLASIFICACION SCORE |PERIOCIDAD DEL CLIENTE |TASA DE INTERES |PLAZO DEL CREDITO |ID DE GRUPO |NOMBRE DEL GRUPO|CICLO DE RENOVACION |FECHA LIMITE DE PAGO |TIPO TDC |TASA DE INTERES |LIMITE DE CREDITO |PAGO MINIMO |PAGO PARA NO GENERAR INTERES |SALDO TOTAL |NO ATRASOS |FECHA DEL ULTIMO |MONTO DEL ULTIMO PAGO |FECHA ULTIMA GESTION |ULTIMO CODIGO DE ACCION |ULTIMO CODIGO DE RESULTADO |FECHA DE PP |MONTO DE PP";
                $cadena=str_replace(" ","",$cadena);
                $cadena=str_replace("  ","",$cadena);
                return $cadena==$linea;
            }
        }
        while (!feof($fp))
        {
            $linea = fgets($fp);
            if(!strlen($linea)==0) //Si la línea no esta vacía
            {
                $dato->setLinea($linea);
                $dato->limpiarLinea();
                $this->insertarMapa($dato);
            }
        }
        fclose ($fp);
    }
    public function validarArchivo() :bool
    {
        if($this->formatoArchivo==DataGeneral::TIPOSLC || $this->formatoArchivo==DataGeneral::TIPOSCYBER)
        {
            if($this->tipoArchivo=="Xlsx")
            {
                return $this->leerExcel(true);
            }
            else if ($this->tipoArchivo=="Csv")
            {
                return $this->leerArchivo(true);
            }
        }
        else
        {
            return $this->leerExcel(true);
        }
        return  null;
    }


    public function leerDatosExcel(){
        $lector = IOFactory::createReader($this->tipoArchivo);
        $spreadSheet=$lector->load($this->archivo);
        $sheet=$spreadSheet->getActiveSheet();
        $leer=false;
        $this->mapa=array();
        foreach ($sheet->getRowIterator() as $row ){
            $cellIterator=$row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            if(!$leer){
                $leer=true;
            }else{
                $columna=0;
                foreach ($cellIterator as $cell) {
                    $valor = $cell->getValue();
                    if($columna==2){
                        $idCliente=array(DataGeneral::CLIENTE_UNICO=>$valor);
                        $this->mapa=array_merge($this->mapa,$idCliente);
                    }
                    if($columna>=5 && $columna<=9){
                        $num=array();
                        switch ($columna){
                            case 5:
                                $num=array(DataGeneral::TELEFONO_1=>$valor);
                                break;
                            case 6:
                                $num=array(DataGeneral::TELEFONO_2=>$valor);
                                break;
                            case 7:
                                $num=array(DataGeneral::TELEFONO_3=>$valor);
                                break;
                            case 8:
                                $num=array(DataGeneral::TELEFONO_4=>$valor);
                                break;
                            case 9:
                                $num=array(DataGeneral::TELEFONO_5=>$valor);
                                break;
                        }
                        $this->mapa=array_merge($this->mapa,$num);
                    }
                    $columna++;

                }
                $this->insertaTelefonosCliente();
            }
        }
    }



    private function leerExcel($soloCabecera)
    {
        $dato =new Linea();
        $lector = IOFactory::createReader($this->tipoArchivo);
        $spreadSheet=$lector->load($this->archivo);
        $sheet=$spreadSheet->getActiveSheet();
        $palabra="";
        foreach ($sheet->getRowIterator() as $row )
        {
            $guardar=true;
            $cellIterator=$row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            foreach ($cellIterator as $cell)
            {
                $valor=$cell->getValue();
                if($soloCabecera)
                {
                    if(strlen($valor)>0)
                    {
                        $palabra=$palabra.$valor.'|';
                        $guardar=false;
                    }
                }
                else
                {
                    if(substr($valor,0,13)!="CLIENTE UNICO" && $guardar && substr($valor,0,13)!="Teléfono")
                    {
                        $palabra=$palabra.$valor.'|';
                    }
                    else
                    {
                        $guardar=false;
                    }
                }
            }
            if($guardar)
            {
                $dato->setLinea($palabra);
                $dato->limpiarLinea();
                $this->insertarMapa($dato);
                $palabra="";
            }
            if($soloCabecera)
            {
                switch ($this->formatoArchivo)
                {
                    case DataGeneral::TIPOSLC:

                        $cadena="CLIENTE UNICO|NOMBRE DEL CLIENTE|CUADRANTE|ZONA GEO|RFC DEL CLIENTE|ID DESPACHO|DIRECCION DOMICILIO CLIENTE|# EXTERIOR CLIENTE|# INTERIOR CLIENTE|CP CLIENTE|COLONIA CLIENTE|POBLACION CLIENTE|ESTADO CLIENTE|CLASIFICACION DEL CLIENTE|ATRASO MAXIMO|SALDO|MORATORIOS|SALDO TOTAL|DIA DE PAGO|FECHA ULTIMO PAGO|IMPORTE ULTIMO PAGO|DIRECCION EMPLEO CLIENTE|# EXTERIOR EMPLEO CLIENTE|# INTERIOR EMPLEO CLIENTE|COLONIA EMPLEO CLIENTE|POBLACION CLIENTE|ESTADO EMPLEO CLIENTE|NOMBRE DEL AVAL|TELEFONO DEL AVAL|DIRECCION DEL AVAL|# EXTERIOR DEL AVAL|# COLONIA DEL AVAL|CP DEL AVAL|POBLACION DEL AVAL|ESTADO DEL AVAL|DIA DE PAGO|TELEFONO 1|TIPO TELEFONO|TELEFONO 2|TIPO TELEFONO|TELEFONO 3|TIPO TELEFONO|TELEFONO 4|TIPO TELEFONO|MIGRADO A CYBER|LATITUD|LONGITUD|GERENCIA|ENCARGADO|CLASIFICACION ESPECIAL|";

                        return $cadena==$palabra;
                        break;
                    case DataGeneral::TIPOSCYBER:
                        $cadena="CLIENTE UNICO |NOMBRE DEL CLIENTE |CUADRANTE |ZONA GEO |RFC DEL CLIENTE |DIRECCION DOMICILIO CLIENTE |# EXTERIOR CLIENTE |# INTERIOR CLIENTE |CP CLIENTE |COLONIA CLIENTE |POBLACION CLIENTE |ESTADO CLIENTE |REFERENCIAS DOMICILIO |CLASIFICACION DEL CLIENTE |ATRASO MAXIMO |DIAS DE ATRASO |SALDO CAPITAL |SALDO |MORATORIOS |SALDO TOTAL |DIA DE PAGO |FECHA ULTIMO PAGO |IMPORTE ULTIMO PAGO |DIRECCION EMPLEO CLIENTE |# EXTERIOR EMPLEO CLIENTE |# INTERIOR EMPLEO CLIENTE |COLONIA EMPLEO CLIENTE|POBLACION CLIENTE |ESTADO EMPLEO CLIENTE |NOMBRE DEL AVAL |TELEFONO DEL AVAL |DIRECCION DEL AVAL |# EXTERIOR DEL AVAL |# INTERIOR DEL AVAL |# COLONIA DEL AVAL |CP  DEL AVAL |POBLACION DEL AVAL |ESTADO DEL AVAL |TELEFONO 1 |TELEFONO 2 |TELEFONO 3 |TELEFONO 4 |GERENCIA |CLASIFICACION CLIENTE |DESC CLASIFICACION CLIENTE |SEXO DEL CLIENTE |CALIFICACION CLIENTE |NIVEL RIESGO |SCORE |CLASIFICACION SCORE |PERIOCIDAD DEL CLIENTE |TASA DE INTERES |PLAZO DEL CREDITO |ID DE GRUPO |NOMBRE DEL GRUPO|CICLO DE RENOVACION |FECHA LIMITE DE PAGO |TIPO TDC |TASA DE INTERES |LIMITE DE CREDITO |PAGO MINIMO |PAGO PARA NO GENERAR INTERES |SALDO TOTAL |NO ATRASOS |FECHA DEL ULTIMO |MONTO DEL ULTIMO PAGO |FECHA ULTIMA GESTION |ULTIMO CODIGO DE ACCION |ULTIMO CODIGO DE RESULTADO |FECHA DE PP |MONTO DE PP|";
                        return $cadena==$palabra;
                        break;
                    case DataGeneral::EXCEL_AUTOMATICO:
                        $cadena="Teléfono|Estado Llamada|Agente|Fecha y hora|Duración(Seg)|Uniqueid|Failure Code|Failure Cause|CLIENTEUNICO|NOMBRE DEL CLIENTE|NUMERO ID|NOMBRE GRUPO|SALDO TOTAL|SALDO ATRASADO|MORATORIOS|ATRASO MAXIMO|DIA DE PAGO|FECHA ULTIMO PAGO|IMPORTE ULTIMO PAGO|NOMBRE DEL AVAL|TIPO TELEFONO|MIGRADO A CYBER|OBSERVACIONES|GERENCIA|";
                        return $cadena==$palabra;
                        break;
                    case DataGeneral::EXCEL_MANUAL:
                        $cadena="CLIENTE UNICO|CA|CR|ID SCL|COMENTARIO|";
                        $this->quitarMierdas($cadena,$palabra);
                        return $cadena==$palabra;
                        break;
                }
            }
        }
    }

    private function quitarMierdas(&$cadena,&$linea)
    {
        $cadena=str_replace(" ","",$cadena);
        $cadena=str_replace("  ","",$cadena);
        $linea=str_replace(" ","",$linea);
        $linea=str_replace (array("\r\n", "\n", "\r"), ' ', $linea);
        $linea=str_replace(" ","",$linea);
    }

    private function insertarMapa(Linea $dato)
    {
        /**Solo insertar **/
        if($this->formatoArchivo==DataGeneral::TIPOSLC)
        {
            $dato->crearMapaSCL();
        }
        else if ($this->formatoArchivo==DataGeneral::TIPOSCYBER)
        {
            $dato->crearMapaCYBER();
        }
        else  if ($this->formatoArchivo==DataGeneral::EXCEL_MANUAL)
        {
            $dato->crearMapaManual();
        }
        else
        {
            $dato->crearMapaAutomatico();
        }
        $this->mapa=$dato->getMapa();

        $this->insertarDatos();
    }

    public function __toString()
    {
        return "Formato archivo: ".$this->formatoArchivo."archivo: ".$this->archivo." tipoArchivo: ".$this->tipoArchivo." IDUsuario".$this->idUsuario;
    }

}
