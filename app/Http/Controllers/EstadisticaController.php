<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\CalendarioPagos;
use App\Cliente;
use App\Estadistica;
use App\Grafica;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {
        BaseDinamica::connexionDynamicSon();
        $date = new DateTime();
        $week = $date->format("W");
        $year = $date->format("Y");

        $grafica_general = Estadistica::get_grafica_general($week, $year);
        if (!empty($grafica_general)) {
            $grafica_general = $grafica_general[0];
            $permiso_general = true;
        } else {
            $permiso_general = false;
            $grafica_general = null;
        }

        if (Estadistica::get_grafica_encargado($week, $year)) {
            $permiso_encargado = true;
            $encargados = null;
        } else {
            $encargados = Cliente::obtener_encargados();
            if (!empty($encargados)) {
                $permiso_encargado = false;
            } else {
                $permiso_encargado = false;
                $encargados = null;
            }
        }

        $data = array(
            'week' => $week,
            'anio' => $year,
            'permiso_general' => $permiso_general,
            'grafica_general' => $grafica_general,
            'permiso_encargado' => $permiso_encargado,
            'encargados' => $encargados
        );

        return Grafica::viewAndData('Estadistica.index', $data);
    }

    public function grafica_todo(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        $grafica_general_actual = Grafica::grafica_general_actual();
        $graficas_generales = Grafica::graficas_generales();
        $data = array(
            'acumulado' => $grafica_general_actual[0]->acumulado,
            'meta' => $grafica_general_actual[0]->monto,
            'graficas' => $graficas_generales
        );
        if ($request->ajax()) {
            return response()->json($data, 200);
        }
        return Grafica::viewAndData('Estadistica.grafica_todo', $data);
    }

    public function grafica_gestor_pasado(Request $request, $fecha)
    {
        BaseDinamica::connexionDynamicSon();
        if ($request->ajax()) {
            $data = Grafica::graphic_manager_past($fecha);
            return Grafica::generate_array_associative_to_manager($data);
        } else {
            return Grafica::viewAndData('Estadistica.Gestor.pasada', $fecha);
        }
    }

    public function grafica_gestor_index(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        if ($request->ajax()) {
            $data = Grafica::graphic_manager_present();
            return Grafica::generate_array_associative_to_manager($data);
        } else {
            $data = Grafica::graphics_managers();
            return Grafica::viewAndData('Estadistica.Gestor.index', $data);
        }
    }


    public function grafica_encargado_pasado(Request $request, $fecha)
    {
        BaseDinamica::connexionDynamicSon();
        if ($request->ajax()) {
            $data = Grafica::grafica_encargado_pasado($fecha);
            return Grafica::generate_array_associative($data);
        } else {
            return Grafica::viewAndData('Estadistica.Encargado.pasada', $fecha);
        }
    }

    public function grafica_encargado_index(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        if ($request->ajax()) {
            $data = Grafica::grafica_encargado_actual();
            return Grafica::generate_array_associative($data);
        } else {
            $data = Grafica::graficas_encargados();
            return Grafica::viewAndData('Estadistica.Encargado.index', $data);
        }
    }


    public function grafica_encargado(Request $request)
    {
        $fecha = date('Y-m-d H:i:s', strtotime("now"));
        $tam = $request->input('tam');

        BaseDinamica::connexionDynamicSon();
        $date = new DateTime();
        $week = $date->format("W");
        $year = $date->format("Y");
        DB::table('Grafica')->insert([
            [
                'fecha' => $fecha,
                'sujeto' => 'encargado',
                'monto' => 0,
                'acumulado' => 0
            ]
        ]);

        for ($i = 0; $i < $tam; $i++) {
            $acumulado = CalendarioPagos::acumulado_por_encargado($week, $year, $request->input($i . '-'));

            DB::table('Grafica')->insert([
                [
                    'fecha' => $fecha,
                    'sujeto' => $request->input($i . '-'),
                    'monto' => $request->input('' . $i),
                    'acumulado' => $acumulado
                ]
            ]);
        }

        return redirect('estadistica')->with('msj', 'Monto capturado correctamente');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            BaseDinamica::connexionDynamicSon();
            $date = new DateTime();
            $week = $date->format("W");
            $year = $date->format("Y");

            $acumulado = DB::SELECT(/** @lang text */ 'SELECT sum(pago_realizado) FROM "CalendarioPagos" 
                WHERE extract(\'week\' from fecha_pago_realizada) = ?
                AND extract(\'year\' from fecha_pago_realizada) = ?',
                [$week, $year]);

            if (empty($acumulado[0]->sum) || is_null($acumulado[0]->sum)) {
                $acumulado[0]->sum = 0;
            }

            $grafica = new Grafica();
            $grafica->fecha = date('Y-m-d H:i:s', strtotime("now"));
            $grafica->sujeto = 'general';
            $grafica->monto = $request->monto;
            $grafica->acumulado = $acumulado[0]->sum;
            $grafica->save();
            return redirect('estadistica')->with('msj', 'Monto capturado correctamente');
        } catch (\Exception $exception) {
            return redirect('estadistica')->with('msj', 'Algo salio mal, vuelve a capturar los datos');
        }

    }

    public function grafica_general_pasada($id_grafica)
    {
        BaseDinamica::connexionDynamicSon();
        $datos = Estadistica::get_grafica_general_pasada($id_grafica);
        return Grafica::viewAndData('Estadistica.grafica_general_pasada', $datos[0]);
    }

    public function grafica_general_index(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        $grafica_general_actual = Grafica::grafica_general_actual();
        $graficas_generales = Grafica::graficas_generales();
        $data = array(
            'acumulado' => $grafica_general_actual[0]->acumulado,
            'meta' => $grafica_general_actual[0]->monto,
            'graficas' => $graficas_generales
        );
        if ($request->ajax()) {
            return response()->json($data, 200);
        }
        return Grafica::viewAndData('Estadistica.grafica_general_index', $data);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        BaseDinamica::connexionDynamicSon();
        try {
            DB::table('Grafica')
                ->where('id_grafica', $id)
                ->update(['monto' => $request->monto]);
            return redirect('estadistica')->with('msj', 'Monto actualizado correctamente');
        } catch (\Exception $exception) {
            return redirect('estadistica')->with('msj', 'Algo salio mal, vuelve a editar el monto');
        }

    }

    public function grafica_gestor_encargado_index(Request $request)
    {
        BaseDinamica::connexionDynamicSon();

        if ($request->ajax()) {
            $data = Grafica::graphic_manager_present();
            return Grafica::generate_array_associative_to_manager($data);

        } else {
            $data = Grafica::graphics_managers();
            return Grafica::viewAndData('Estadistica.Gestor.index', $data);
        }

        /*return Grafica::viewAndData('Estadistica.Encargado_gestor.grafica_gestor_encargado_index');*/
    }

}
