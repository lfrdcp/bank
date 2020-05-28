<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Telefono_Cliente;
use App\Relacion_Cliente_Telefono;

class TelefonoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        if ($request->nuevo === 0) {
            $telAdd = new Telefono_Cliente();
            $telAdd->numero_tel = $request->numero_tel;
            $telAdd->save();
        }

        $rel_cli_tel = new Relacion_Cliente_Telefono();
        $rel_cli_tel->id_cliente = $request->id_cli;
        if ($request->nuevo === 1) {
            $rel_cli_tel->id_telefono = $request->id_tel;
        } else {
            $rel_cli_tel->id_telefono = trim($telAdd->id_tel);
        }
        $rel_cli_tel->save();

        return response()->json('entro', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id_cliente)
    {
        BaseDinamica::connexionDynamicSon();
        if ($request->ajax()) {
            $arregloTelefonos = DB::SELECT(/** @lang text */ 'SELECT numero_tel,id_tel FROM "Telefono_Cliente" 
                                INNER JOIN "Relacion_Cliente_Telefono" ON "Telefono_Cliente".id_tel = "Relacion_Cliente_Telefono".id_telefono 
                                WHERE "Relacion_Cliente_Telefono".id_cliente = ?',[$id_cliente]);
            $todos = DB::SELECT('SELECT id_tel,numero_tel FROM "Telefono_Cliente"');
            $arreglo = array('telefonos' => $arregloTelefonos, 'todos' => $todos);
            return response()->json($arreglo);
        } else {
            return Telefono_Cliente::viewAndData('Telefono.index', compact('id_cliente'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        BaseDinamica::connexionDynamicSon();
        Telefono_Cliente::where('id_tel', $id)->update(['numero_tel' => $request->numero_tel]);
        $telUp = DB::table('Telefono_Cliente')->where('id_tel', $id)->first();
        return response()->json($telUp);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_telefono, Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        Relacion_Cliente_Telefono::where(['id_telefono' => $id_telefono, 'id_cliente' => $request->id_cliente])->delete();
        /*Telefono_Cliente::where('id_tel', $id)->delete();*/
        return response()->json('Eliminado', 200);
    }
}
