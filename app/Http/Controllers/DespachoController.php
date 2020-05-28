<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\Despacho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DespachoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Despacho::obtenerDespachos();
        } else {
            return Despacho::viewAndData('Despacho.index');
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
     * @return array
     */
    public function store(Request $request)
    {
        try {

            $nombreBD = strtolower($request->nombre);

            DB::statement("CREATE DATABASE {$nombreBD} WITH OWNER = \"B4nC0\" ENCODING = 'UTF8' CONNECTION LIMIT = -1;");

            Config::set("database.connections.pgsql.host", 'localhost');
            Config::set("database.connections.pgsql.database", $nombreBD);
            Config::set("database.connections.pgsql.username", 'B4nC0');
            Config::set("database.connections.pgsql.password", '');
            Schema::connection('pgsql')->getConnection()->reconnect();

            Artisan::call('migrate', array(
                '--database' => 'pgsql',
                '--env' => 'local',
                '--seed' => true,
                '--path' => 'database/tablasDinamicas'
            ));

            Config::set("database.connections.pgsql.host", 'localhost');
            Config::set("database.connections.pgsql.database", $nombreBD);
            Config::set("database.connections.pgsql.username", 'B4nC0');
            Config::set("database.connections.pgsql.password", '');
            Schema::connection('pgsql')->getConnection()->reconnect();


            $this::connexionDynamicFather();
            $despacho = new Despacho();
            $despacho->nombre = $nombreBD;
            $despacho->fecha = $request->fecha;
            $despacho->costo = $request->costo;
            $despacho->pago = $request->pago;
            $despacho->metodo = $request->metodo;
            $despacho->id_despacho_externo = $request->idExterno;
            $despacho->save();

            return response()->json(['despacho' => $despacho, 'correcto' => true]);
        } catch (\Exception $e) {
            return response()->json(['despacho' => $e, 'correcto' => false]);
        }


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
        Despacho::where('id_despacho', $id)
            ->update([
                'fecha' => $request->fecha,
                'costo' => $request->costo,
                'pago' => $request->pago,
                'metodo' => $request->metodo,
            ]);
        $despacho = DB::table('Despacho')->where('id_despacho', $id)->first();
        return response()->json($despacho);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Despacho::where('id_despacho', $id)->delete();
    }

    public function connexionDynamicFather()
    {
        Config::set("database.connections.pgsql.host", 'localhost');
        Config::set("database.connections.pgsql.database", 'B4nC0');
        Config::set("database.connections.pgsql.username", 'B4nC0');
        Config::set("database.connections.pgsql.password", '');
        Schema::connection('pgsql')->getConnection()->reconnect();
    }
}
