<?php


namespace App;

use App\Traits\Util;
use PDO;

class ConsultaSQL
{
    use Util;
    private static $instance;
    private $fecha;
    /**
     * ConsultaSQL constructor.
     */
    public function __construct(){
    }
    public static function getInstance()
    {
        if(!self::$instance instanceof self)
        {
            self::$instance=new self();
        }
        return self::$instance;
    }

    private function generarFecha()
    {
        $this->fecha=date('Y-m-d H:i:s', strtotime("now"));
    }

    public function insertaCliente(ClienteModel $cliente,$formato) : void
    {

        self::generarFecha();
        //La weada de php no acepta el pto get en el bindParam y obliga a setearlo a variables >:V
        $clienteID=$cliente->getClienteUnicoID();
        $nombreCliente=$cliente->getNombreCliente();
        $rfcCliente=$cliente->getRfcCliente();
        $gerencia=$cliente->getGerencia();
        $encargado=$cliente->getEncargado();
        $idGrupo=$cliente->getNumeroId();
        $nombreGrupo=$cliente->getNombreGrupo();
        $query= /** @lang text */
            "INSERT INTO \"Cliente\" (id_cliente, nombre, rfc, gerencia, created_at, updated_at, encargado,id_grupo,nombre_grupo)
            VALUES(:id_cliente, :nombre, :rfc, :gerencia, :created_at, :update_at, :encargado,:id_grupo,:nombre_grupo) ON CONFLICT (id_cliente)
            DO NOTHING";
        if($formato==ProcesadorData::TIPOSLC)
        {
            if(strlen($encargado)>0)
            {
                $query= /** @lang text */
                    "INSERT INTO \"Cliente\" (id_cliente, nombre, rfc, gerencia, created_at, updated_at, encargado,id_grupo,nombre_grupo)
                VALUES(:id_cliente, :nombre, :rfc, :gerencia, :created_at, :update_at, :encargado,:id_grupo,:nombre_grupo) ON CONFLICT (id_cliente)
                DO UPDATE SET encargado=excluded.encargado";
            }
        }
        else
        {
            if(strlen($nombreGrupo)>0 && strlen($idGrupo)>0)
            {
                $query= /** @lang text */
                    "INSERT INTO \"Cliente\" (id_cliente, nombre, rfc, gerencia, created_at, updated_at, encargado,id_grupo,nombre_grupo)
                VALUES(:id_cliente, :nombre, :rfc, :gerencia, :created_at, :update_at, :encargado,:id_grupo,:nombre_grupo) ON CONFLICT (id_cliente)
                DO UPDATE SET
                nombre_grupo=excluded.nombre_grupo,
                id_grupo=excluded.id_grupo";
            }
            else if(strlen($nombreGrupo)>0)
            {
                $query= /** @lang text */
                    "INSERT INTO \"Cliente\" (id_cliente, nombre, rfc, gerencia, created_at, updated_at, encargado,id_grupo,nombre_grupo)
                VALUES(:id_cliente, :nombre, :rfc, :gerencia, :created_at, :update_at, :encargado,:id_grupo,:nombre_grupo) ON CONFLICT (id_cliente)
                DO UPDATE SET
                nombre_grupo=excluded.nombre_grupo";
            }
            else if(strlen($idGrupo)>0)
            {
                $query= /** @lang text */
                    "INSERT INTO \"Cliente\" (id_cliente, nombre, rfc, gerencia, created_at, updated_at, encargado,id_grupo,nombre_grupo)
                VALUES(:id_cliente, :nombre, :rfc, :gerencia, :created_at, :update_at, :encargado,:id_grupo,:nombre_grupo) ON CONFLICT (id_cliente)
                DO UPDATE SET
                id_grupo=excluded.id_grupo";
            }
        }

        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->bindParam(':id_cliente',$clienteID,PDO::PARAM_STR);
        $sentencia->bindParam(':nombre',$nombreCliente,PDO::PARAM_STR);
        $sentencia->bindParam(':rfc',$rfcCliente,PDO::PARAM_STR);
        $sentencia->bindParam(':gerencia',$gerencia,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':update_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':encargado',$encargado,PDO::PARAM_STR);
        $sentencia->bindParam(':id_grupo',$idGrupo,PDO::PARAM_STR);
        $sentencia->bindParam(':nombre_grupo',$nombreGrupo,PDO::PARAM_STR);
        $r=$sentencia->execute();
        if($r)
        {
            ConexionSQL::getInstance()->setSeInsertoTodo(true);
        }

    }

    public function insertarGestores($bdHija,$listaGestores)
    {
        $this->generarFecha();
        ConexionSQL::getInstance()->cambiarConexion("B4nC0");
        $tam=count($listaGestores);
        for($i=0; $i<$tam; $i++)
        {
            $this->crearGestor($listaGestores[$i]["gestor"],$bdHija);
        }
        ConexionSQL::getInstance()->cambiarConexion($bdHija);
    }

    public function insertarConvenio($convenio,$idGestion)
    {
        $this->generarFecha();
        $idCliente=$convenio["contrato"];
        $idCliente=str_replace(" ","",$idCliente);
        $idCliente=str_replace("*","",$idCliente);
        $convenioEstado=true;
        $primerPagoEstado=true;
        $created=$convenio["fecha_gestion"];
        if($convenio["status_pago"]=="En proceso")
        {
            $primerPagoEstado=false;
        }
        $primarPago=(float)$convenio["pagos_varios"];
        if(strlen($convenio["fecha_entrega"])>0 || $convenio["fecha_entrega"]==null || isset($convenio["fecha_entrega"]) || empty(($convenio["fecha_entrega"])))
        {
            $primerPagoFecha=$convenio["fecha_gestion"];
        }
        else
        {
            $primerPagoFecha=$convenio["fecha_entrega"];
        }
        $numeroPagos=(int)$convenio["semanas"];
        $deudaTotal=(float)$numeroPagos*$primarPago;
        $folio=$convenio["folio_convenio"];
        $query="INSERT INTO public.\"Convenio\"(
	    id_gestion, id_cliente, convenio_estado, primer_pago_cantidad,
	    primer_pago_estado, primer_pago_fecha, deuda_total,
	    deuda_total_original, numero_pagos, \"folioGen\", created_at, updated_at)
	    VALUES (:id_gestion, :id_cliente, :convenio_estado, :primer_pago_cantidad,
	    :primer_pago_estado, :primer_pago_fecha, :deuda_total,
	    :deuda_total_original, :numero_pagos, :folioGen, :created_at, :updated_at)";

        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->bindParam(':id_gestion',$idGestion,PDO::PARAM_STR);
        $sentencia->bindParam(':id_cliente',$idCliente,PDO::PARAM_STR);

        $sentencia->bindParam(':convenio_estado',$convenioEstado,PDO::PARAM_BOOL);
        $sentencia->bindParam(':primer_pago_cantidad',$primarPago,PDO::PARAM_STR);
        $sentencia->bindParam(':primer_pago_estado',$primerPagoEstado,PDO::PARAM_BOOL);
        $sentencia->bindParam(':primer_pago_fecha',$primerPagoFecha,PDO::PARAM_STR);
        $sentencia->bindParam(':deuda_total',$deudaTotal,PDO::PARAM_STR);
        $sentencia->bindParam(':deuda_total_original',$deudaTotal,PDO::PARAM_STR);
        $sentencia->bindParam(':numero_pagos',$numeroPagos,PDO::PARAM_INT);

        $sentencia->bindParam(':folioGen',$folio,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$created,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$created,PDO::PARAM_STR);
        $d=$sentencia->execute();
        if(!$d)
        {
            echo "nel en convenios";
            dd("idgestion",$idGestion,
                "cliente",$idCliente,
                "conv estado",$convenioEstado,
                "primer pago",$primarPago,
                "priemr pago esta",$primerPagoEstado,
                "primer pago fec",$primerPagoFecha,
                "deuda total",$deudaTotal,
            "num pagos",$numeroPagos,"folio",$folio);
        }
    }

    public function insertarCalendarioPagos($calendario,$folio)
    {
        self::generarFecha();
        $idCliente=$calendario["contrato"];
        $idCliente=str_replace(" ","",$idCliente);
        $idCliente=str_replace("*","",$idCliente);
        $pagoRealizado=null;
        if(strlen($calendario["fecha_entrega"])>0)
        {
            $fechaPagoEsperada=$calendario["fecha_entrega"];
        }
        else
        {
            $fechaPagoEsperada=$calendario["fecha_gestion"];
        }
        $fechaPagoRealizada=null;
        $pagado=null;
        $pagoEsperado=(float)$calendario["pagos_varios"];
        if($calendario["status_pago"]=="Pagado" || $calendario["status_pago"]=="Verificar Pago")
        {
            if(strtotime($fechaPagoEsperada)<strtotime($this->fecha))
            {
                $pagado=true;
                $pagoRealizado=(float)$calendario["pagos_varios"];
                $fechaPagoRealizada=$fechaPagoEsperada;
            }
        }
        $folioIngresado=$calendario["folio_convenio"];
        $comentario=$calendario["notas_gestion"];
        $folio=$folio[0]["id_convenio"];

        $query="INSERT INTO public.\"CalendarioPagos\"(
	        id_cliente, folio, fecha_pago_esperada, fecha_pago_realizada, pagado, pago_esperado, pago_realizado,
           folio_ingresado, comentario, created_at, updated_at)
	        VALUES (:id_cliente, :folio, :fecha_pago_esperada, :fecha_pago_realizada, :pagado, :pago_esperado, :pago_realizado,
            :folio_ingresado, :comentario, :created_at, :updated_at)";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->bindParam(':id_cliente',$idCliente,PDO::PARAM_STR);
        $sentencia->bindParam(':folio',$folio,PDO::PARAM_INT);
        $sentencia->bindParam(':fecha_pago_esperada',$fechaPagoEsperada,PDO::PARAM_STR);
        $sentencia->bindParam(':fecha_pago_realizada',$fechaPagoEsperada,PDO::PARAM_STR);
        $sentencia->bindParam(':pagado',$pagado,PDO::PARAM_BOOL);
        $sentencia->bindParam(':pago_esperado',$pagoEsperado);
        $sentencia->bindParam(':pago_realizado',$pagoRealizado);
        $sentencia->bindParam(':folio_ingresado',$folioIngresado,PDO::PARAM_STR);
        $sentencia->bindParam(':comentario',$comentario,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$this->fecha,PDO::PARAM_STR);
        $d=$sentencia->execute();
    }

