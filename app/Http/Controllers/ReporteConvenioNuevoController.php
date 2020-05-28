<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\Constante;
use App\Convenio;
use App\modelosExcel\ConvenioNuevo;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReporteConvenioNuevoController extends Controller
{

    //reporte todos
    public function reporteConvenioNuevo(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        return Excel::download(new ConvenioNuevo(Constante::$TODOS, $request->fechaBuscar, $request->filtrado, $request->filtrado2, $request->idUsuario, $request->encargado), 'reporte_nuevo_convenio_liquidacion.csv');
    }

    public function reporteSoloConvenios(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        return Excel::download(new ConvenioNuevo(Constante::$CONVENIO, $request->fechaBuscar, $request->filtrado, $request->filtrado2, $request->idUsuario, $request->encargado), 'reporte_nuevo_convenio_liquidacion.csv');
    }

    public function reporteSoloPagoIntencion(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        return Excel::download(new ConvenioNuevo(Constante::$INTENCION, $request->fechaBuscar, $request->filtrado, $request->filtrado2, $request->idUsuario, $request->encargado), 'reporte_nuevo_convenio_liquidacion.csv');
    }

    public function reporteSoloPagoLiquidaciones(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        return Excel::download(new ConvenioNuevo(Constante::$LIQUIDACION, $request->fechaBuscar, $request->filtrado, $request->filtrado2, $request->idUsuario, $request->encargado), 'reporte_nuevo_convenio_liquidacion.csv');
    }


    public function obtenerUsuarios()
    {
        $despacho = auth()->user()->despacho;
        $usuarios = User::obtenerUsuariosGestores($despacho);
        $items = [];
        for ($i = 0; $i < count($usuarios); $i++) {
            $iniciales = substr($usuarios[$i]->name, 0, 1) . '' . substr($usuarios[$i]->last_name, 0, 1);
            $items[$i] = array(
                'nombre' => $usuarios[$i]->name . ' ' . $usuarios[$i]->last_name,
                'iniciales' => $iniciales,
                'id' => $usuarios[$i]->id,
                'tipo' => $usuarios[$i]->tipo);
        }
        return response()->json($items, 200);
    }

    public function rutaPrueba()
    {
        BaseDinamica::connexionDynamicSon();
        $vector = [];
        $datos=Convenio::generarExcelNuevo(Constante::$TODOS,'2019-11', 'mes', 'todos', null, null);
        $resultArray = json_decode(json_encode($datos), true);

        return dd($resultArray);
    }


}
