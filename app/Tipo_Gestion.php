<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tipo_Gestion extends Model
{
    protected $primaryKey = 'id_tipo_gestion';
    protected $table = 'Tipo_Gestion';

    public static function obtenerTipoGestion()
    {
        return DB::SELECT('SELECT * FROM "Tipo_Gestion"');
    }
}
