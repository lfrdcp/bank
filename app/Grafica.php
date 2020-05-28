<?php

namespace App;

use App\Traits\ModeloEstandar;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Grafica extends Model
{
    use ModeloEstandar;
    protected $primaryKey = 'id_grafica';
    protected $table = 'Grafica';

    /**
     * @return array
     */
    public static function graphics_managers(): array
    {
        return
            DB::SELECT(/** @lang text */ '
            SELECT DISTINCT fecha FROM "Grafica" WHERE sujeto = \'gestor\'
            ');
    }

    public static function graphic_manager_present(): array
    {
        $date = new DateTime();
        $week = $date->format("W");
        $year = $date->format("Y");
        $fecha =
            DB::SELECT(/** @lang text */ '
            SELECT fecha FROM "Grafica"
            WHERE extract(\'week\' from fecha) = ?
            AND extract(\'year\' from fecha) = ?
            AND sujeto = \'gestor\'
            ', [$week, $year]);
        return
            DB::SELECT(/** @lang text */ '
            SELECT * FROM "Grafica"
            WHERE extract(\'week\' from fecha) = ?
            AND extract(\'year\' from fecha) = ?
            AND sujeto != \'gestor\'
            AND fecha = ?
            ORDER BY acumulado DESC
            ', [$week, $year, $fecha[0]->fecha]);
    }

    public static function generate_array_associative_to_manager($data): array
    {

        $array_acumulados = [];
        $array_acumulados_2 = [];
        $array_nombres = [];
        $array_nombres2 = [];
        $total = 0;
        for ($i = 0; $i < count($data); $i++) {
            $total = $total + $data[$i]->acumulado;
            $fecha = $data[$i]->fecha;
        }
        for ($i = 0; $i < count($data); $i++) {

            $acumulados = array($data[$i]->acumulado);
            $array_acumulados = array_merge($array_acumulados, $acumulados);
            if ($total > 0) {
                $array_acumulados_2[$i] = round(($data[$i]->acumulado * 100) / $total);
            }

            $nombres = array($data[$i]->sujeto);
            $array_nombres = array_merge($array_nombres, $nombres);
            $array_nombres2[$i] = $data[$i]->sujeto;
        }


        $array_acumulados = array('name' => 'Cobrado', 'data' => $array_acumulados);
        $array_nombres = array('categories' => $array_nombres);

        $series[0] = $array_acumulados;

        $arreglo_final[0] = $series;
        $arreglo_final[1] = $array_nombres;
        $arreglo_final[2] = $array_acumulados_2;
        $arreglo_final[3] = $total;
        $arreglo_final[4] = $array_nombres2;
        $arreglo_final[5] = $fecha;
        return $arreglo_final;
    }

    /**
     * @param $data
     * @return array
     */
    public static function generate_array_associative($data): array
    {
        $array_montos = [];
        $array_acumulados = [];
        $array_nombres = [];
        for ($i = 0; $i < count($data); $i++) {

            $montos = array($data[$i]->monto);
            $array_montos = array_merge($array_montos, $montos);

            $acumulados = array($data[$i]->acumulado);
            $array_acumulados = array_merge($array_acumulados, $acumulados);

            $nombres = array($data[$i]->sujeto);
            $array_nombres = array_merge($array_nombres, $nombres);
        }

        $array_montos = array('name' => 'Meta', 'data' => $array_montos);
        $array_acumulados = array('name' => 'Acumulado', 'data' => $array_acumulados);
        $array_nombres = array('categories' => $array_nombres);
        $series[0] = $array_montos;
        $series[1] = $array_acumulados;

        $arreglo_final[0] = $series;
        $arreglo_final[1] = $array_nombres;

        return $arreglo_final;
    }

    /**
     * @return array
     */
    public static function graficas_encargados(): array
    {
        return
            DB::SELECT(/** @lang text */ '
            SELECT DISTINCT fecha FROM "Grafica" WHERE sujeto = \'encargado\'
            ');
    }


    public static function graphic_manager_past($fecha): array
    {
        return
            DB::SELECT(/** @lang text */ '
            SELECT * FROM "Grafica" WHERE
            sujeto != \'gestor\'
            AND fecha = ?
            ORDER BY acumulado DESC
            ', [$fecha]);
    }


    /**
     * @param $fecha
     * @return array
     */
    public static function grafica_encargado_pasado($fecha): array
    {
        return
            DB::SELECT(/** @lang text */ '
            SELECT * FROM "Grafica" WHERE 
            sujeto != \'encargado\'
            AND fecha = ?
            ORDER BY acumulado DESC
            ', [$fecha]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function grafica_encargado_actual(): array
    {
        $date = new DateTime();
        $week = $date->format("W");
        $year = $date->format("Y");
        $fecha =
            DB::SELECT(/** @lang text */ '
            SELECT fecha FROM "Grafica" WHERE extract(\'week\' from fecha) = ?
            AND extract(\'year\' from fecha) = ?
            AND sujeto = \'encargado\'
            ', [$week, $year]);
        return
            DB::SELECT(/** @lang text */ '
            SELECT * FROM "Grafica" WHERE extract(\'week\' from fecha) = ?
            AND extract(\'year\' from fecha) = ?
            AND sujeto != \'encargado\'
            AND fecha = ?
            ORDER BY acumulado DESC
            ', [$week, $year, $fecha[0]->fecha]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function grafica_general_actual(): array
    {
        $date = new DateTime();
        $week = $date->format("W");
        $year = $date->format("Y");
        return
            DB::SELECT(/** @lang text */ '
            SELECT * FROM "Grafica" WHERE extract(\'week\' from fecha) = ?
            AND extract(\'year\' from fecha) = ?
            AND sujeto = \'general\'
            ', [$week, $year]);
    }

    /**
     * @return array
     */
    public static function graficas_generales(): array
    {
        return
            DB::SELECT(/** @lang text */ '
            SELECT * FROM "Grafica" WHERE sujeto = \'general\'
            ');
    }
}
