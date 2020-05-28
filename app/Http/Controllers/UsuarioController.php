<?php

namespace App\Http\Controllers;

use App\Despacho;
use App\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array
     */
    public function prepararDespachos()
    {
        $despachosAux = Despacho::obtenerDespachos();
        for ($i = 0; $i < count($despachosAux); $i++) {
            $despachos[$i] = $despachosAux[$i]->nombre;
        }
        return $despachos;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            if (empty($request->opcion)) return array('usuarios' => User::obtenerUsuariosTodos(), 'despachos' => $this::prepararDespachos());
            if ($request->opcion == 1) return array('usuarios' => User::obtenerUsuarioNombre($request->buscar), 'despachos' => $this::prepararDespachos());
            if ($request->opcion == 2) return array('usuarios' => User::obtenerUsuarioDespacho($request->buscar), 'despachos' => $this::prepararDespachos());
            if ($request->opcion == 3) return array('usuarios' => User::obtenerUsuarioUsername($request->buscar), 'despachos' => $this::prepararDespachos());
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->despacho = $request->despacho;
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
        try {
            User::where('id', $id)->delete();
        } catch (\Exception $e) {
            return $e;
        }
    }
}
