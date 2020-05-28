<?php

namespace App\Http\Controllers;

use App\ConexionMySQL;
use App\ConsultaSQL;
use App\DataGeneral;
use App\ProcesadorData;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\VarDumper\Cloner\Data;

class VaciarBaseDatosController extends Controller
{
    private $nombreArchivo;

    public function vaciarBase(Request $request)
    {

        if ($request->hasFile('archivo')) {
            Validator::make(
                [
                    'file' => $request->archivo,
                    'extension' => strtolower($request->archivo->getClientOriginalExtension()),
                ],
                [
                    'file' => 'required',
                    'extension' => 'required|in:csv,xlsx',
                ]
            );
        } else {
            if ($request->opcion == 1 || $request->opcion == 2) {
                return redirect('base_datos')->with('msj', 'Debe seleccionar un archivo');
            } else if ($request->opcion == 3 || $request->opcion == 4) {
                return redirect('reporte_llamada')->with('msj', 'Debe seleccionar un archivo');
            }
        }


        $bdHija = auth()->user()->despacho;
        $id_usuario = ConsultaSQL::getInstance()->consultarIDAdministrador($bdHija);
        ini_set('MAX_EXECUTION_TIME', '-1');
        set_time_limit(0);

        if ($request->opcion != DataGeneral::PASAR_BASE) {
            $this->subirArchivo($bdHija);
            $archivo = "tmp_excel/" . $bdHija . "/" . $this->nombreArchivo;//request -> archivo
        }


        $procesarData = new ProcesadorData();
        $procesar = true;
        switch ($request->opcion) {
            case DataGeneral::TIPOSLC: //1
                $procesarData = new ProcesadorData($archivo, DataGeneral::TIPOSLC, $id_usuario);
                if (!$procesarData->validarArchivo()) {
                    echo "archivo no valido";
                    $procesar = false;
                }
                break;
            case DataGeneral::TIPOSCYBER: //2
                $procesarData = new ProcesadorData($archivo, DataGeneral::TIPOSCYBER, $id_usuario);
                if (!$procesarData->validarArchivo()) {
                    echo "archivo no valido";
                    $procesar = false;
                }

                break;
            case DataGeneral::EXCEL_MANUAL: //3
                $procesarData = new ProcesadorData($archivo, DataGeneral::EXCEL_MANUAL, $id_usuario);
                if (!$procesarData->validarArchivo()) {
                    echo "archivo no valido";
                    $procesar = false;
                }
                break;
            case DataGeneral::EXCEL_AUTOMATICO: //4
                $procesarData = new ProcesadorData($archivo, DataGeneral::EXCEL_AUTOMATICO, $id_usuario);
                if (!$procesarData->validarArchivo()) {
                    echo "no valido";
                    $procesar = false;
                    if (is_file($archivo)) {
                        unlink($archivo);
                    }
                } else {
                    echo "valid";
                }
                break;

            case DataGeneral::PASAR_BASE: //10
                $procesarData = new ProcesadorData("s", DataGeneral::PASAR_BASE, $id_usuario);
                break;
            case DataGeneral::PASAR_TELEFONOS: //11
                 $procesarData = new ProcesadorData($archivo, DataGeneral::PASAR_TELEFONOS, $id_usuario);
                break;
        }

        if ($procesar) {
            if($request->opcion==DataGeneral::PASAR_TELEFONOS) {
                $procesarData->leerDatosExcel();

                //return redirect('home')->with('msj', 'La información se migró al sistema correctamente');
            }
            else{
                $procesarData->procesarDatos();
                return;
                return redirect('home')->with('msj', 'La información se migró al sistema correctamente');
            }




            //if($request->opcion==DataGeneral::PASAR_BASE)
            //{
                //$procesarData->insertaGestionesdeBDvieja($bdHija);
                //return;
            //}
            /*
             if ($procesarData->getTodoSeInserto()) {

            } else {
                return redirect('home')->with('msj', 'Algo salio mal, por favor verifica el CSV o XLSX y vuelve a subirlo');
            }
             */
        }

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
            if (!is_dir("tmp_excel/" . $despacho)) {
                mkdir("tmp_excel/" . $despacho);
            }
            ftp_put($con_id, '/' . $despacho . '/' . $this->nombreArchivo, $source_file, FTP_BINARY);
        }
    }
}
