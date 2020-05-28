<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tipo_Gestion_ssl extends Model
{
    protected $primaryKey = 'id_tipo_gestion_ssl';
    protected $table = 'Tipo_Gestion_ssl';

    public static function obtenerTipoGestionSsl()
    {
        return DB::SELECT('SELECT * FROM "Tipo_Gestion_ssl"');
    }
}
