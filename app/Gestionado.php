<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gestionado extends Model
{
    protected $primaryKey = 'id_gestionado';
    protected $table = 'Gestionado';


    public static function obtenerTipoGestion()
    {
        return DB::SELECT('SELECT * FROM "Gestionado"');
    }
}
