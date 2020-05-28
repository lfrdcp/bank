<?php

namespace App\Http\Controllers;

use App\Despacho;
use App\User;
use Illuminate\Http\Request;

class GestionarUsuariosController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (empty($request->opcion)) return array('usuarios' => User::obtenerUsuariosPorDespacho());
            if ($request->opcion == 1) return array('usuarios' => User::obtenerUsuarioNombrePorDespacho($request->buscar));
            if ($request->opcion == 2) return array('usuarios' => User::obtenerUsuarioUsernamePorDespacho($request->buscar));
        } else {
            return Despacho::viewAndData('auth.actualizar');
        }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @return \Exception
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $user->tipo = $request->tipo;
            if (!$request->password == '') {
                $user->password = bcrypt($request->password);
            }
            $user->save();
            return response()->json($user);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
