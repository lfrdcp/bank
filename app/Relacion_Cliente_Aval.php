<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Relacion_Cliente_Aval extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'Relacion_Cliente_Aval';

    protected $fillable = ['id_cliente', 'id_aval'];

    public static function tieneAval($id_cliente, $id_aval)
    {
        return DB::select('SELECT * FROM "Relacion_Cliente_Aval" WHERE id_cliente = ? AND id_aval = ?', [$id_cliente, $id_aval]);
    }
}
