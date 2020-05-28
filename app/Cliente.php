<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModeloEstandar;

use Illuminate\Support\Facades\DB;

class Cliente extends Model
{
    use ModeloEstandar;
    protected $primaryKey = 'id_cliente';
    protected $table = 'Cliente';

    public static function obtenerClientes($id_cliente)
    {
        return DB::SELECT('SELECT * FROM "Cliente" WHERE id_cliente = ?', [$id_cliente]);
    }

    public static function obtenerTelefonosXidCliente($id_cliente)
    {
        return DB::SELECT(/** @lang text */ 'SELECT * FROM "Telefono_Cliente" 
                    INNER JOIN "Relacion_Cliente_Telefono"
                    ON "Telefono_Cliente".id_tel = "Relacion_Cliente_Telefono".id_telefono
                    WHERE "Relacion_Cliente_Telefono".id_cliente = ?', [$id_cliente]);
    }

    public static function obtenerClientesxTel($telefonos)
    {
        $lista = [];
        if (!empty($telefonos)) {
            for ($i = 0; $i < count($telefonos); $i++) {
                $lista[$i] = DB::SELECT(/** @lang text */ '
                SELECT "Cliente".id_cliente,"Cliente".nombre FROM "Cliente"
                INNER JOIN "Relacion_Cliente_Telefono" ON "Relacion_Cliente_Telefono".id_cliente = "Cliente".id_cliente
                INNER JOIN "Telefono_Cliente" ON "Telefono_Cliente".id_tel = "Relacion_Cliente_Telefono".id_telefono
                WHERE "Telefono_Cliente".numero_tel = ?', [$telefonos[$i]->numero_tel]);
            }
        }
        return $lista;
    }

    public static function obtenerPrimerAval($id_cliente)
    {
        $aval = null;
        $id_aval = DB::SELECT('SELECT * FROM "Relacion_Cliente_Aval" WHERE id_cliente = ?', [$id_cliente]);
        if (!(empty($id_aval))) {
            $aval = DB::SELECT('SELECT * FROM "Aval" WHERE id_aval = ?', [$id_aval[0]->id_aval]);
        }
        return $aval;
    }

    public static function validarSoloLetras()
    {
        return request()->validate([
            'buscarCliente' => 'required|regex:/^[a-zA-ZÑñ\s]+$/',
        ], [
            'buscarCliente.required' => 'Favor de capturar letras',
            'buscarCliente.regex' => 'Favor de capturar letras',
        ]);
    }

    public static function validarSoloNumeros()
    {
        return request()->validate([
            'buscarCliente' => 'required|numeric',
        ], [
            'buscarCliente.required' => 'Favor de capturar números',
            'buscarCliente.numeric' => 'Favor de capturar números',
        ]);
    }

    public static function buscarClienteOpc1($id_cliente)
    {
        return DB::select(/** @lang text */ 'SELECT  DISTINCT ON ("Cliente".id_cliente)
        "Cliente".id_cliente,
        "Cliente".nombre,
        "Aval".nombre_aval,
        "Cliente".encargado,
        "Pago".total
        FROM "Cliente" 
        FULL OUTER JOIN "Relacion_Cliente_Aval" ON "Cliente".id_cliente = "Relacion_Cliente_Aval".id_cliente
        FULL OUTER JOIN "Aval" ON "Relacion_Cliente_Aval".id_aval = "Aval".id_aval
        FULL OUTER JOIN "Pago" ON "Cliente".id_cliente = "Pago".id_cliente
        WHERE "Cliente".id_cliente = ?', [$id_cliente]);
    }

    public static function buscarClienteOpc2($nombreCliente)
    {
        $buscarClienteNombre = mb_strtoupper($nombreCliente);
        $buscarClienteNombre = "%" . $buscarClienteNombre . "%";
        return DB::select(/** @lang text */ '
        SELECT DISTINCT ON("Cliente".id_cliente)
        "Cliente".id_cliente,
        "Cliente".nombre,
		"Aval".nombre_aval,
		"Cliente".encargado,
		"Pago".total
        FROM "Cliente" 
        FULL OUTER JOIN "Relacion_Cliente_Aval" ON "Cliente".id_cliente = "Relacion_Cliente_Aval".id_cliente
        FULL OUTER JOIN "Aval" ON "Relacion_Cliente_Aval".id_aval = "Aval".id_aval
        FULL OUTER JOIN "Pago" ON "Cliente".id_cliente = "Pago".id_cliente
        WHERE "Cliente".nombre LIKE ? OR  "Aval".nombre_aval LIKE ?', [$buscarClienteNombre,$buscarClienteNombre]);
    }


    public static function buscarClienteOpc3($telefono)
    {
        $cadena="%".$telefono."%";
        return DB::select(/** @lang text */ 'SELECT  DISTINCT ON ("Cliente".id_cliente)
        "Cliente".id_cliente,
        "Cliente".nombre,
        "Aval".nombre_aval,
        "Cliente".encargado,
        "Pago".total
       FROM "Cliente" FULL OUTER JOIN "Relacion_Cliente_Telefono" ON "Cliente".id_cliente = "Relacion_Cliente_Telefono".id_cliente
        FULL OUTER JOIN "Telefono_Cliente" ON "Relacion_Cliente_Telefono".id_telefono = "Telefono_Cliente".id_tel
        FULL OUTER JOIN "Relacion_Cliente_Aval" ON "Cliente".id_cliente = "Relacion_Cliente_Aval".id_cliente
        FULL OUTER JOIN "Aval" ON "Relacion_Cliente_Aval".id_aval = "Aval".id_aval
        FULL OUTER JOIN "Pago" ON "Cliente".id_cliente = "Pago".id_cliente
        WHERE "Telefono_Cliente".numero_tel LIKE ?', [$cadena]);
    }



    public static function buscarClienteOpc4($id_grupo) //NUMERO DE GRUPO
    {
        $cadena="%".$id_grupo."%";
        $cadena=strtoupper($cadena);
        return DB::select(/** @lang text */ 'SELECT  DISTINCT ON ("Cliente".id_cliente)
        "Cliente".id_cliente,
        "Cliente".nombre,
        "Aval".nombre_aval,
        "Cliente".encargado,
        "Pago".total
        FROM "Cliente" 
        FULL OUTER JOIN "Relacion_Cliente_Aval" ON "Cliente".id_cliente = "Relacion_Cliente_Aval".id_cliente
        FULL OUTER JOIN "Aval" ON "Relacion_Cliente_Aval".id_aval = "Aval".id_aval
        FULL OUTER JOIN "Pago" ON "Cliente".id_cliente = "Pago".id_cliente
        WHERE UPPER("Cliente".id_grupo) LIKE ?', [$cadena]);
    }

    public static function buscarClienteOpc5($nombre_grupo) //NOMBRE DE GRUPO
    {
        $cadena="%".$nombre_grupo."%";
        $cadena=strtoupper($cadena);
        return DB::select(/** @lang text */ 'SELECT  DISTINCT ON ("Cliente".id_cliente)
        "Cliente".id_cliente,
        "Cliente".nombre,
        "Aval".nombre_aval,
        "Cliente".encargado,
        "Pago".total
        FROM "Relacion_Cliente_Aval" FULL OUTER JOIN "Cliente" ON "Cliente".id_cliente = "Relacion_Cliente_Aval".id_cliente
        FULL OUTER JOIN "Aval" ON "Relacion_Cliente_Aval".id_aval = "Aval".id_aval
        FULL OUTER JOIN "Pago" ON "Cliente".id_cliente = "Pago".id_cliente
        WHERE UPPER("Cliente".nombre_grupo) LIKE ?', [$cadena]);
    }

    public static function buscarClienteOpc6($telefono)
    {
        $cadena="%".$telefono."%";
        return DB::select(/** @lang text */ 'SELECT  DISTINCT ON ("Cliente".id_cliente)
        "Cliente".id_cliente,
        "Cliente".nombre,
        "Aval".nombre_aval,
        "Cliente".encargado,
        "Pago".total
       FROM "Cliente" FULL OUTER JOIN "Relacion_Cliente_Telefono" ON "Cliente".id_cliente = "Relacion_Cliente_Telefono".id_cliente
        FULL OUTER JOIN "Telefono_Cliente" ON "Relacion_Cliente_Telefono".id_telefono = "Telefono_Cliente".id_tel
        FULL OUTER JOIN "Relacion_Cliente_Aval" ON "Cliente".id_cliente = "Relacion_Cliente_Aval".id_cliente
        FULL OUTER JOIN "Aval" ON "Relacion_Cliente_Aval".id_aval = "Aval".id_aval
        FULL OUTER JOIN "Pago" ON "Cliente".id_cliente = "Pago".id_cliente
        FULL OUTER JOIN "Telefono_Aval" ON "Aval".id_aval="Telefono_Aval".id_aval
        WHERE "Telefono_Aval".numero_tel LIKE ?', [$cadena]);
    }



    public static function obtener_encargados()
    {
        return DB::SELECT('SELECT DISTINCT ON (encargado) encargado FROM "Cliente" WHERE encargado is not null');
    }


    public static function obtener_encargado()
    {
        return DB::SELECT('SELECT DISTINCT ON (encargado) encargado FROM "Cliente" WHERE encargado is not null');
    }
}
