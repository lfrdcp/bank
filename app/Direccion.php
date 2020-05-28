<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModeloEstandar;
use Illuminate\Support\Facades\DB;

class Direccion extends Model
{
    use ModeloEstandar;
    protected $primaryKey = 'id_direccion';
    protected $table = 'Direccion';

    protected $fillable = [
        'cuadrante',
        'zona_geo',
        'direccion',
        'num_ext',
        'num_int',
        'tipo_direccion',
        'cp',
        'colonia',
        'poblacion',
        'estado',
        'id_cliente'
    ];


    public static function obtenerDireccionClienteCasa($id_cliente)
    {
        return DB::SELECT('SELECT * FROM "Direccion" WHERE id_cliente = ? AND tipo_direccion = ?', [$id_cliente, 'casa']);
    }

    public static function obtenerDireccionClienteTrabajo($id_cliente)
    {
        $trabajo = DB::SELECT('SELECT * FROM "Trabajo" WHERE id_cliente = ?', [$id_cliente]);
        $direccion = [];
        if (!empty($trabajo)) {
            for ($i = 0; $i < count($trabajo); $i++) {
                $direccionAux[$i] = DB::SELECT('SELECT * FROM "Direccion" WHERE id_direccion = ?', [$trabajo[$i]->id_direccion]);
                $direccion[$i] = $direccionAux[$i][0];
            }
        }
        $datos = array('trabajo' => $trabajo, 'direccion' => $direccion);
        return $datos;
    }

    public static function obtenerDireccionCliente($id_cliente)
    {
        return DB::SELECT('SELECT * FROM "Direccion" WHERE id_cliente = ?', [$id_cliente]);
    }

    public static function compararDirecciones($opcion, $request)
    {
        $reqCom = strtolower(
            $request->cuadrante .
            $request->zona_geo .
            $request->direccion .
            $request->num_ext .
            $request->num_int .
            $request->tipo_direccion .
            $request->cp .
            $request->colonia .
            $request->poblacion .
            $request->estado);
        $reqCom = str_replace(' ', '', $reqCom);


        switch ($opcion) {
            case 0:
                $direcciones = DB::SELECT('SELECT * FROM "Direccion" WHERE id_cliente = ? AND tipo_direccion = ?', [$request->id_cliente, $request->tipo_direccion]);
                break;
            case 1:
                $direcciones = DB::SELECT('SELECT * FROM "Direccion" WHERE id_direccion = ? AND tipo_direccion = ?', [$request->id_direccion, 'aval']);
                break;
        }


        if (!empty($direcciones)) {
            for ($i = 0; $i < count($direcciones); $i++) {
                $dirCom = strtolower($direcciones[$i]->cuadrante .
                    $direcciones[$i]->zona_geo .
                    $direcciones[$i]->direccion .
                    $direcciones[$i]->num_ext .
                    $direcciones[$i]->num_int .
                    $direcciones[$i]->tipo_direccion .
                    $direcciones[$i]->cp .
                    $direcciones[$i]->colonia .
                    $direcciones[$i]->poblacion .
                    $direcciones[$i]->estado);
                $dirCom = str_replace(' ', '', $dirCom);
                switch ($opcion) {
                    case 0:
                        if ($reqCom == $dirCom) return $direcciones[$i]->id_direccion;
                        break;
                    case 1:
                        if ($reqCom == $dirCom) return
                            array(
                                'id_direccion' => $direcciones[$i]->id_direccion,
                                'id_cliente' => $direcciones[$i]->id_cliente
                            );
                        break;
                }
            }
            return null;
        } else {
            return null;
        }
    }
}