    public function obtenerIdConvenio($folio_gen)
    {
        $query="SELECT id_convenio FROM \"Convenio\" WHERE \"folioGen\"=?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$folio_gen]);
        return $sentencia->fetchAll();
    }

    public function consultarListaIDGestor($bdHija)
    {
        ConexionSQL::getInstance()->cambiarConexion("B4nC0");
        $query="SELECT id,username FROM \"users\" WHERE tipo = ? AND despacho = ? ";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute(["Gestor",$bdHija]); //todo cambiar por bd hija
        $datos=$sentencia->fetchAll();
        ConexionSQL::getInstance()->cambiarConexion($bdHija);
        return $datos;
    }

    public function deshabilitarTriggers()
    {
        //Convenio
        $this->deshabilitarTriggerGenerarFolioConvenio();
        $this->deshabilitarTriggerEstadoConvenio();
        $this->deshabilitarTriggerInsertaCalendario();
        //Gestion
        $this->deshabilitarTriggerGenerarFolioGestion();
        //calendario
        $this->deshabilitarTriggeractualizaconvenioestado();
        $this->deshabilitarTriggeractualizamontorealgrl();
    }

    private function deshabilitarTriggeractualizaconvenioestado()
    {
        $query="ALTER TABLE \"CalendarioPagos\" DISABLE TRIGGER actualizaconvenioestado";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }
    private function deshabilitarTriggeractualizamontorealgrl()
    {
        $query="ALTER TABLE \"CalendarioPagos\" DISABLE TRIGGER actualizamontorealgrl";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }

