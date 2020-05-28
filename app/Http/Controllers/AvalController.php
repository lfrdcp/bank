<?php

namespace App\Http\Controllers;

use App\Aval;
use App\BaseDinamica;
use App\Direccion;
use App\Relacion_Cliente_Aval;
use App\Telefono_Aval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AvalController extends Controller
{
    public static function editarNombre(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        Aval::where('id_aval', $request->id_aval)->update(['nombre_aval' => $request->nombre_aval]);
        return response()->json('Nombre actualizado', 200);
    }

    public static function editarDireccion(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        Direccion::where(
            'id_direccion', $request->id_direccion
        )
            ->update([
                'cuadrante' => $request->cuadrante,
                'zona_geo' => $request->zona_geo,
                'direccion' => $request->direccion,
                'num_ext' => $request->num_ext,
                'num_int' => $request->num_int,
                'tipo_direccion' => $request->tipo_direccion,
                'cp' => $request->cp,
                'colonia' => $request->colonia,
                'poblacion' => $request->poblacion,
                'estado' => $request->estado
            ]);

        return response()->json('Direccion actualizada', 200);
    }

    public static function editarTelefono(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        Telefono_Aval::where('id_telefono', $request->id_telefono)->update(['numero_tel' => $request->numero_tel]);
        return response()->json('Telefono actualizado', 200);
    }

    public static function agregarTelefono(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        $telAval = new Telefono_Aval();
        $telAval->id_aval = $request->id_aval;
        $telAval->numero_tel = $request->numero_tel;
        $telAval->save();
        return response()->json('Telefono agregado', 200);
    }

    public static function eliminarAval(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        Relacion_Cliente_Aval::where(['id_cliente' => $request->id_cliente, 'id_aval' => $request->id_aval])->delete();
        return response()->json('Aval eliminado', 200);
    }
}
