<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PDF extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'PDF';

    public static function obtenerPDFs($id_cliente)
    {
        return DB::SELECT(/** @lang text */ 'SELECT ruta, substr(concat("PDF".created_at),1,10) AS fecha FROM "PDF" WHERE id_cliente = ?', [$id_cliente]);
    }

}
