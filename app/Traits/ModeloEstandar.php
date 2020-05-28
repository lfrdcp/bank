<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait ModeloEstandar
{
    public static function viewAndData($vista, $data = 1)
    {
        $weekMap = [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miercoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sabado',
        ];
        $dayOfTheWeek = Carbon::now()->dayOfWeek;
        $weekday = $weekMap[$dayOfTheWeek];
        $dt = date("W");

        $monthMap = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];
        $mes = $monthMap[date("n")];
        $arrayDate = array('weekday' => $weekday, 'mes' => $mes, 'dt' => $dt);

        return view($vista, compact('data', 'arrayDate'));
    }

    public static function desObj($id, $view)
    {

        self::where('id', $id)->delete();
        return redirect($view)->with('msj', 'ELIMINACIÓN CORRECTA');
    }

    public static function updObj($id, Model $Model, $view)
    {
        self::where('id', $id)->update($Model->attributes);
        return redirect($view)->with('msj', 'ACTUALIZACIÓN CORRECTA');
    }

    public static function creObj(Model $Model, $view)
    {
        self::create($Model->attributes);
        return redirect($view)->with('msj', 'CREACIÓN CORRECTA');
    }


}
