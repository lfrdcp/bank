<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\GestionTrait;
use PhpParser\Node\Expr\Array_;

class Convenio extends Model
{
    use GestionTrait;
    protected $primaryKey = 'id_gestion';
    protected $table = 'Convenio';

    public static function obtenerConveniosTodo()
    {
        return DB::SELECT('SELECT * FROM "Convenio"');
    }

    public static function obtenerConveniosCliente($id_cliente)
    {
        return DB::SELECT('SELECT * FROM "Convenio" WHERE id_cliente =  ?', [$id_cliente]);
        /*return DB::table('Convenio')->where('id_cliente', $id_cliente);*/
    }


    public static function obtenerConvenio($id_cliente)
    {
        $convenio = DB::SELECT(/** @lang text */ '
            SELECT 
            CASE
            WHEN convenio_estado = TRUE
            THEN \'CONVENIO ACTIVO\'
            WHEN convenio_estado = FALSE
            THEN \'CONVENIO CANCELADO\'
            WHEN convenio_estado IS NULL
            THEN \'CONVENIO PENDIENTE\'
            END AS status, *
            FROM "Convenio" WHERE id_cliente = ?
            AND id_convenio=(SELECT MAX(id_convenio) FROM "Convenio" where id_cliente = ? )', [$id_cliente, $id_cliente]);
        if (empty($convenio)) {
            return null;
        } else {
            return $convenio[0];
        }
    }

    public static function calcularTotalPagado($id_gestion)
    {
        $totales = DB::SELECT('SELECT * FROM "Convenio" WHERE id_gestion = ?', [$id_gestion]);
        $deudaPagada = $totales[0]->deuda_total_original - $totales[0]->deuda_total;
        $deudaTotal = $totales[0]->deuda_total;
        $deudaOriginal = $totales[0]->deuda_total_original;
        return $totales = array('deudaTotal' => $deudaTotal, 'deudaOriginal' => $deudaOriginal, 'deudaPagada' => $deudaPagada);
    }

    public static function validarDatosConvenio()
    {
        return request()->validate([
            'fechaInicial' => 'required',
            'opcionPago' => 'required',
            'pagoInicial' => 'required',
            'deudaTotal' => 'required',
        ], [

            'fechaInicial.required' => 'Favor de capturar la fecha de pago inicial',
            'opcionPago.required' => 'Favor de seleccionar la opción de pago',
            'pagoInicial.required' => 'Favor de capturar el pago inicial',
            'deudaTotal.required' => 'Favor de capturar la deuda total',
        ]);
    }


    public static function obtenerConveniosSuperYAdmin()
    {
        return DB::SELECT('SELECT "Convenio".*,"Cliente".nombre FROM 
        "Convenio" INNER JOIN "Cliente" ON "Convenio".id_cliente = "Cliente".id_cliente
        INNER JOIN "Gestion" ON "Convenio".id_gestion = "Gestion".id_gestion');
    }

    public static function obtenerConveniosGestor($id_usuario)
    {
        return DB::SELECT('SELECT "Convenio".*,"Cliente".nombre FROM 
        "Convenio" INNER JOIN "Cliente" ON "Convenio".id_cliente = "Cliente".id_cliente
        INNER JOIN "Gestion" ON "Convenio".id_gestion = "Gestion".id_gestion 
        WHERE "Gestion".id_usuario = ?', [$id_usuario]);
    }

    public static function generarExcelNuevo(string $tipoReporte, string $fecha, string $filtro_tiempo, string $filtro_persona, int $id_usuario = null, String $encargado = null)
    {
        $consultaPorReporte = "";
        switch ($tipoReporte) {
            case Constante::$LIQUIDACION:
                $consultaPorReporte = "AND substr(\"Convenio\".\"folioGen\",1,1) = 'L'";
                break;
            case Constante::$CONVENIO:
                $consultaPorReporte = "AND substr(\"Convenio\".\"folioGen\",1,1) = 'C'";
                break;
        }
        switch ($filtro_persona) {
            case 'gestor':
                switch ($filtro_tiempo) {
                    case 'dia':
                        $con_liq = [];
                        if ($tipoReporte == Constante::$CONVENIO || $tipoReporte == Constante::$LIQUIDACION || $tipoReporte == Constante::$TODOS) {
                            $con_liq = DB::SELECT(self::consulta_general() . ' ' .
                                'WHERE "Gestion".id_usuario = ?
                            AND substr(concat("Convenio".created_at),1,10) = ? ' . $consultaPorReporte,
                                [$id_usuario, $fecha]);
                        }

                        if ($tipoReporte != Constante::$INTENCION && $tipoReporte != Constante::$TODOS) return $con_liq;

                        $pago_intencion = DB::SELECT(self::consulta_general_reporte_pago_intencion() . ' ' .
                            'WHERE "Gestion".id_usuario = ?
                            AND substr(concat("Intencion".created_at),1,10) = ?',
                            [$id_usuario, $fecha]);

                        return array_merge($con_liq, $pago_intencion);
                        break;
                    case 'semana':
                        $date = new DateTime($fecha);
                        $week = $date->format("W");
                        $year = $date->format("Y");

                        $con_liq = [];
                        if ($tipoReporte == Constante::$CONVENIO || $tipoReporte == Constante::$LIQUIDACION || $tipoReporte == Constante::$TODOS) {
                            $con_liq = DB::SELECT(self::consulta_general() . ' ' .
                                'WHERE "Gestion".id_usuario = ?
                             AND extract(\'week\' from "Convenio".created_at) = ?
                             AND extract(\'year\' from "Convenio".created_at) = ? ' . $consultaPorReporte,
                                [$id_usuario, $week, $year]);
                        }

                        if ($tipoReporte != Constante::$INTENCION && $tipoReporte != Constante::$TODOS) return $con_liq;

                        $pago_intencion = DB::SELECT(self::consulta_general_reporte_pago_intencion() . ' ' .
                            'WHERE "Gestion".id_usuario = ?
                             AND extract(\'week\' from "Intencion".created_at) = ?
                             AND extract(\'year\' from "Intencion".created_at) = ?',
                            [$id_usuario, $week, $year]);
                        return array_merge($con_liq, $pago_intencion);
                        break;
                    case 'mes':
                        $fecha = $fecha . '-01';
                        $date = new DateTime($fecha);
                        $year = $date->format("Y");
                        $month = $date->format("m");

                        $con_liq = [];
                        if ($tipoReporte == Constante::$CONVENIO || $tipoReporte == Constante::$LIQUIDACION || $tipoReporte == Constante::$TODOS) {
                            $con_liq = DB::SELECT(self::consulta_general() . ' ' .
                                'WHERE "Gestion".id_usuario = ?
                             AND extract(\'month\' from "Convenio".created_at) = ?
                             AND extract(\'year\' from "Convenio".created_at) = ? ' . $consultaPorReporte,
                                [$id_usuario, $month, $year]);
                        }

                        if ($tipoReporte != Constante::$INTENCION && $tipoReporte != Constante::$TODOS) return $con_liq;

                        $pago_intencion = DB::SELECT(self::consulta_general_reporte_pago_intencion() . ' ' .
                            'WHERE "Gestion".id_usuario = ?
                             AND extract(\'month\' from "Intencion".created_at) = ?
                             AND extract(\'year\' from "Intencion".created_at) = ?',
                            [$id_usuario, $month, $year]);
                        return array_merge($con_liq, $pago_intencion);

                        break;
                }
                break;
            case 'todos':
                switch ($filtro_tiempo) {
                    case 'dia':
                        $con_liq = [];
                        if ($tipoReporte == Constante::$CONVENIO || $tipoReporte == Constante::$LIQUIDACION || $tipoReporte == Constante::$TODOS) {
                            $con_liq = DB::SELECT(self::consulta_general() . ' ' .
                                'WHERE substr(concat("Convenio".created_at),1,10) = ? ' . $consultaPorReporte,
                                [$fecha]);
                        }
                        if ($tipoReporte != Constante::$INTENCION && $tipoReporte != Constante::$TODOS) return $con_liq;

                        $pago_intencion = DB::SELECT(self::consulta_general_reporte_pago_intencion() . ' ' .
                            'WHERE substr(concat("Intencion".created_at),1,10) = ?',
                            [$fecha]);
                        return array_merge($con_liq, $pago_intencion);
                        break;
                    case 'semana':
                        $date = new DateTime($fecha);
                        $week = $date->format("W");
                        $year = $date->format("Y");
                        $con_liq = [];
                        if ($tipoReporte == Constante::$CONVENIO || $tipoReporte == Constante::$LIQUIDACION || $tipoReporte == Constante::$TODOS) {
                            $con_liq = DB::SELECT(self::consulta_general() . ' ' .
                                'WHERE extract(\'week\' from "Convenio".created_at) = ?
                            AND extract(\'year\' from "Convenio".created_at) = ? ' . $consultaPorReporte,
                                [$week, $year]);
                        }

                        if ($tipoReporte != Constante::$INTENCION && $tipoReporte != Constante::$TODOS) return $con_liq;

                        $pago_intencion = DB::SELECT(self::consulta_general_reporte_pago_intencion() . ' ' .
                            'WHERE extract(\'week\' from "Intencion".created_at) = ?
                            AND extract(\'year\' from "Intencion".created_at) = ?',
                            [$week, $year]);
                        return array_merge($con_liq, $pago_intencion);
                        break;
                    case 'mes':
                        $fecha = $fecha . '-01';
                        $date = new DateTime($fecha);
                        $month = $date->format("m");
                        $year = $date->format("Y");
                        $con_liq = [];
                        if ($tipoReporte == Constante::$CONVENIO || $tipoReporte == Constante::$LIQUIDACION || $tipoReporte == Constante::$TODOS) {

                            $con_liq = DB::SELECT(self::consulta_general() . ' ' .
                                'WHERE extract(\'month\' from "Convenio".created_at) = ?
                            AND extract(\'year\' from "Convenio".created_at) = ? ' . $consultaPorReporte,
                                [$month, $year]);
                        }

                        if ($tipoReporte != Constante::$INTENCION && $tipoReporte != Constante::$TODOS) return $con_liq;

                        $pago_intencion = DB::SELECT(self::consulta_general_reporte_pago_intencion() . ' ' .
                            'WHERE extract(\'month\' from "Intencion".created_at) = ?
                            AND extract(\'year\' from "Intencion".created_at) = ?',
                            [$month, $year]);
                        return array_merge($con_liq, $pago_intencion);
                        break;
                }
                break;
            case 'encargado':
                switch ($filtro_tiempo) {
                    case 'dia':
                        $con_liq = [];
                        if ($tipoReporte == Constante::$CONVENIO || $tipoReporte == Constante::$LIQUIDACION || $tipoReporte == Constante::$TODOS) {
                            $con_liq = DB::SELECT(self::consulta_general() . ' ' .
                                'WHERE substr(concat("Convenio".created_at),1,10) = ?
                            AND encargado = ? ' . $consultaPorReporte, [$fecha, $encargado]);
                        }
                        if ($tipoReporte != Constante::$INTENCION && $tipoReporte != Constante::$TODOS) return $con_liq;

                        $pago_intencion = DB::SELECT(self::consulta_general_reporte_pago_intencion() . ' ' .
                            'WHERE substr(concat("Intencion".created_at),1,10) = ?
                            AND encargado = ?', [$fecha, $encargado]);
                        return array_merge($con_liq, $pago_intencion);
                        break;
                    case 'semana':
                        $date = new DateTime($fecha);
                        $week = $date->format("W");
                        $year = $date->format("Y");

                        $con_liq = [];
                        if ($tipoReporte == Constante::$CONVENIO || $tipoReporte == Constante::$LIQUIDACION || $tipoReporte == Constante::$TODOS) {
                            $con_liq = DB::SELECT(self::consulta_general() . ' ' .
                                'WHERE extract(\'week\' from "Convenio".created_at) = ?
                            AND encargado = ?
                            AND extract(\'year\' from "Convenio".created_at) = ? ' . $consultaPorReporte,
                                [$week, $encargado, $year]);
                        }

                        if ($tipoReporte != Constante::$INTENCION && $tipoReporte != Constante::$TODOS) return $con_liq;

                        $pago_intencion = DB::SELECT(self::consulta_general_reporte_pago_intencion() . ' ' .
                            'WHERE extract(\'week\' from "Intencion".created_at) = ?
                            AND encargado = ?
                            AND extract(\'year\' from "Intencion".created_at) = ? ',
                            [$week, $encargado, $year]);
                        return array_merge($con_liq, $pago_intencion);
                        break;
                    case 'mes':
                        $fecha = $fecha . '-01';
                        $date = new DateTime($fecha);
                        $month = $date->format("m");
                        $year = $date->format("Y");

                        $con_liq = [];
                        if ($tipoReporte == Constante::$CONVENIO || $tipoReporte == Constante::$LIQUIDACION || $tipoReporte == Constante::$TODOS) {
                            $con_liq = DB::SELECT(self::consulta_general() . ' ' .
                                'WHERE extract(\'month\' from "Convenio".created_at) = ?
                            AND encargado = ?
                            AND extract(\'year\' from "Convenio".created_at) = ? ' . $consultaPorReporte,
                                [$month, $encargado, $year]);
                        }
                        if ($tipoReporte != Constante::$INTENCION && $tipoReporte != Constante::$TODOS) return $con_liq;
                        $pago_intencion = DB::SELECT(self::consulta_general_reporte_pago_intencion() . ' ' .
                            'WHERE extract(\'month\' from "Intencion".created_at) = ?
                            AND encargado = ?
                            AND extract(\'year\' from "Intencion".created_at) = ?',
                            [$month, $encargado, $year]);
                        return array_merge($con_liq, $pago_intencion);
                        break;
                }
                break;
        }


    }

    public static function consulta_general(): string
    {
        return /** @lang text */ "
        
            SELECT DISTINCT ON (\"CalendarioPagos\".folio) 
            
            
            concat(\"Convenio\".\"folioGen\",'|',
            CASE WHEN 
            substr(\"Convenio\".\"folioGen\",1,1) = 'C'
            THEN 'CONVENIO|'
            ELSE 'LIQUIDACIÓN|'
            END, 
            CASE WHEN 
            substr(\"Convenio\".\"folioGen\",1,1) = 'C'
            THEN 'PAGO PARCIALIDAD|'
            ELSE 'UN SOLO PAGO|'
            END,\"Gestion\".username,'|',extract('week' from \"Convenio\".created_at),
           '|',substr(concat(\"Convenio\".created_at),1,10),'|',\"Cliente\".id_cliente,'|',\"Cliente\".nombre,
           '|',\"Pago\".\"total\",'|',\"Pago\".saldo,'|',\"Convenio\".numero_pagos,'|',\"Convenio\".primer_pago_cantidad,
           '|',substr(concat(\"Convenio\".primer_pago_fecha),1,10),'|', \"CalendarioPagos\".pago_esperado,'|',
            CASE WHEN 
            \"Convenio\".convenio_estado=TRUE
            THEN 'ACTIVO|'
            ELSE 'CANCELADO|'
            END,
            \"Convenio\".deuda_total_original-\"Convenio\".deuda_total,'|',\"Convenio\".deuda_total,'|',
           \"Cliente\".gerencia,'|',\"Cliente\".encargado,'|',\"Direccion\".zona_geo)
            from \"Convenio\"
            INNER JOIN \"Cliente\" ON \"Cliente\".id_cliente=\"Convenio\".id_cliente
            INNER JOIN \"Pago\" ON \"Pago\".id_cliente=\"Cliente\".id_cliente
            INNER JOIN \"CalendarioPagos\" ON \"Convenio\".id_convenio=\"CalendarioPagos\".folio
            INNER JOIN \"Gestion\" ON \"Convenio\".id_gestion = \"Gestion\".id_gestion
            INNER JOIN \"Direccion\" ON \"Direccion\".id_cliente=\"Cliente\".id_cliente";
    }


    public static function consulta_general_recurrente(): string
    {
        return /** @lang text */ "
        SELECT  
        DISTINCT ON (\"CalendarioPagos\".id_calendario)
        \"CalendarioPagos\".pago_esperado as pagoSemanal,
        \"Convenio\".\"folioGen\",
        CASE WHEN 
        substr(\"Convenio\".\"folioGen\",1,1) = 'C'
        THEN 'CONVENIO'
        ELSE 'LIQUIDACIÓN'
        END as convenio_liquidacion,
        CASE WHEN 
        substr(\"Convenio\".\"folioGen\",1,1) = 'C'
        THEN 'PAGO PARCIALIDAD'
        ELSE 'UN SOLO PAGO'
        END as tipo_gestion,
        substr(\"Convenio\".\"folioGen\",(SELECT LENGTH(\"Convenio\".\"folioGen\")-1)) AS gestor,
        extract('week' from \"Convenio\".created_at) as semana,
        substr(concat(\"Convenio\".created_at),1,10) as FechaGestion ,
        \"Cliente\".id_cliente, \"Cliente\".nombre,\"Pago\".saldo as saldoPlan,
        \"Pago\".\"total\" as saldoTotal,
        \"Convenio\".numero_pagos as cantidadPagos,
        \"Convenio\".primer_pago_cantidad,
        substr(concat(\"Convenio\".primer_pago_fecha),1,10) as fechaPagoInicial,
        CASE WHEN 
        \"Convenio\".convenio_estado=TRUE
        THEN 'ACTIVO'
        ELSE 'CANCELADO'
        END as estatus,
        \"Convenio\".deuda_total_original-\"Convenio\".deuda_total as avanceDelPlan,
        \"Convenio\".deuda_total as porPagar,
        \"Cliente\".gerencia,
        \"Cliente\".encargado,
        \"Direccion\".zona_geo as zona,
        \"CalendarioPagos\".fecha_pago_esperada,
        \"CalendarioPagos\".pago_esperado,
        
        CASE WHEN 
        \"CalendarioPagos\".pagado=TRUE
        THEN 'PAGADO'
        ELSE 'PENDIENTE'
        END as pagado
        
        
        from \"Convenio\" 
        INNER JOIN \"Cliente\" ON \"Cliente\".id_cliente=\"Convenio\".id_cliente
        INNER JOIN \"Pago\" ON \"Pago\".id_cliente=\"Cliente\".id_cliente
        INNER JOIN \"CalendarioPagos\" ON \"Convenio\".id_convenio=\"CalendarioPagos\".folio
        INNER JOIN \"Gestion\" ON \"Convenio\".id_gestion = \"Gestion\".id_gestion
        INNER JOIN \"Direccion\" ON \"Direccion\".id_cliente=\"Cliente\".id_cliente";
    }

    //todo, no se usa
    public static function generarExcelRecurrente(string $fecha, string $filtro_tiempo, string $filtro_persona, int $id_usuario = null, String $encargado = null)
    {
        switch ($filtro_persona) {
            case 'gestor':
                switch ($filtro_tiempo) {
                    case 'dia':
                        $repite = DB::SELECT(self::consulta_general_recurrente() . ' ' . 'WHERE "Gestion".id_usuario = ? AND substr(concat("Convenio".created_at),1,10) = ?', [$id_usuario, $fecha]);
                        $unico = DB::SELECT(self::consulta_general() . ' ' . 'WHERE "Gestion".id_usuario = ? AND substr(concat("Convenio".created_at),1,10) = ?', [$id_usuario, $fecha]);
                        $pagos = self::preparar_datos_recurrentes($repite, $unico);
                        return array('unico' => $unico, 'pagos' => $pagos);
                        break;
                    case 'semana':
                        $date = new DateTime($fecha);
                        $week = $date->format("W");
                        //todo, correjir las semanas en los reportes, validar años
                        $repite = DB::SELECT(self::consulta_general_recurrente() . ' ' . 'WHERE "Gestion".id_usuario = ? AND extract(\'week\' from "Convenio".created_at) = ?', [$id_usuario, $week]);
                        $unico = DB::SELECT(self::consulta_general() . ' ' . 'WHERE "Gestion".id_usuario = ? AND extract(\'week\' from "Convenio".created_at) = ?', [$id_usuario, $week]);
                        $pagos = self::preparar_datos_recurrentes($repite, $unico);
                        return array('unico' => $unico, 'pagos' => $pagos);
                        break;
                    case 'mes':
                        $fecha = $fecha . '-01';
                        $date = new DateTime($fecha);
                        $month = $date->format("m");

                        $repite = DB::SELECT(self::consulta_general_recurrente() . ' ' . 'WHERE "Gestion".id_usuario = ? AND extract(\'month\' from "Convenio".created_at) = ?', [$id_usuario, $month]);
                        $unico = DB::SELECT(self::consulta_general() . ' ' . 'WHERE "Gestion".id_usuario = ? AND extract(\'month\' from "Convenio".created_at) = ?', [$id_usuario, $month]);
                        $pagos = self::preparar_datos_recurrentes($repite, $unico);
                        return array('unico' => $unico, 'pagos' => $pagos);
                        break;
                }
                break;
            case 'todos';
                switch ($filtro_tiempo) {
                    case 'dia':

                        $repite = DB::SELECT(self::consulta_general_recurrente() . ' ' . 'WHERE substr(concat("Convenio".created_at),1,10) = ?', [$fecha]);
                        $unico = DB::SELECT(self::consulta_general() . ' ' . 'WHERE substr(concat("Convenio".created_at),1,10) = ?', [$fecha]);
                        $pagos = self::preparar_datos_recurrentes($repite, $unico);
                        return array('unico' => $unico, 'pagos' => $pagos);
                        break;
                    case 'semana':
                        $date = new DateTime($fecha);
                        $week = $date->format("W");

                        $repite = DB::SELECT(self::consulta_general_recurrente() . ' ' . 'WHERE extract(\'week\' from "Convenio".created_at) = ?', [$week]);
                        $unico = DB::SELECT(self::consulta_general() . ' ' . 'WHERE extract(\'week\' from "Convenio".created_at) = ?', [$week]);
                        $pagos = self::preparar_datos_recurrentes($repite, $unico);
                        return array('unico' => $unico, 'pagos' => $pagos);
                        break;
                    case 'mes':
                        $fecha = $fecha . '-01';
                        $date = new DateTime($fecha);
                        $month = $date->format("m");

                        $repite = DB::SELECT(self::consulta_general_recurrente() . ' ' . 'WHERE extract(\'month\' from "Convenio".created_at) = ?', [$month]);
                        $unico = DB::SELECT(self::consulta_general() . ' ' . 'WHERE extract(\'month\' from "Convenio".created_at) = ?', [$month]);
                        $pagos = self::preparar_datos_recurrentes($repite, $unico);
                        return array('unico' => $unico, 'pagos' => $pagos);
                        break;
                }
                break;
            case 'encargado':
                switch ($filtro_tiempo) {
                    case 'dia':
                        $repite = DB::SELECT(self::consulta_general_recurrente() . ' ' . 'WHERE "Cliente".encargado = ? AND substr(concat("Convenio".created_at),1,10) = ?', [$encargado, $fecha]);
                        $unico = DB::SELECT(self::consulta_general() . ' ' . 'WHERE "Cliente".encargado = ? AND substr(concat("Convenio".created_at),1,10) = ?', [$encargado, $fecha]);
                        $pagos = self::preparar_datos_recurrentes($repite, $unico);
                        return array('unico' => $unico, 'pagos' => $pagos);
                        break;
                    case 'semana':
                        $date = new DateTime($fecha);
                        $week = $date->format("W");

                        $repite = DB::SELECT(self::consulta_general_recurrente() . ' ' . 'WHERE "Cliente".encargado = ? AND extract(\'week\' from "Convenio".created_at) = ?', [$encargado, $week]);
                        $unico = DB::SELECT(self::consulta_general() . ' ' . 'WHERE "Gestion".id_usuario = ? AND extract(\'week\' from "Convenio".created_at) = ?', [$id_usuario, $week]);
                        $pagos = self::preparar_datos_recurrentes($repite, $unico);
                        return array('unico' => $unico, 'pagos' => $pagos);
                        break;
                    case 'mes':
                        $fecha = $fecha . '-01';
                        $date = new DateTime($fecha);
                        $month = $date->format("m");

                        $repite = DB::SELECT(self::consulta_general_recurrente() . ' ' . 'WHERE "Cliente".encargado = ? AND extract(\'month\' from "Convenio".created_at) = ?', [$encargado, $month]);
                        $unico = DB::SELECT(self::consulta_general() . ' ' . 'WHERE "Gestion".id_usuario = ? AND extract(\'month\' from "Convenio".created_at) = ?', [$id_usuario, $month]);
                        $pagos = self::preparar_datos_recurrentes($repite, $unico);
                        return array('unico' => $unico, 'pagos' => $pagos);
                        break;
                }
                break;
        }
    }

    public static function preparar_datos_recurrentes(array $arreglo_repite, array $arreglo_unico): array
    {
        $vector = [];
        $palabra = '';
        for ($i = 0; $i < count($arreglo_unico); $i++) {
            for ($j = 0; $j < count($arreglo_repite); $j++) {
                if ($arreglo_unico[$i]->folioGen == $arreglo_repite[$j]->folioGen) {
                    $palabra = $palabra . '|' . $arreglo_repite[$j]->fecha_pago_esperada . '|' . $arreglo_repite[$j]->pago_esperado . '|' . $arreglo_repite[$j]->pagado;
                }
            }
            $vector[$i] = $palabra;
            $palabra = '';
        }
        return $vector;
    }


    public static function consulta_general_reporte_pago_intencion(): string
    {
        return /** @lang text */ "
        select DISTINCT ON (\"Intencion\".\"folioGen\") 
        concat(\"Intencion\".\"folioGen\",'|','INTENCIÓN','|','UN SOLO PAGO','|',\"Gestion\".username,'|',extract('week' from \"Intencion\".fecha),
		'|',substr(concat(\"Intencion\".created_at),1,10),'|',\"Cliente\".id_cliente,'|',\"Cliente\".nombre,'|',
		\"Pago\".\"total\",'|',\"Pago\".saldo,'|','0','|',\"Intencion\".pago,'|',substr(concat(\"Intencion\".fecha),1,10),'|','0','PAGADO',
		'|', \"Intencion\".pago,'|','0','|',
		\"Cliente\".gerencia,'|',
		\"Cliente\".encargado,'|', \"Direccion\".zona_geo)
		from \"Intencion\" 
		inner join \"Gestion\" on \"Intencion\".id_gestion=\"Gestion\".id_gestion 
		inner join \"Cliente\" on \"Intencion\".id_cliente=\"Cliente\".id_cliente
		inner join \"Pago\" on \"Pago\".id_cliente=\"Cliente\".id_cliente
		inner join \"Direccion\" ON \"Direccion\".id_cliente=\"Cliente\".id_cliente";
    }

}
