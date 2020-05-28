<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModeloEstandar;
use Illuminate\Support\Facades\DB;

class Trabajo extends Model
{
    use ModeloEstandar;
    protected $primaryKey = 'id_trabajo';
    protected $table = 'Trabajo';

    public static function obtenerTrabajoCliente($id_cliente)
    {
        return DB::SELECT('SELECT * FROM "Trabajo" WHERE id_cliente = ?', [$id_cliente]);
    }
}
