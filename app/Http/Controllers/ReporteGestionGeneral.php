<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\Despacho;
use App\modelosExcel\GestionGeneral;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ReporteGestionGeneral extends Controller
{
    public function reporteGestionGeneral(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        return Excel::download(new GestionGeneral($request->fechaBuscar, $request->filtrado, $request->filtrado2, $request->idUsuario), 'reporte_gestion.csv');
    }



    public function obtenerIdDespacho()
    {
        return Despacho::obtenerIdDespacho();
    }
}