    private function habilitarTriggeractualizaconvenioestado()
    {
        $query="ALTER TABLE \"CalendarioPagos\" ENABLE TRIGGER actualizaconvenioestado";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }
    private function habilitarTriggeractualizamontorealgrl()
    {
        $query="ALTER TABLE \"CalendarioPagos\" ENABLE TRIGGER actualizamontorealgrl";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }




    private function deshabilitarTriggerInsertaCalendario()
    {
        $query="ALTER TABLE \"Convenio\" DISABLE TRIGGER insertadatoscalendariopagos";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }

    private function deshabilitarTriggerEstadoConvenio()
    {
        $query="ALTER TABLE \"Convenio\" DISABLE TRIGGER actualizaconvenioestado";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }

    private function deshabilitarTriggerGenerarFolioConvenio()
    {
        $query="ALTER TABLE \"Convenio\" DISABLE TRIGGER generarfolioconvenio";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }
    private function deshabilitarTriggerGenerarFolioGestion()
    {
        $query="ALTER TABLE \"Gestion\" DISABLE TRIGGER generarfoliogestion";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }

    public function habilitarTriggers()
    {
        //Gestion
        $this->habilitarTriggerGenerarFolioGestion();
        //Convenio
        $this->habilitarTriggerInsertaCalendario();
        $this->habilitarTriggerEstadoConvenio();
        $this->habilitarTriggerGenerarFolioConvenio();
        //calendario
        $this->habilitarTriggeractualizaconvenioestado();
        $this->habilitarTriggeractualizamontorealgrl();
    }

    private function habilitarTriggerEstadoConvenio()
    {
        $query="ALTER TABLE \"Convenio\" ENABLE TRIGGER actualizaconvenioestado";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }

    private function habilitarTriggerGenerarFolioConvenio()
    {
        $query="ALTER TABLE \"Convenio\" ENABLE TRIGGER generarfolioconvenio";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }

    private function habilitarTriggerGenerarFolioGestion()
    {
        $query="ALTER TABLE \"Gestion\" ENABLE TRIGGER generarfoliogestion";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }

    private function habilitarTriggerInsertaCalendario()
    {
        $query="ALTER TABLE \"Convenio\" ENABLE TRIGGER insertadatoscalendariopagos";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }

    public function consultaGestionesCompletas()
    {
        $query="SELECT * FROM gestion";
        $sentencia=ConexionMySQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

    public function existeGestion($folio)
    {
        $query="SELECT * FROM \"Gestion\" WHERE \"folioGen\"=?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$folio]);
        $arreglo=$sentencia->fetchAll();
        return count($arreglo);
    }

    public function noHapagadoMasDedos($folio)
    {
        //consulta calendariPagos donde el idConvenio = folio y pagado = falso -> si >2 false
        $query="SELECT * FROM \"CalendarioPagos\" WHERE \"folio\"=? AND pagado IS NULL";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$folio]);
        $arreglo=$sentencia->fetchAll();
        return count($arreglo);
    }




    public function insertaGestionesBdVieja($gestion,$id_gestor)
    {
        self::generarFecha();
        $idUsuario=$id_gestor;
        $idCliente=$gestion["contrato"];
        $idCliente=str_replace(" ","",$idCliente);
        $idCliente=str_replace("*","",$idCliente);
        $idTipoGestion=null;
        $idTipoGestionSSL=null;
        $tipoGestion=$gestion["tipo_gestion"];
        $tipoGestion=str_replace("\"","",$tipoGestion);
        $idCa=null;
        //todo pasar
        if(strlen($tipoGestion)>0)
        {
            if($tipoGestion=="Promesa de Pago")
            {
                $clave="PP";
                $idTipoGestion=$this->consultaIDCodigoResultado($clave);
            }
            else if($tipoGestion=="Negativa de Pago")
            {
                $clave="NP";
                $idTipoGestion=$this->consultaIDCodigoResultado($clave);
            }
            else if($tipoGestion=="RMD")
            {
                $clave="CN";
                $idTipoGestion=$this->consultaIDCodigoResultado($clave);
                $idCa=1; //LT
            }
            else if($tipoGestion=="Posible Fraude")
            {
                $clave="NL";
                $idTipoGestion=$this->consultaIDCodigoResultado($clave);
                $idCa=3; //FR
            }
            else if($tipoGestion=="Recordatorio de Pago")
            {
                $clave="SG";
                $idTipoGestion=$this->consultaIDCodigoResultado($clave);
                $idCa=1; //LT
            }
            else if($tipoGestion=="Defunción" || $tipoGestion=="DefunciÃ³n")
            {
                $clave="FA";
                $idTipoGestion=$this->consultaIDCodigoResultado($clave);
                $idCa=4; //DI
            }
            else if($tipoGestion=="Ilocalizable")
            {
                $clave="IL";
                $idTipoGestion=$this->consultaIDCodigoResultado($clave);
                $idCa=4; //DI
            }
            else if($tipoGestion=="Cambio de Domicilio")
            {
                $clave="FP";
                $idTipoGestion=$this->consultaIDCodigoResultado($clave);
                $idCa=1; //LT
            }
        }

        if($tipoGestion=="RMD")
        {
            $idTipoGestionSSL=6;
        }
        else if($tipoGestion=="Posible Fraude")
        {
            $idTipoGestionSSL=18;
        }
        else if($tipoGestion=="Recordatorio de Pago")
        {
            $idTipoGestionSSL=9;
        }
        else if($tipoGestion=="Defunción" || $tipoGestion=="DefunciÃ³n")
        {
            $idTipoGestionSSL=10;
        }
        else if($tipoGestion=="Ilocalizable" || $tipoGestion=="Cambio de Domicilio")
        {
            $idTipoGestionSSL=2;
        }

        $comentario=$gestion["notas_gestion"];
        $folio=$gestion["folio_gestion"];
        $fechaGestion=$gestion["fecha_gestion"];
        $query="INSERT INTO \"Gestion\" (id_usuario,id_cliente,tit_aval, id_tipo_gestion,id_tipo_gestion_ssl,comentario,
                         created_at,updated_at,\"folioGen\",fecha_hora_contactar)
                VALUES(:id_usuario,:id_cliente,:tit_aval, :id_tipo_gestion,:id_tipo_gestion_ssl,:comentario,
                       :created_at,:updated_at,:folioGen,:fecha_hora_contactar)";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->bindParam(':id_usuario',$idUsuario,PDO::PARAM_INT);
        $sentencia->bindParam(':id_cliente',$idCliente,PDO::PARAM_STR);
        $sentencia->bindParam(':tit_aval',$idCa,PDO::PARAM_STR);
        $sentencia->bindParam(':id_tipo_gestion',$idTipoGestion,PDO::PARAM_INT);
        $sentencia->bindParam(':id_tipo_gestion_ssl',$idTipoGestionSSL,PDO::PARAM_INT);
        $sentencia->bindParam(':comentario',$comentario,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$fechaGestion,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$fechaGestion,PDO::PARAM_STR);
        $sentencia->bindParam(':folioGen',$folio,PDO::PARAM_STR);
        $sentencia->bindParam(':fecha_hora_contactar',$fechaGestion,PDO::PARAM_STR);
        $d=$sentencia->execute();
        if(!$d)
        {
            echo "nel en gestion";
        }
        return ConexionSQL::getInstance()->getConexion()->lastInsertId();
    }

    private function crearGestor($nombreGestor,$bdHija)
    {
        $nuevoNombre=$nombreGestor;
        $nuevoNombre=str_replace("\"","",$nuevoNombre);
        $nuevoNombre=str_replace("*","",$nuevoNombre);
        self::generarFecha();
        $tipo="Gestor";
        $arregloNombre=explode(" ",$nuevoNombre,2);
        $nombre=null;
        $apellido=null;
        $contra="1234";

        $contraEncript=password_hash($contra,PASSWORD_BCRYPT);
        if(isset($arregloNombre[0]))
        {
            $nombre=$arregloNombre[0];
        }
        if(isset($arregloNombre[1]))
        {
            $apellido=$arregloNombre[1];
        }
        $query="INSERT INTO users (name,last_name, despacho,tipo,username,password,created_at,updated_at)
            VALUES(:name,:last_name,:despacho,:tipo,:username,:password,:created_at,:updated_at)";
        $apodo=$nombre." ".$apellido;
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->bindParam(':name',$nombre);
        $sentencia->bindParam(':last_name',$apellido);
        $sentencia->bindParam(':despacho',$bdHija,PDO::PARAM_STR);
        $sentencia->bindParam(':tipo',$tipo,PDO::PARAM_STR);
        $sentencia->bindParam(':username',$apodo,PDO::PARAM_STR);
        $sentencia->bindParam(':password',$contraEncript,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$this->fecha,PDO::PARAM_STR);
        $dd=$sentencia->execute();
        if(!$dd)
        {
            //dd("nel en ususarios gestores ",mb_detect_encoding($nuevoNombre),$nuevoNombre);
            echo "<br><br>";
            echo $nombreGestor;
            echo "<br><br>";
        }
    }

    public function obtenerGestores()
    {
        $query="SELECT gestor FROM gestion WHERE gestor IS NOT NULL GROUP BY gestor ";
        $sentencia=ConexionMySQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

    public function cancelarConvenio($idCliente)
    {
        $query="UPDATE \"Convenio\" SET convenio_estado=false WHERE id_cliente=?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$idCliente]);
    }

    public function permitirGoupbyTodo()
    {
        $query="set global sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';";
        $this->setConfiguracionGroupBy($query);
        $query="set session sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';";
        $this->setConfiguracionGroupBy($query);
    }

    private function setConfiguracionGroupBy($query)
    {
        $sentencia=ConexionMySQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
    }



    public function actualizaAval($nombreAval,$idAval)
    {
        $query="UPDATE \"Aval\" SET nombre_aval=? WHERE id_aval=?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$nombreAval,$idAval]);
    }


    /*
     */
    public function actualizaPago(PagoModel $pagoModel)
    {
        $idCliente=$pagoModel->getIdCliente();
        $query="UPDATE \"Pago\" SET clasificacion=?,atraso_max=?,saldo=?,moratorios=?,total=?,dia_de_pago=?,
                    fecha_pago_ultimo=?,importe_pago_ultimo=? WHERE id_cliente=?";
        $clasificacion=$pagoModel->getClasificacion();
        $atrasoMax=$pagoModel->getAtrasoMax();
        $saldo=$pagoModel->getSaldo();
        $total=$pagoModel->getTotal();
        $diaDePago=$pagoModel->getDiaPago();
        $fechaPagoUltimo=$pagoModel->getFechaPagoUltimo();
        $importe=$pagoModel->getImportePagoUltimo();
        $moratorios=$pagoModel->getMoratorios();
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $r=$sentencia->execute([$clasificacion,$atrasoMax,$saldo,$moratorios,$total,$diaDePago,$fechaPagoUltimo,$importe
        ,$idCliente]);
        if(!$r)
        {
            dd("nel en actualizaPago linea 576 consulta sql");
        }
    }






    public function insertaEncargado($lista)
    {
        $tam=count($lista);
        for($i=0; $i<$tam; $i++)
        {
            $cliente=$lista[$i]["contrato"];
            $this->limpiarCadena($cliente);
            $encargado=$lista[$i]["encargado"];
            $this->limpiarCadena($encargado);
            $this->renombrarEncargado($encargado);
            $this->actualizaClienteAgregaEncargado($cliente,$encargado);
        }
    }

    private function actualizaClienteAgregaEncargado($id_cliente,$encargado)
    {
        $query="UPDATE \"Cliente\" set encargado=? WHERE id_cliente =?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$encargado,$id_cliente]);
    }

    public function actualizaConvenio($idFolio)
    {
        $query="UPDATE \"Convenio\" set convenio_estado=false WHERE id_convenio =?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $s=$sentencia->execute([$idFolio]);
    }

    public function actualizaCalendario($idFolio)
    {
        $query="UPDATE \"CalendarioPagos\" set pagado=false WHERE folio =?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $s=$sentencia->execute([$idFolio]);
        if(!$s)
        {
            echo "nel acatualzia CALENDARIO";
        }
    }


    private function limpiarCadena(&$cadena)
    {
        $cadena=str_replace(" ","",$cadena);
        $cadena=str_replace("*","",$cadena);
    }

    public function obtenerEncargados()
    {
        $query="SELECT contrato,encargado FROM gestion WHERE encargado IS NOT NULL GROUP BY contrato ";
        $sentencia=ConexionMySQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

    public function existeDireccionDeCliente($idCliente,$tipoDireccion)
    {
        $query="SELECT id_direccion FROM \"Direccion\" WHERE id_cliente=? AND tipo_direccion=?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$idCliente,$tipoDireccion]);
        $datos=$sentencia->fetchAll();
        if(count($datos)>0)
        {
            return $datos[0]["id_direccion"];
        }
        return -1;
    }

    public function obtenerDireccionesPorTipo($tipo)
    {
        $query="SELECT id_cliente,\"Direccion\".* FROM \"Direccion\" WHERE tipo_direccion=?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$tipo]);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
    }

    public function obtenerPagos()
    {
        $query="SELECT id_cliente,\"Pago\".* FROM \"Pago\"";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
    }

    public function obtenerAvales()
    {
        $query="SELECT * FROM \"Aval\"";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }





    public function insertaDireccion(DireccionModel $direccion) : int
    {
        self::generarFecha();
        $query= /** @lang text */
            "INSERT INTO \"Direccion\" (id_cliente, cuadrante, zona_geo, direccion, num_ext, num_int, tipo_direccion, cp, colonia, poblacion, estado, created_at, updated_at)
         VALUES(:id_cliente, :cuadrante, :zona_geo, :direccion, :num_ext, :num_int, :tipo_direccion, :cp, :colonia, :poblacion, :estado, :created_at, :updated_at)";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        //La weada de php no acepta el pto get en el bindParam y obliga a setearlo a variables >:V
        $num_ext=$direccion->getNumExt();
        $num_int=$direccion->getNumInt();
        $tipo_direccion=$direccion->getTipoDireccion();
        $cp=$direccion->getCp();
        $colonia=$direccion->getColonia();
        $poblacion=$direccion->getPoblacion();
        $estado=$direccion->getEstado();
        $id_cliente=$direccion->getIdCliente();
        $cuadrante=$direccion->getCuadrante();
        $zona_geo=$direccion->getZonaGeo();
        $direccionInfo=$direccion->getDireccion();
        $sentencia->bindParam(':id_cliente',$id_cliente,PDO::PARAM_STR);
        $sentencia->bindParam(':cuadrante',$cuadrante,PDO::PARAM_STR);
        $sentencia->bindParam(':zona_geo',$zona_geo,PDO::PARAM_STR);
        $sentencia->bindParam(':direccion',$direccionInfo,PDO::PARAM_STR);
        $sentencia->bindParam(':num_ext',$num_ext,PDO::PARAM_STR);
        $sentencia->bindParam(':num_int',$num_int,PDO::PARAM_STR);
        $sentencia->bindParam(':tipo_direccion',$tipo_direccion,PDO::PARAM_STR);
        $sentencia->bindParam(':cp',$cp,PDO::PARAM_STR);
        $sentencia->bindParam(':colonia',$colonia,PDO::PARAM_STR);
        $sentencia->bindParam(':poblacion',$poblacion,PDO::PARAM_STR);
        $sentencia->bindParam(':estado',$estado,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$this->fecha,PDO::PARAM_STR);
        $r=$sentencia->execute();
        if(!$r)
        {
            ConexionSQL::getInstance()->setSeInsertoTodo(true);
        }
        return ConexionSQL::getInstance()->getConexion()->lastInsertId();
    }


    public function actualizarDireccion(DireccionModel $direccion,$idDireccion)
    {
        self::generarFecha();
        $query="UPDATE \"Direccion\" SET cuadrante=?, zona_geo=?, direccion=?,num_ext=?,num_int=?, tipo_direccion=?,cp=?,colonia=?,poblacion=?,estado=?,created_at=?,updated_at=?
        WHERE id_direccion=?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $num_ext=$direccion->getNumExt();
        $num_int=$direccion->getNumInt();
        $tipo_direccion=$direccion->getTipoDireccion();
        $cp=$direccion->getCp();
        $colonia=$direccion->getColonia();
        $poblacion=$direccion->getPoblacion();
        $estado=$direccion->getEstado();
        $id_cliente=$direccion->getIdCliente();
        $cuadrante=$direccion->getCuadrante();
        $zona_geo=$direccion->getZonaGeo();
        $direccionInfo=$direccion->getDireccion();
        $r=$sentencia->execute([$cuadrante,$zona_geo,$direccionInfo,$num_ext,$num_int,$tipo_direccion,$cp,$colonia,$poblacion,$estado,$this->fecha,$this->fecha,$idDireccion]);
        if(!$r)
        {
            ConexionSQL::getInstance()->setSeInsertoTodo(true);
        }
    }




    public function insertaAval($nombreAval,$idDireccion) : int
    {
        self::generarFecha();
        $query= /** @lang text */
            "INSERT INTO \"Aval\" (id_direccion, nombre_aval,created_at, updated_at) VALUES(:id_direccion, :nombre_aval, :created_at, :updated_at)";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->bindParam(':id_direccion',$idDireccion,PDO::PARAM_INT);
        $sentencia->bindParam(':nombre_aval',$nombreAval,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$this->fecha,PDO::PARAM_STR);
        $r=$sentencia->execute();
        if(!$r)
        {
            ConexionSQL::getInstance()->setSeInsertoTodo(true);
        }
        return ConexionSQL::getInstance()->getConexion()->lastInsertId();
    }

    public function insertaRelacionClienteAval($id_aval, $id_cliente) : void
    {
        self::generarFecha();
        $query= /** @lang text */
            "INSERT INTO \"Relacion_Cliente_Aval\" (id_cliente, id_aval,created_at, updated_at) VALUES(:id_cliente, :id_aval, :created_at, :updated_at)";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->bindParam(':id_cliente',$id_cliente,PDO::PARAM_STR);
        $sentencia->bindParam(':id_aval',$id_aval,PDO::PARAM_INT);
        $sentencia->bindParam(':created_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$this->fecha,PDO::PARAM_STR);
        $r=$sentencia->execute();
        if(!$r)
        {
            ConexionSQL::getInstance()->setSeInsertoTodo(true);
        }
    }

    public function insertaPago(PagoModel $pagoModel) : void
    {
        self::generarFecha();
        $query= /** @lang text */
            "INSERT INTO \"Pago\" (id_cliente, clasificacion,atraso_max, saldo,moratorios,total,dia_de_pago,fecha_pago_ultimo,importe_pago_ultimo, created_at, updated_at) VALUES(:id_cliente, :clasificacion, :atraso_max, :saldo,:moratorios,:total,:dia_de_pago,:fecha_pago_ultimo,:importe_pago_ultimo, :created_at, :updated_at)";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $idCliente=$pagoModel->getIdCliente();
        $clasificacion=$pagoModel->getClasificacion();
        $atrasoMax=$pagoModel->getAtrasoMax();
        $saldo=$pagoModel->getSaldo();
        $total=$pagoModel->getTotal();
        $diaDePago=$pagoModel->getDiaPago();
        $fechaPagoUltimo=$pagoModel->getFechaPagoUltimo();
        $importe=$pagoModel->getImportePagoUltimo();
        $moratorios=$pagoModel->getMoratorios();

        $sentencia->bindParam(':id_cliente',$idCliente,PDO::PARAM_STR);
        $sentencia->bindParam(':clasificacion',$clasificacion,PDO::PARAM_STR);
        $sentencia->bindParam(':atraso_max',$atrasoMax,PDO::PARAM_STR);
        $sentencia->bindParam(':saldo',$saldo,PDO::PARAM_STR);
        $sentencia->bindParam(':moratorios',$moratorios,PDO::PARAM_STR);
        $sentencia->bindParam(':total',$total,PDO::PARAM_STR);
        $sentencia->bindParam(':dia_de_pago',$diaDePago,PDO::PARAM_STR);
        $sentencia->bindParam(':fecha_pago_ultimo',$fechaPagoUltimo,PDO::PARAM_STR);
        $sentencia->bindParam(':importe_pago_ultimo',$importe,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$this->fecha,PDO::PARAM_STR);
        $r=$sentencia->execute();
        if(!$r)
        {
            ConexionSQL::getInstance()->setSeInsertoTodo(true);
        }
    }

    public function insertaTelefonoCliente($numTel) : int
    {
        self::generarFecha();
        $query= /** @lang text */
            "INSERT INTO \"Telefono_Cliente\" (numero_tel,created_at, updated_at) VALUES(:numero_tel, :created_at, :updated_at)";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $numTel=str_replace(" ","",$numTel);
        $sentencia->bindParam(':numero_tel',$numTel,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$this->fecha,PDO::PARAM_STR);
        $r=$sentencia->execute();
        if(!$r)
        {
            echo "nel";
            ConexionSQL::getInstance()->setSeInsertoTodo(true);
        }
        return ConexionSQL::getInstance()->getConexion()->lastInsertId();
    }

    public function insertaRelacionClienteTelefono($idCliente,$id_r_c_t)
    {
        self::generarFecha();
        $query= /** @lang text */
            "INSERT INTO \"Relacion_Cliente_Telefono\" (id_telefono,id_cliente,created_at, updated_at) VALUES(:id_telefono,:id_cliente, :created_at, :updated_at)";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->bindParam(':id_telefono',$id_r_c_t,PDO::PARAM_INT);
        $sentencia->bindParam(':id_cliente',$idCliente,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$this->fecha,PDO::PARAM_STR);
        $r=$sentencia->execute();
        if(!$r)
        {
            ConexionSQL::getInstance()->setSeInsertoTodo(true);
        }
    }

    public function insertaTelefonoAval($idAval,$numTel)
    {
        self::generarFecha();
        $query= /** @lang text */
            "INSERT INTO \"Telefono_Aval\" (id_aval,numero_tel,created_at, updated_at) VALUES(:id_aval,:numero_tel,:created_at, :updated_at)";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->bindParam(':id_aval',$idAval,PDO::PARAM_INT);
        $sentencia->bindParam(':numero_tel',$numTel,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$this->fecha,PDO::PARAM_STR);
        $r=$sentencia->execute();
        if(!$r)
        {
            ConexionSQL::getInstance()->setSeInsertoTodo(true);
        }
    }


    public function insertaTrabajoCliente(TrabajoModel $trabajoModel)
    {
        self::generarFecha();
        $numero= $trabajoModel->getNumTel();
        $idClietne=$trabajoModel->getIdCliente();
        $id_direccion=$trabajoModel->getIdDireccion();

        $query= /** @lang text */
            "INSERT INTO \"Trabajo\" (num_tel,id_cliente,created_at, updated_at,id_direccion) VALUES(:numero_tel,:id_cliente,:created_at, :updated_at,:id_direccion)";

        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->bindParam(':numero_tel',$numero,PDO::PARAM_STR);
        $sentencia->bindParam(':id_cliente',$idClietne,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':id_direccion',$id_direccion,PDO::PARAM_INT);
        $r=$sentencia->execute();
        if(!$r)
        {
            ConexionSQL::getInstance()->setSeInsertoTodo(true);
        }
    }





    public function consultarIDAdministrador($bdHija) : int
    {
        ConexionSQL::getInstance()->cambiarConexion("B4nC0");
        $query="SELECT id FROM \"users\" WHERE tipo = ? AND despacho = ? ";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute(["Administrador",$bdHija]);
        $datos=$sentencia->fetchAll();
        ConexionSQL::getInstance()->cambiarConexion($bdHija);
        return  $datos[0]['id'];
    }

    public function insertarGestion(GestionModel $gestion)
    {
        self::generarFecha();
        $idUsuario=$gestion->getIdUsuario();
        $idCliente=$gestion->getClienteUnico();
        $titAval=$gestion->getTitAval();
        $idTipoGestion=$gestion->getIdTipoGestion();
        $idTipoGestionSSL=$gestion->getIdTipoGestionSSL();
        $comentario=$gestion->getComentario();
        $migrado=true;
        $query="INSERT INTO \"Gestion\" (id_usuario,id_cliente,tit_aval, id_tipo_gestion,id_tipo_gestion_ssl,comentario,
                         created_at,updated_at,fecha_hora_contactar,migrado)
                VALUES(:id_usuario,:id_cliente,:tit_aval, :id_tipo_gestion,:id_tipo_gestion_ssl,:comentario,
                       :created_at,:updated_at,:fecha_hora_contactar,:migrado)";

        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->bindParam(':id_usuario',$idUsuario,PDO::PARAM_INT);
        $sentencia->bindParam(':id_cliente',$idCliente,PDO::PARAM_STR);
        $sentencia->bindParam(':tit_aval',$titAval,PDO::PARAM_STR);
        $sentencia->bindParam(':id_tipo_gestion',$idTipoGestion,PDO::PARAM_INT);
        $sentencia->bindParam(':id_tipo_gestion_ssl',$idTipoGestionSSL,PDO::PARAM_INT);
        $sentencia->bindParam(':comentario',$comentario,PDO::PARAM_STR);
        $sentencia->bindParam(':created_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':updated_at',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':fecha_hora_contactar',$this->fecha,PDO::PARAM_STR);
        $sentencia->bindParam(':migrado',$migrado,PDO::PARAM_BOOL);
        $r=$sentencia->execute();
        if(!$r)
        {
            ConexionSQL::getInstance()->setSeInsertoTodo(true);
        }
    }

    public function consultaIDCodigoResultado($cadena)
    {
        $query="SELECT id_tipo_gestion FROM \"Tipo_Gestion\" WHERE nombre_gestion = ? ";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$cadena]);
        $datos=$sentencia->fetchAll();
        return  $datos[0]['id_tipo_gestion'];
    }

    public function existeCliente($cadena)
    {
        $existe=false;
        $query="SELECT * FROM \"Cliente\" WHERE id_cliente = ? ";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$cadena]);
        $datos=$sentencia->fetchAll();
        if(count($datos)>0)
        {
            $existe=true;
        }
        return  $existe;
    }


    public function existeClienteEnRelacionAval($cadena)
    {
        $query="SELECT id_aval FROM \"Relacion_Cliente_Aval\" WHERE id_cliente = ?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$cadena]);
        $datos=$sentencia->fetchAll();
        if(count($datos)>0)
        {
            return $datos[0]["id_aval"];
        }
        return  -1;
    }



    public function obtenerRelacionClienteAval()
    {
        $query="SELECT id_cliente,id_aval FROM \"Relacion_Cliente_Aval\"";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
    }




    public function existeTelefonoIDCliente($numTel)
    {
        $query="SELECT id_tel FROM \"Telefono_Cliente\" WHERE numero_tel = ?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$numTel]);
        $datos=$sentencia->fetchAll();
        if(count($datos)>0)
        {
            return $datos[0]["id_tel"];
        }
        return  -1;
    }


    public function existeTelefonoIDAval($numTel)
    {
        $query="SELECT id_telefono FROM \"Telefono_Aval\" WHERE numero_tel = ?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$numTel]);
        $datos=$sentencia->fetchAll();
        if(count($datos)>0)
        {
            return $datos[0]["id_telefono"];
        }
        return  -1;
    }




    public function existeRelacionTelefonoIDCliente($idTel,$idCliente)
    {
        $query="SELECT * FROM \"Relacion_Cliente_Telefono\" WHERE id_cliente = ? AND id_telefono=?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$idCliente,$idTel]);
        $datos=$sentencia->fetchAll();
        return count($datos);
    }


    public function existeRelacionTelefonoIDAval($idTel,$idAval)
    {
        $query="SELECT * FROM \"Telefono_Aval\" WHERE id_aval = ? AND id_telefono=?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$idAval,$idTel]);
        $datos=$sentencia->fetchAll();
        return count($datos);
    }


    public function obtenerIdDireccionPorAval($idAval)
    {
        $query="SELECT id_direccion FROM \"Aval\" WHERE id_aval = ?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute([$idAval]);
        $datos=$sentencia->fetchAll();
        return $datos[0]["id_direccion"];
    }

    public function consultaClientesBDMySQL()
    {
        $query="SELECT * FROM azteca";
        $sentencia=ConexionMySQL::getInstance()->getConexion()->prepare($query);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }


    public function actualizarCliente($nombreGrupo, $idGrupo,$idCliente) :void
    {
        $nombreGrupo=trim($nombreGrupo);
        $idGrupo=trim($idGrupo);
        $query="UPDATE \"Cliente\" SET nombre_grupo=?,id_grupo=? WHERE id_cliente=?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $r=$sentencia->execute([$nombreGrupo,$idGrupo,$idCliente]);
        if(!$r)
        {
            echo "nel";
        }
    }

    public function actualizarDireccionU($idCliente)
    {
        $query="UPDATE \"Direccion\" SET zona_geo=null,cuadrante=null WHERE id_cliente=?";
        $sentencia=ConexionSQL::getInstance()->getConexion()->prepare($query);
        $r=$sentencia->execute([$idCliente]);
        if(!$r)
        {
            dd("nel");
        }
        //dd($idCliente,"simon");
    }






}
