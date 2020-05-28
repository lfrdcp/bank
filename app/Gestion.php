<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ModeloEstandar;
use App\Traits\GestionTrait;
use Illuminate\Support\Facades\DB;

class Gestion extends Model
{
    use ModeloEstandar, GestionTrait;
    protected $primaryKey = 'id_gestion';
    protected $table = 'Gestion';


    public static function validarDatosGestionAval()
    {
        return request()->validate([
            'id_tipo_gestion' => 'required',
            'id_tipo_gestion_ssl' => 'required',
            'tit_aval' => 'required',
            'id_gestionado' => 'required',
            'convenio' => 'required',
            'fechaContactar' => 'required',
            'horaContactar' => 'required',
        ], [
            'id_tipo_gestion.required' => 'Favor de capturar el tipo de gestión',
            'id_tipo_gestion_ssl.required' => 'Favor de capturar el tipo de gestión ssl',
            'tit_aval' => 'required',
            'id_gestionado.required' => 'Favor de capturar el gestionado',
            'convenio.required' => 'Favor de seleccionar el convenio',
            'fechaContactar.required' => 'Favor de capturar la fecha a contactar',
            'horaContactar.required' => 'Favor de capturar la hora a contactar',
        ]);
    }


    public static function validarDatosGestionTitular()
    {
        return request()->validate([
            'id_tipo_gestion' => 'required',
            'id_tipo_gestion_ssl' => 'required',
            'tit_aval' => 'required',

            'convenio' => 'required',
            'fechaContactar' => 'required',
            'horaContactar' => 'required',
        ], [
            'id_tipo_gestion.required' => 'Favor de capturar el tipo de gestión',
            'id_tipo_gestion_ssl.required' => 'Favor de capturar el tipo de gestión ssl',
            'tit_aval' => 'required',

            'convenio.required' => 'Favor de seleccionar el convenio',
            'fechaContactar.required' => 'Favor de capturar la fecha a contactar',
            'horaContactar.required' => 'Favor de capturar la hora a contactar',
        ]);
    }

    public static function obtenerUltimaGestion($id_usuario)
    {
        $max = DB::SELECT('SELECT MAX (id_gestion) FROM "Gestion" WHERE id_usuario = ?', [$id_usuario]);
        return $max[0]->max;
    }

    public static function obtenerFoliosGen($fecha)
    {
        return DB::select('SELECT id_tipo_gestion_ssl,comentario,id_usuario,id_cliente FROM "Gestion" WHERE substr(concat(created_at),1,10) = ?;', [$fecha]);
    }

