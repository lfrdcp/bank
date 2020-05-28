<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pago extends Model
{
    protected $primaryKey = 'id_pago';
    protected $table = 'Pago';

    public static function obtenerPago($buscarCliente)
    {
        return DB::SELECT('SELECT * FROM "Pago" WHERE id_cliente = ?', [$buscarCliente]);
    }
}
