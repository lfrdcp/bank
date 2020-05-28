<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\Cliente;
use App\Gestion;
use App\modelosExcel\CyberCsv;
use App\modelosExcel\CyberXlsx;
use App\modelosExcel\Scl;
use App\modelosExcel\SclXlsx;
use App\ReportePago;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    private $idExternoDespacho;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        BaseDinamica::connexionDynamicSon();
        return Gestion::viewAndData('Reporte.index');
    }


    public function reporteSclCsv(Request $request)
    {
        self::obtenerIdDespachoExterno();
        BaseDinamica::connexionDynamicSon();
        return Excel::download(new Scl($request->fechaBuscar, $this->idExternoDespacho), 'reporteSclCsv.csv');
    }

    public function reporteSclXlsx(Request $request)
    {
        self::obtenerIdDespachoExterno();
        BaseDinamica::connexionDynamicSon();
        return Excel::download(new SclXlsx($request->fechaBuscar, $this->idExternoDespacho), 'reporteSclXlsx.csv');
    }


    public function reporteCyberCsv(Request $request)
    {

        BaseDinamica::connexionDynamicSon();
        return Excel::download(new CyberCsv($request->fechaBuscar), 'reporteSclXlsx.csv');
    }

    public function reporteCyberXlsx(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        return Excel::download(new CyberXlsx($request->fechaBuscar), 'reporteSclXlsx.csv');
    }

    public function obtenerEncargados()
    {
        BaseDinamica::connexionDynamicSon();
        return Cliente::obtener_encargado();
    }

    private function obtenerIdDespachoExterno()
    {
        Config::set("database.connections.pgsql.host", 'localhost');
        Config::set("database.connections.pgsql.database", 'B4nC0');
        Config::set("database.connections.pgsql.username", 'B4nC0');
        Config::set("database.connections.pgsql.password", '');
        Schema::connection('pgsql')->getConnection()->reconnect();
        $nombreBD = "%" . auth()->user()->despacho . "%";
        $idExterno = DB::select('SELECT id_despacho_externo FROM "Despacho" WHERE nombre LIKE ?', [$nombreBD])[0]->id_despacho_externo;
        $this->idExternoDespacho = $idExterno;
    }


    public function reportepagos(Request $request)
    {
        BaseDinamica::connexionDynamicSon();

        return Excel::download(new ReportePago($request->filtrado3,$request->filtrado, $request->fechaBuscar,$request->filtrado2, $request->idUsuario, $request->encargado), 'reporte_de_pagos.csv');
    }

    public function reportePagosNuevos(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        return Excel::download(new ReportePago($request->filtrado3,$request->filtrado, $request->fechaBuscar,$request->filtrado2, $request->idUsuario, $request->encargado,true), 'reporte_de_pagos.csv');
    }

    public function reportePagosRecurrente(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        return Excel::download(new ReportePago($request->filtrado3,$request->filtrado, $request->fechaBuscar,$request->filtrado2, $request->idUsuario, $request->encargado,false,true), 'reporte_de_pagos.csv');
    }



}
