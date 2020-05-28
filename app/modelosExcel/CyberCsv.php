<?php


namespace App\modelosExcel;


use App\BaseDinamica;
use App\Gestion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CyberCsv implements FromCollection, ShouldAutoSize
{

    private $fecha;


    public function __construct(string $fecha)
    {
        $this->fecha = $fecha;

    }

    private function obtenerIdDespacho()
    {


    }

    /**
     * @return Collection
     */
    public function collection()
    {
        // TODO: Implement collection() method.
        $collection = collect($this::prepararDatos($this->fecha));
        return $collection;
    }

    public static function prepararDatos($fecha)
    {

        Config::set("database.connections.pgsql.host", 'localhost');
        Config::set("database.connections.pgsql.database", 'B4nC0');
        Config::set("database.connections.pgsql.username", 'B4nC0');
        Config::set("database.connections.pgsql.password", '');
        Schema::connection('pgsql')->getConnection()->reconnect();

        $nombreBD = "%" . auth()->user()->despacho . "%";
        $idDespacho = DB::select('SELECT LPAD(id_despacho_externo::text, 8, \'0\') as id_despacho_externo FROM "Despacho" WHERE nombre LIKE ?', [$nombreBD])[0]->id_despacho_externo;

        BaseDinamica::connexionDynamicSon();
        $vector = [];
        $contador = -1;
        $datos = Gestion::obtenerDatosCyber($fecha);



        for ($i = 0; $i < count($datos); $i++) {
            if (strlen(trim($datos[$i]->numero_tel)) == 10 || strlen(trim($datos[$i]->numero_tel)) == 7) {
                $datos[$i]->numero_tel = '052' . $datos[$i]->numero_tel;
            }

            $dia = substr($datos[$i]->fecha, 8, 2);
            $mes = substr($datos[$i]->fecha, 5, 2);
            $anio = substr($datos[$i]->fecha, 0, 4);
            $datos[$i]->fecha = $mes . $dia . $anio;
            $comentario = str_replace(",", " ", $datos[$i]->comentario);
            if (strlen($datos[$i]->comentario) < 56) {
                $contador = $contador + 1;
                $dato =
                    'a' .
                    '|' . trim($datos[$i]->id_cliente) .
                    '|' . $datos[$i]->fecha .
                    '|' . '00000' .
                    '|' . $datos[$i]->tit_aval .
                    '|' . trim($datos[$i]->nombre_gestion) .
                    '|' . '00' .
                    '|' . $idDespacho .
                    '|' . preg_replace("/\r|\n/", "", $comentario) .
                    '|' . trim($datos[$i]->numero_tel) .
                    '|' . '00000000';
                $vector[$contador] = [$dato];
            } else {
                $comentario_auxilio = $comentario;
                $ciclo = strlen($comentario_auxilio) / 56;
                for ($j = 0; $j < $ciclo; $j++) {
                    if (strlen($comentario_auxilio) > 56) {
                        $tam = strlen($contador + 2);
                        $aux_consecutivo = $contador + 2;
                        if ($tam < 5) {
                            $ceros = 5 - $tam;
                            $aux_ceros = '';
                            for ($k = 0; $k < $ceros; $k++) {
                                $aux_ceros = $aux_ceros . '0';
                            }
                            $aux_consecutivo = $aux_ceros . ($contador + 2);
                        }

                        $dato =
                            'a' .
                            '|' . trim($datos[$i]->id_cliente) .
                            '|' . $datos[$i]->fecha .
                            '|' . $aux_consecutivo .
                            '|' . $datos[$i]->tit_aval .
                            '|' . trim($datos[$i]->nombre_gestion) .
                            '|' . '00' .
                            '|' . $idDespacho .
                            '|' . preg_replace("/\r|\n/", "", substr($comentario_auxilio, 0, 56)) .
                            '|' . trim($datos[$i]->numero_tel) .
                            '|' . '00000000';
                        $contador = $contador + 1;
                        $vector[$contador] = [$dato];
                        $comentario_auxilio = substr($comentario_auxilio, 56);
                    } else {
                        if (strlen($comentario_auxilio) < 56) {
                            $tam = strlen($contador + 2);
                            $aux_consecutivo = $contador + 2;
                            if ($tam < 5) {
                                $ceros = 5 - $tam;
                                $aux_ceros = '';
                                for ($k = 0; $k < $ceros; $k++) {
                                    $aux_ceros = $aux_ceros . '0';
                                }
                                $aux_consecutivo = $aux_ceros . ($contador + 2);
                            }

                            $dato =
                                'a' .
                                '|' . trim($datos[$i]->id_cliente) .
                                '|' . $datos[$i]->fecha .
                                '|' . $aux_consecutivo .
                                '|' . $datos[$i]->tit_aval .
                                '|' . trim($datos[$i]->nombre_gestion) .
                                '|' . '00' .
                                '|' . $idDespacho .
                                '|' . preg_replace("/\r|\n/", "", substr($comentario_auxilio, 0, 56)) .
                                '|' . trim($datos[$i]->numero_tel) .
                                '|' . '00000000';
                            $contador = $contador + 1;
                            $vector[$contador] = [$dato];
                        }
                    }

                }
            }
        }
        return $vector;
    }

}

