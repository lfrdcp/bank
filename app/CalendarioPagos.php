<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModeloEstandar;
use Illuminate\Support\Facades\DB;

class CalendarioPagos extends Model
{

    use ModeloEstandar;
    protected $primaryKey = 'id_calendario';
    protected $table = 'CalendarioPagos';


    public static function obtenerPagos($folio)
    {
        $pagos = DB::SELECT(/** @lang text */ 'SELECT * FROM "CalendarioPagos" WHERE folio = ? ORDER BY id_calendario', [$folio]);
        for ($i = 0; $i < count($pagos); $i++) {
            $pagos[$i]->pago_esperado = number_format($pagos[$i]->pago_esperado, 2, '.', '.');

            if (!is_null($pagos[$i]->pagado)) {
                if ($pagos[$i]->pagado) {
                    $pagos[$i]->pagado = 'Si';
                } else {
                    $pagos[$i]->pagado = 'No';
                }
            }
        }

        return $pagos;
    }

    public static function obtenerCalendarioPagosSuperYAdmin()
    {
        return DB::SELECT
        (/** @lang text */ '
            SELECT "Convenio".convenio_estado, "CalendarioPagos".*,"Cliente".nombre,"Convenio"."folioGen" FROM "CalendarioPagos"
            INNER JOIN "Convenio" ON "CalendarioPagos".folio = "Convenio".id_convenio
            INNER JOIN "Gestion" ON "Convenio".id_gestion = "Gestion".id_gestion
            INNER JOIN "Cliente" ON "Cliente".id_cliente =  "CalendarioPagos".id_cliente
            WHERE "Convenio".convenio_estado = TRUE OR "Convenio".convenio_estado is null  
            ');
    }

    public static function obtenerCalendarioPagosGestorNotificaciones($id_usuario)
    {
        return DB::SELECT
        (/** @lang text */ '
             SELECT "Convenio".convenio_estado, "CalendarioPagos".*,"Cliente".nombre,"Convenio"."folioGen" FROM "CalendarioPagos"
            INNER JOIN "Convenio" ON "CalendarioPagos".folio = "Convenio".id_convenio
            INNER JOIN "Gestion" ON "Convenio".id_gestion = "Gestion".id_gestion
            INNER JOIN "Cliente" ON "Cliente".id_cliente =  "CalendarioPagos".id_cliente
			WHERE convenio_estado = TRUE AND pagado is null
			AND substr(concat("CalendarioPagos".fecha_pago_esperada),1,10) = substr(concat(now()),1,10)
			AND "Gestion".id_usuario = ?',
            [$id_usuario]
        );
    }

    public static function obtenerCalendarioPagosGestor($id_usuario)
    {
        return DB::SELECT
        (/** @lang text */ '
            SELECT "Convenio".convenio_estado, "CalendarioPagos".*,"Cliente".nombre,"Convenio"."folioGen" FROM "CalendarioPagos"
            INNER JOIN "Convenio" ON "CalendarioPagos".folio = "Convenio".id_convenio
            INNER JOIN "Gestion" ON "Convenio".id_gestion = "Gestion".id_gestion
            INNER JOIN "Cliente" ON "Cliente".id_cliente =  "CalendarioPagos".id_cliente
            WHERE "Gestion".id_usuario = ?
            AND "Convenio".convenio_estado != FALSE 
            ',
            [$id_usuario]
        );
    }

    public static function acumulado_por_encargado($week, $year, $encargado)
    {
        $acumulado = DB::SELECT
        (/** @lang text */ '
            SELECT sum(pago_realizado) FROM "CalendarioPagos" 
            INNER JOIN "Convenio" ON "CalendarioPagos".folio = "Convenio".id_convenio
            INNER JOIN "Gestion" ON "Convenio".id_gestion = "Gestion".id_gestion
            INNER JOIN "Cliente" ON "Gestion".id_cliente = "Cliente".id_cliente
            WHERE extract(\'week\' from "CalendarioPagos" .fecha_pago_realizada) = ?
            AND extract(\'year\' from "CalendarioPagos" .fecha_pago_realizada) = ?
            AND "Cliente".encargado = ?',
            [$week, $year, $encargado]
        );
        if (is_null($acumulado[0]->sum)) {
            return 0;
        } else {
            return $acumulado[0]->sum;
        }
    }

    public static function obtenerCheck($id_calendario)
    {
        return DB::SELECT(/** @lang text */ 'SELECT "check" FROM "CalendarioPagos" WHERE id_calendario = ? ', [$id_calendario]);
    }
}
