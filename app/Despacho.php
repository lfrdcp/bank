<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModeloEstandar;
use Illuminate\Support\Facades\DB;

class Despacho extends Model
{
    use ModeloEstandar;
    protected $primaryKey = 'id_despacho';
    protected $table = 'Despacho';

    public static function obtenerDespachos()
    {
        $despachos = DB::SELECT('SELECT * FROM "Despacho"');
        for ($i = 0; $i < count($despachos); $i++) {
            $despachos[$i]->fecha = self::quitarHoraAFechas($despachos[$i]->fecha);
        }
        return $despachos;
    }

    private static function quitarHoraAFechas($fecha)
    {
        return substr($fecha, 0, 10);
    }


    public static function obtenerIdDespacho()
    {
        $id = DB::SELECT('SELECT id_despacho_externo FROM "Despacho" WHERE nombre = ?', [auth()->user()->despacho]);
        return $id[0]->id_despacho_externo;
    }
}
