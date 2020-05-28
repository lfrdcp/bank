<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\Documentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocumentacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        BaseDinamica::connexionDynamicSon();
        $documentacion = DB::SELECT(/** @lang text */ 'SELECT * FROM "Documento"');
        return Documentacion::viewAndData('Documentacion.index', $documentacion);
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
        $bdHija = auth()->user()->despacho;
        BaseDinamica::connexionDynamicSon();
        $archivo = $request->file('archivo');
        $this->subirArchivo($bdHija);


        $name = time() . $archivo->getClientOriginalName();
        $archivo->move(public_path() . '/' . auth()->user()->despacho . '/', $name);


        $tablaDocumentacion = new Documentacion();
        $tablaDocumentacion->ruta = $name;
        $tablaDocumentacion->nombre = $request->input('nombre');
        $tablaDocumentacion->save();
        return redirect()->action('DocumentacionController@index')->with('msj', 'Se ha subido el archivo exitosamente');

    }

    private function subirArchivo($despacho)
    {
        $ftp_server = "127.0.0.1";
        $ftp_usuario = "admin";
        $ftp_password = "vb75jkl32";
        $con_id = ftp_connect($ftp_server);
        $login_result = ftp_login($con_id, $ftp_usuario, $ftp_password);
        if ($login_result && $con_id) {
            explode(".", $_FILES['archivo']['name']);
            $source_file = $_FILES['archivo']['tmp_name'];
            $nombre_aux = $_FILES["archivo"]["name"];
            $this->nombreArchivo = time() . $nombre_aux;
            if (!is_dir("tmp" . $despacho)) {
                mkdir("tmp" . $despacho);
            }
            ftp_put($con_id, '/' . $despacho . '/' . $this->nombreArchivo, $source_file, FTP_BINARY);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