    public static function obtenerDatosCyber($fecha)
    {
        return DB::select(/** @lang text */ '
         SELECT DISTINCT  ON ("Gestion".id_gestion) "Gestion".id_gestion,
                                    "Gestion".id_cliente,
                                    "Gestion".comentario,
                                    
                                   CASE WHEN 
								"Gestion".tit_aval = \'1\'
									THEN \'LT\'
            						ELSE \'LA\'
								END as tit_aval,
                                    "Gestion".id_usuario,
                                    substr(concat("Gestion".created_at),1,16) as fecha,
                                    "Telefono_Cliente".numero_tel,
                                    "Tipo_Gestion".nombre_gestion
                                    FROM "Gestion" 
                                    FULL OUTER JOIN "Tipo_Gestion" ON "Gestion".id_tipo_gestion = "Tipo_Gestion".id_tipo_gestion
                                    FULL OUTER JOIN "Relacion_Cliente_Telefono" ON "Relacion_Cliente_Telefono".id_cliente = "Gestion".id_cliente
                                    FULL OUTER JOIN "Telefono_Cliente" ON "Telefono_Cliente".id_tel = "Relacion_Cliente_Telefono".id_telefono
                                    WHERE substr(concat("Gestion".created_at),1,10) = ?;
        ', [$fecha]);
    }

    public static function generarExcel(string $fecha, string $filtro_tiempo, string $filtro_persona, int $id_usuario = null)
    {
        switch ($filtro_persona) {
            case 'gestor':
                switch ($filtro_tiempo) {
                    case 'dia':
                        return DB::SELECT(self::consulta_general() . ' ' .
                            'WHERE "Gestion".id_usuario = ?
                            AND substr(concat("Gestion".created_at),1,10) = ?
                            AND  "Gestion"."folioGen" is not null',
                            [$id_usuario, $fecha]);
                        break;
                    case 'semana':
                        $date = new DateTime($fecha);
                        $week = $date->format("W");
                        $year = $date->format("Y");
                        return DB::SELECT(self::consulta_general() . ' ' .
                            'WHERE "Gestion".id_usuario = ?
                            AND extract(\'week\' from "Gestion".created_at) = ?
                            AND extract(\'year\' from "Gestion".created_at) = ?
                            AND  "Gestion"."folioGen" is not null',
                            [$id_usuario, $week, $year]);
                        break;
                    case 'mes':
                        $fecha = $fecha . '-01';
                        $date = new DateTime($fecha);
                        $month = $date->format("m");
                        $year = $date->format("Y");
                        return DB::SELECT(self::consulta_general() . ' ' .
                            'WHERE "Gestion".id_usuario = ?
                            AND extract(\'month\' from "Gestion".created_at) = ?
                            AND extract(\'year\' from "Gestion".created_at) = ?
                            AND  "Gestion"."folioGen" is not null',
                            [$id_usuario, $month, $year]);
                        break;
                }
                break;
            case 'todos';
                switch ($filtro_tiempo) {
                    case 'dia':
                        return DB::SELECT(self::consulta_general() . ' ' .
                            'WHERE substr(concat("Gestion".created_at),1,10) = ?
                            AND "Gestion"."folioGen" is not null',
                            [$fecha]);
                        break;
                    case 'semana':
                        $date = new DateTime($fecha);
                        $week = $date->format("W");
                        $year = $date->format("Y");
                        return DB::SELECT(self::consulta_general() . ' ' .
                            'WHERE extract(\'week\' from "Gestion".created_at) = ?
                            AND extract(\'year\' from "Gestion".created_at) = ?
                            AND "Gestion"."folioGen" is not null',
                            [$week, $year]);
                        break;
                    case 'mes':
                        $fecha = $fecha . '-01';
                        $date = new DateTime($fecha);
                        $month = $date->format("m");
                        $year = $date->format("Y");
                        return DB::SELECT(self::consulta_general() . ' ' .
                            'WHERE extract(\'month\' from "Gestion".created_at) = ?
                            AND extract(\'year\' from "Gestion".created_at) = ?
                            AND "Gestion"."folioGen" is not null',
                            [$month, $year]);
                        break;
                }
                break;
        }
    }

    public static function consulta_general(): string
    {
        return /** @lang text */
            '				SELECT 
				concat("Gestion"."folioGen",\'|\',CASE WHEN 
                tit_aval = \'1\'    
                THEN \'TITULAR\'
                ELSE \'AVAL\' END,\'|\',"Tipo_Gestion".nombre_gestion,\'|\',concat("Tipo_Gestion_ssl".id_tipo_gestion_ssl,\'-\',"Tipo_Gestion_ssl".descripcion_ssl)
					  ,\'|\',substr("Gestion"."folioGen",(SELECT LENGTH("Gestion"."folioGen")-1)),
					  \'|\',substr(concat("Gestion".created_at),1,10),
					  \'|\',substr(concat("Gestion".created_at),11,16),
					  \'|\',"Gestion".id_cliente,\'|\',"Cliente".nombre,\'|\',
					  "Pago".saldo,\'|\',"Pago"."total",
					  \'|\',"Convenio".numero_pagos,\'|\',"Convenio".primer_pago_cantidad,
					  \'|\',substr(concat("Convenio".primer_pago_fecha),1,10),
					  \'|\',"Gestion".comentario,\'|\',"Cliente".gerencia,\'|\',"Cliente".encargado)
				
                FROM "Gestion"
                FULL OUTER JOIN "Tipo_Gestion_ssl" ON "Tipo_Gestion_ssl".id_tipo_gestion_ssl = "Gestion".id_tipo_gestion_ssl
                FULL OUTER JOIN "Tipo_Gestion" ON "Tipo_Gestion".id_tipo_gestion = "Gestion".id_tipo_gestion
                FULL OUTER JOIN "Cliente" ON "Cliente".id_cliente  = "Gestion".id_cliente
                FULL OUTER JOIN "Pago" ON "Pago".id_cliente = "Cliente".id_cliente
                FULL OUTER JOIN "Convenio" ON "Convenio".id_gestion = "Gestion".id_gestion 
				';
    }

    public static function obtenerFoliosConsecutivos($id_cliente)
    {
        return DB::SELECT(/** @lang text */ 'SELECT * FROM "Gestion" WHERE id_cliente = ? ORDER BY "folioGen" DESC', [$id_cliente]);
    }

    public static function obtenerCheck($id_gestion)
    {
        return DB::SELECT(/** @lang text */ 'SELECT "check" FROM "Gestion" WHERE id_gestion = ? ', [$id_gestion]);
    }

    public static function consultaPago(): string
    {
        return /** @lang text */ '
        select 
	    concat(to_char( "CalendarioPagos".fecha_pago_esperada,\'DD/MM/YYYY\'),\'|\', 
        extract(\'week\' from "CalendarioPagos".fecha_pago_esperada),\'|\',
		"Cliente".id_cliente,\'|\',"Cliente".nombre,\'|\',
        "CalendarioPagos".pago_esperado,\'|\',
        "CalendarioPagos".pago_realizado,\'|\',
        CASE WHEN "CalendarioPagos".pago_realizado is null
        THEN \'PENDIENTE|\'
        ELSE
        \'PAGADO|\'
        END,
		CASE WHEN "CalendarioPagos".fecha_pago_esperada="Convenio".primer_pago_fecha 
		THEN \'NUEVO|\'
		WHEN "CalendarioPagos".fecha_pago_esperada!="Convenio".primer_pago_fecha 
		THEN \'RECURRENTE|\'
		END,
        CASE WHEN "Convenio".convenio_estado=TRUE
        THEN  \'ACTIVO| \'
        WHEN "Convenio".convenio_estado=FALSE
        THEN  \'CANCELADO|\'
        ELSE
        \'SIN CONVENIO|\'
        END,
		"Gestion".username,
		\'|\',"Cliente".gerencia,\'|\',"Cliente".encargado) 
        from "Convenio" inner join "Cliente" on
        "Convenio".id_cliente="Cliente".id_cliente
        inner join "Gestion" on "Gestion".id_gestion="Convenio".id_gestion
        inner join "CalendarioPagos" on "CalendarioPagos".folio="Convenio".id_convenio
       ';
    }

    public static function consulta_intencion_reporte_todo(): string
    {
        return /** @lang text */ '
        select concat(to_char( "Intencion".fecha,\'DD/MM/YYYY\'),\'|\', \'0\',\'|\',
		"Cliente".id_cliente,\'|\',"Cliente".nombre,\'|\',"Intencion".pago,\'|\',\'INTENCIÓN|\',
		"Gestion".username,
		\'|\',"Cliente".gerencia,\'|\',"Cliente".encargado)
        from "Intencion" inner join "Cliente" on
        "Intencion".id_cliente="Cliente".id_cliente
        inner join "Gestion" on "Gestion".id_gestion="Intencion".id_gestion
       ';
    }


    public static function reportePago(ReportePago $reportePago)
    {
        $finQuery="";
        $finQuery2PendientesPagados="";
        if($reportePago->isEsNuevo())
        {
            $finQuery="AND \"CalendarioPagos\".fecha_pago_esperada=\"Convenio\".primer_pago_fecha ";
        }
        if($reportePago->isEsRecurrente())
        {
            $finQuery="AND \"CalendarioPagos\".fecha_pago_esperada!=\"Convenio\".primer_pago_fecha ";
        }

        switch ($reportePago->getFiltrado3())
        {
            case Constante::$PAGADO:
                $finQuery2PendientesPagados=" AND \"CalendarioPagos\".pago_realizado is not null";
                break;
            case Constante::$PENDIENTE:
                $finQuery2PendientesPagados=" AND \"CalendarioPagos\".pago_realizado is null";
                break;
        }

        switch ($reportePago->getFiltroPersona())
        {
            case 'todos':
                switch ($reportePago->getFiltrado()) {
                    case 'dia':
                        $con_liq = DB::SELECT(self::consultaPago() . ' ' .
                            'WHERE substr(concat("CalendarioPagos".fecha_pago_esperada),1,10) = ?'.' '.$finQuery.' '.$finQuery2PendientesPagados,
                            [$reportePago->getFecha()]);
                        if($reportePago->isEsRecurrente()) return $con_liq;
                        $intencion = DB::SELECT(self::consulta_intencion_reporte_todo() . ' ' .
                            'WHERE substr(concat("Intencion".fecha),1,10) = ?',
                            [$reportePago->getFecha()]);
                        return array_merge($con_liq,$intencion);
                        break;
                    case 'semana':
                        $date = new DateTime($reportePago->getFecha());
                        $week = $date->format("W");
                        $year = $date->format("Y");

                        $con_liq = DB::SELECT(self::consultaPago() . ' ' .
                            'WHERE extract(\'week\' from "CalendarioPagos".fecha_pago_esperada) = ?
                    AND extract(\'year\' from "CalendarioPagos".fecha_pago_esperada) = ? '.$finQuery.' '.$finQuery2PendientesPagados,
                            [$week, $year]);
                        if($reportePago->isEsRecurrente()) return $con_liq;
                        $intencion = DB::SELECT(self::consulta_intencion_reporte_todo() . ' ' .
                            'WHERE extract(\'week\' from "Intencion".fecha) = ?
                    AND extract(\'year\' from "Intencion".fecha) = ?',
                            [$week, $year]);

                        return array_merge($intencion, $con_liq);
                        break;
                    case 'mes':
                        $fecha = $reportePago->getFecha() . '-01';
                        $date = new DateTime($fecha);
                        $month = $date->format("m");
                        $year = $date->format("Y");
                        $con_liq = DB::SELECT(self::consultaPago() . ' ' .
                            'WHERE extract(\'month\' from "CalendarioPagos".fecha_pago_esperada) = ?
                            AND extract(\'year\' from "CalendarioPagos".fecha_pago_esperada) = ? '.$finQuery.' '.$finQuery2PendientesPagados,
                            [$month, $year]);
                        if($reportePago->isEsRecurrente()) return $con_liq;
                        $intencion = DB::SELECT(self::consulta_intencion_reporte_todo() . ' ' .
                            'WHERE extract(\'month\' from "Intencion".fecha) = ?
                            AND extract(\'year\' from "Intencion".fecha) = ?',
                            [$month, $year]);

                        return array_merge($intencion, $con_liq);
                        break;
                }
                break;
            case 'gestor':
                switch ($reportePago->getFiltrado()) {
                    case 'dia':
                        $con_liq = DB::SELECT(self::consultaPago() . ' ' .
                            'WHERE substr(concat("CalendarioPagos".fecha_pago_esperada),1,10) = ?
                            AND "Gestion".id_usuario=? '.$finQuery.' '.$finQuery2PendientesPagados,
                            [$reportePago->getFecha(), $reportePago->getIdUsuario()]);
                        if($reportePago->isEsRecurrente()) return $con_liq;
                        $intencion = DB::SELECT(self::consulta_intencion_reporte_todo() . ' ' .
                            'WHERE substr(concat("Intencion".fecha),1,10) = ?
                            AND "Gestion".id_usuario=?',
                            [$reportePago->getFecha(), $reportePago->getIdUsuario()]);

                        return array_merge($intencion, $con_liq);
                        break;
                    case 'semana':
                        $date = new DateTime($reportePago->getFecha());
                        $week = $date->format("W");
                        $year = $date->format("Y");
                        $con_liq = DB::SELECT(self::consultaPago() . ' ' .
                            'WHERE extract(\'week\' from "CalendarioPagos".fecha_pago_esperada) = ?
                            AND extract(\'year\' from "CalendarioPagos".fecha_pago_esperada) = ?
                            AND "Gestion".id_usuario=? '.$finQuery.' '.$finQuery2PendientesPagados,
                            [$week, $year, $reportePago->getIdUsuario()]);
                        if($reportePago->isEsRecurrente()) return $con_liq;
                        $intencion = DB::SELECT(self::consulta_intencion_reporte_todo() . ' ' .
                            'WHERE extract(\'week\' from "Intencion".fecha) = ?
                            AND extract(\'year\' from "Intencion".fecha) = ?
                            AND "Gestion".id_usuario=?',
                            [$week, $year, $reportePago->getIdUsuario()]);
                        return array_merge($intencion, $con_liq);
                        break;
                    case 'mes':
                        $fecha = $reportePago->getFecha() . '-01';
                        $date = new DateTime($fecha);
                        $month = $date->format("m");
                        $year = $date->format("Y");
                        $con_liq = DB::SELECT(self::consultaPago() . ' ' .
                            'WHERE extract(\'month\' from "CalendarioPagos".fecha_pago_esperada) = ?
                            AND extract(\'year\' from "CalendarioPagos".fecha_pago_esperada) = ?
                            AND "Gestion".id_usuario=? '.$finQuery.' '.$finQuery2PendientesPagados,
                            [$month, $year, $reportePago->getIdUsuario()]);
                        if($reportePago->isEsRecurrente()) return $con_liq;
                        $intencion = DB::SELECT(self::consulta_intencion_reporte_todo() . ' ' .
                            'WHERE extract(\'month\' from "Intencion".fecha) = ?
                            AND extract(\'year\' from "Intencion".fecha) = ?
                            AND "Gestion".id_usuario=?',
                            [$month, $year, $reportePago->getIdUsuario()]);
                        return array_merge($intencion, $con_liq);
                        break;
                }
                break;
            case 'encargado':
                switch ($reportePago->getFiltrado()) {
                    case 'dia':
                        $con_liq = DB::SELECT(self::consultaPago() . ' ' .
                            'WHERE substr(concat("CalendarioPagos".fecha_pago_esperada),1,10) = ?
                            AND "Cliente".encargado=? '.$finQuery.' '.$finQuery2PendientesPagados,
                            [$reportePago->getFecha(), $reportePago->getEncargado()]);
                        if($reportePago->isEsRecurrente()) return $con_liq;
                        $intencion = DB::SELECT(self::consulta_intencion_reporte_todo() . ' ' .
                            'WHERE substr(concat("Intencion".fecha),1,10) = ?
                            AND "Cliente".encargado=?',
                            [$reportePago->getFecha(), $reportePago->getEncargado()]);

                        return array_merge($intencion, $con_liq);
                        break;
                    case 'semana':
                        $date = new DateTime($reportePago->getFecha());
                        $week = $date->format("W");
                        $year = $date->format("Y");

                        $con_liq = DB::SELECT(self::consultaPago() . ' ' .
                            'WHERE extract(\'week\' from "CalendarioPagos".fecha_pago_esperada) = ?
                            AND extract(\'year\' from "CalendarioPagos".fecha_pago_esperada) = ?
                            AND "Cliente".encargado=? '.$finQuery.' '.$finQuery2PendientesPagados,
                            [$week, $year, $reportePago->getEncargado()]);
                        if($reportePago->isEsRecurrente()) return $con_liq;
                        $intencion = DB::SELECT(self::consulta_intencion_reporte_todo() . ' ' .
                            'WHERE extract(\'week\' from "Intencion".fecha) = ?
                            AND extract(\'year\' from "Intencion".fecha) = ?
                            AND "Cliente".encargado=?',
                            [$week, $year, $reportePago->getEncargado()]);
                        return array_merge($intencion, $con_liq);
                        break;
                    case 'mes':
                        $fecha = $reportePago->getFecha() . '-01';
                        $date = new DateTime($fecha);
                        $month = $date->format("m");
                        $year = $date->format("Y");


                        $con_liq = DB::SELECT(self::consultaPago() . ' ' .
                            'WHERE extract(\'month\' from "CalendarioPagos".fecha_pago_esperada) = ?
                            AND extract(\'year\' from "CalendarioPagos".fecha_pago_esperada) = ?
                            AND "Cliente".encargado=? '.$finQuery.' '.$finQuery2PendientesPagados,
                            [$month, $year, $reportePago->getEncargado()]);
                        if($reportePago->isEsRecurrente()) return $con_liq;
                        $intencion = DB::SELECT(self::consulta_intencion_reporte_todo() . ' ' .
                            'WHERE extract(\'month\' from "Intencion".fecha) = ?
                            AND extract(\'year\' from "Intencion".fecha) = ?
                            AND "Cliente".encargado=?',
                            [$month, $year, $reportePago->getEncargado()]);

                        return array_merge($intencion, $con_liq);
                        break;
                }
                break;
        }
    }
}
