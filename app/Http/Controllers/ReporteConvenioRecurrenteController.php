<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\modelosExcel\ConvenioRecurrente;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReporteConvenioRecurrenteController extends Controller
{
    public function reporteConvenioRecurrente(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        return Excel::download(new ConvenioRecurrente($request->fechaBuscar, $request->filtrado, $request->filtrado2, $request->idUsuario, $request->encargado), 'reporte_nuevo_convenio_liquidacion.csv');
    }
}
