<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Estadistica extends Model
{
    public static function get_grafica_general_pasada($id_grafica)
    {
        return
            DB::SELECT(/** @lang text */ '
            SELECT * FROM "Grafica" WHERE id_grafica = ?
            ', [$id_grafica]);
    }

    public static function get_grafica_general($week, $year)
    {
        return
            DB::SELECT(/** @lang text */ '
            SELECT * FROM "Grafica" WHERE extract(\'week\' from fecha) = ?
            AND extract(\'year\' from fecha) = ?
            AND sujeto = \'general\'
            ', [$week, $year]);
    }

    public static function get_grafica_encargado($week, $year)
    {
        $tiene = DB::SELECT(/** @lang text */ '
        SELECT * FROM "Grafica" WHERE extract(\'week\' from fecha) = ?
        AND extract(\'year\' from fecha) = ?
        AND sujeto = \'encargado\'
        ', [$week, $year]);
        
        if (empty($tiene)) {
            return false;
        } else {
            return true;
        }
    }
}
