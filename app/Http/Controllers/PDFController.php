<?php

namespace App\Http\Controllers;

use App\Aval;
use App\BaseDinamica;
use App\Cliente;
use App\Convenio;
use App\Direccion;
use App\Gestion;
use App\Gestionado;
use App\Pago;
use App\PDF;
use App\Tipo_Gestion;
use App\Tipo_Gestion_ssl;
use App\Trabajo;
use Illuminate\Http\Request;

class PDFController extends Controller
{

    public function subirpdf(Request $request)
    {

        request()->validate([
            "Documento" => "required|mimes:pdf|max:100000"
        ], [
            'Documento' => 'Favor de subir un pdf',
        ]);


        BaseDinamica::connexionDynamicSon();
        if ($request->hasFile('Documento')) {

            $pdf = $request->file('Documento');
            $name = time() . $pdf->getClientOriginalName();
            $pdf->move(public_path() . '/' . auth()->user()->despacho . '/', $name);


            $tablaPDF = new PDF();
            $tablaPDF->id_cliente = $request->id_cliente;
            $tablaPDF->ruta = $name;
            $tablaPDF->save();

            return redirect()->action('GestionController@show', ['gestion' => $request->id_cliente])->with('msj', 'Se ha subido el PDF');
        } else {
            return redirect()->action('GestionController@show', ['gestion' => $request->id_cliente])->with('msj', 'Algo salio mal, intentalo de nuevo');
        }
    }

}

