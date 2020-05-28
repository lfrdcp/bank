<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Intencion extends Model
{
    protected $primaryKey = 'id_intencion';
    protected $table = 'Intencion';

    public static function obtenerIntenciones($id_usuario)
    {
        return DB::SELECT(/** @lang text */ 'SELECT "Intencion".*,"Cliente".nombre FROM "Intencion"
        INNER JOIN "Gestion" ON "Intencion"."folioGen" = "Gestion"."folioGen"
        INNER JOIN "Cliente" ON "Cliente".id_cliente =  "Intencion".id_cliente
        WHERE "Gestion".id_usuario = ?', [$id_usuario]);
    }

    public static function obtenerIntencionesSuperYAdmin()
    {
        return DB::SELECT(/** @lang text */ 'SELECT "Intencion".*,"Cliente".nombre FROM "Intencion"
        INNER JOIN "Cliente" ON "Cliente".id_cliente =  "Intencion".id_cliente');
    }

    public static function obtenerIntencionesCliente($id_cliente)
    {
        return DB::SELECT(/** @lang text */ 'SELECT * FROM "Intencion" WHERE id_cliente = ?', [$id_cliente]);
    }

    public static function obtenerIntencionUltimaCliente($id_cliente)
    {
        $intencion = DB::SELECT(/** @lang text */ 'SELECT * FROM "Intencion" WHERE id_intencion = (select max(id_intencion) from "Intencion") AND id_cliente = ?', [$id_cliente]);
        if (empty($intencion)) {
            return null;
        } else {
            return $intencion[0];
        }
    }
}
