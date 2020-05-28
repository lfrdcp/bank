<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\CalendarioPagos;
use App\Gestion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        BaseDinamica::connexionDynamicSon();
        $arreglo = [];
        $total = 0;
        if ($request->tipo == 'hoy') {
            $notas = Gestion::obtenerGestionesGestorNotificacion(auth()->id(), date("Y-m-d"));
        } else {
            $notas = Gestion::obtenerGestionesGestorNotificacion(auth()->id(), $request->fecha);
        }
        for ($i = 0; $i < count($notas); $i++) {
            $hora = substr($notas[$i]->fecha_hora_contactar, 10, 6);
            $id_cliente = $notas[$i]->id_cliente;
            $nombre = $notas[$i]->nombre;
            $comentario = $notas[$i]->comentario;
            $id_gestion = $notas[$i]->id_gestion;
            $check = $notas[$i]->check;
            $arreglo[$i] = array('id_gestion' => $id_gestion, 'titulo' => $nombre, 'nombre' => $comentario, 'id' => $id_cliente, 'hora' => $hora, 'check' => $check);
            if (is_null($check)) {
                $total = $total + 1;
            }
        }
        return array('nota' => $arreglo, 'total' => $total);
    }

    public function check(Request $request)
    {

        BaseDinamica::connexionDynamicSon();
        $check = Gestion::obtenerCheck($request->id);
        if (is_null($check[0]->check)) {
            Gestion::where('id_gestion', $request->id)
                ->update([
                    'check' => true,
                ]);
        } else {
            Gestion::where('id_gestion', $request->id)
                ->update([
                    'check' => null,
                ]);
        }
        $notas = Gestion::obtenerGestionesGestorNotificacion(auth()->id(), date("Y-m-d"));
        $total = 0;
        for ($i = 0; $i < count($notas); $i++) {
            $check = $notas[$i]->check;
            if (is_null($check)) {
                $total = $total + 1;
            }
        }
        return $total;


    }
}
