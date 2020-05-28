<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\Direccion;
use App\Trabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
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
        if ($request->ajax()) {
            BaseDinamica::connexionDynamicSon();
            try {
                $id_direccion = DB::table('Direccion')->insertGetId(array(
                    'cuadrante' => $request->cuadrante,
                    'zona_geo' => $request->zona_geo,
                    'direccion' => $request->direccion,
                    'num_ext' => $request->num_ext,
                    'num_int' => $request->num_int,
                    'tipo_direccion' => 'trabajo',
                    'cp' => $request->cp,
                    'colonia' => $request->colonia,
                    'poblacion' => $request->poblacion,
                    'estado' => $request->estado,
                    'id_cliente' => $request->id_cliente,
                ), 'id_direccion');


                $trabajo = new Trabajo();
                $trabajo->num_tel = $request->num_tel;
                $trabajo->id_direccion = $id_direccion;
                $trabajo->id_cliente = $request->id_cliente;
                $trabajo->save();

                $msj = 'Agregado';
            } catch (\Exception $exception) {
                $msj = $exception;
            }
            return response()->json($msj, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if ($request->ajax()) {
            BaseDinamica::connexionDynamicSon();
            return response()->json(Direccion::obtenerDireccionClienteTrabajo($id), 200);
        } else {
            return Trabajo::viewAndData('Trabajo.index', $id);
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

        try{
            Direccion::where(
                'id_direccion', $id
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
            $msj = "Direccion actualizada";
        }catch (\Exception $exception){
            $msj = $exception;
        }
        return response()->json($msj, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BaseDinamica::connexionDynamicSon();
        Direccion::where(['id_direccion' => $id])->delete();
        Trabajo::where(['id_direccion' => $id])->delete();
        return response()->json('Direccion de trabajo eliminada', 200);
    }
}
