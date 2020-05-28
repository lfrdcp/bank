<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModeloEstandar;
use Illuminate\Support\Facades\DB;

class Aval extends Model
{
    use ModeloEstandar;
    protected $primaryKey = 'id_aval';
    protected $table = 'Aval';

    public static function obtenerTodoAvales($id_cliente)
    {
        $id_aval = DB::SELECT('SELECT * FROM "Relacion_Cliente_Aval" WHERE id_cliente = ?', [$id_cliente]);

        if (!(empty($id_aval))) {
            for ($i = 0; $i < count($id_aval); $i++) {
                $datosAval[$i] = DB::SELECT('SELECT * FROM "Aval" WHERE id_aval = ?', [$id_aval[$i]->id_aval]);
                $direccionAval[$i] = DB::SELECT('SELECT * FROM "Direccion" WHERE id_direccion = ?', [$datosAval[$i][0]->id_direccion]);

                $telefonosAval[$i] = DB::SELECT('SELECT * FROM "Telefono_Aval" WHERE id_aval = ?', [$id_aval[$i]->id_aval]);
                $auxListaClientes[$i] = DB::SELECT('SELECT * FROM "Relacion_Cliente_Aval" WHERE id_aval = ?', [$id_aval[$i]->id_aval]);
            }

            for ($i = 0; $i < count($auxListaClientes); $i++) {
                for ($j = 0; $j < count($auxListaClientes[$i]); $j++) {
                    $listaClientes[$i][$j] = DB::SELECT('SELECT * FROM "Cliente" WHERE id_cliente = ?', [$auxListaClientes[$i][$j]->id_cliente]);
                }
            }

            return $data = array(
                'direccionAval' => $direccionAval,
                'datosAval' => $datosAval,
                'telefonosAval' => $telefonosAval,
                'auxListaClientes' => $auxListaClientes,
                'listaClientes' => $listaClientes);

        } else {
            return $data = array(
                'direccionAval' => null,
                'datosAval' => null,
                'telefonosAval' => null,
                'auxListaClientes' => null,
                'listaClientes' => null);
        }
    }

    public static function obtenerAvalesUnCliente($id_cliente)
    {
        $id_aval = DB::SELECT('SELECT * FROM "Relacion_Cliente_Aval" WHERE id_cliente = ?', [$id_cliente]);
        $data = '';
        if (!(empty($id_aval))) {
            for ($i = 0; $i < count($id_aval); $i++) {
                $datosAval[$i] = DB::SELECT('SELECT * FROM "Aval" WHERE id_aval = ?', [$id_aval[$i]->id_aval]);
                $direcciones[$i] = DB::SELECT('SELECT * FROM "Direccion" WHERE id_direccion = ?', [$datosAval[$i][0]->id_direccion]);
                $telefono[$i] = DB::SELECT('SELECT * FROM "Telefono_Aval" WHERE id_aval = ?', [$id_aval[$i]->id_aval]);
            }
            $data = array(
                'datos' => $datosAval,
                'telefono' => $telefono,
                'direccion' => $direcciones
            );
        }
        return $data;
    }

    //todo: mejorar lógica
    public static function existeDireccion($request)
    {
        $ids = DB::SELECT('SELECT id_direccion,id_aval FROM "Aval" WHERE nombre_aval = ?', [$request->nombre_aval]);
        $yaexiste = false;
        $idAval = null;

        if (!empty($ids)) {
            for ($i = 0; $i < count($ids); $i++) {


                $arreglo = array('id_direccion' => $ids[$i]->id_direccion);

                $requestAux = $request->all();

                $requestAux = array_merge($requestAux, $arreglo);
                $requestAux = (object)$requestAux;

                $vector = Direccion::compararDirecciones(1, $requestAux);
                if (!is_null($vector)) {
                    if ($vector['id_cliente'] != $request->id_cliente) {
                        $idAval = $ids[$i]->id_aval;
                    } else {
                        $yaexiste = true;
                    }
                }
            }


            if (!is_null($idAval)){
                return $idAval;
            }
            if ($yaexiste){
                return 'ya existe';
            }
        }
        return null;
    }
}
