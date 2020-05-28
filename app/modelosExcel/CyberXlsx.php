<?php


namespace App\modelosExcel;


use App\Gestion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CyberXlsx implements FromCollection, ShouldAutoSize, WithHeadings
{


    private $fecha;

    public function __construct(string $fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        // TODO: Implement collection() method.
        // TODO: Implement collection() method.
        $collection = collect($this::prepararDatos($this->fecha));
        return $collection;
    }

    public static function prepararDatos($fecha)
    {
        $vector = [];
        $datos = Gestion::obtenerDatosCyber($fecha);

        for ($i = 0; $i < count($datos); $i++) {

            $auxIdGestion = $datos[$i]->id_gestion;
            $tam = strlen($datos[$i]->id_gestion);

            if ($tam < 5) {
                $ceros = 5 - $tam;
                $auxCeros = '';
                for ($j = 0; $j < $ceros; $j++) {
                    $auxCeros = $auxCeros . '0';
                }

                $auxIdGestion = $auxCeros . $datos[$i]->id_gestion;

            }

            $dato = [
                'a'
                , trim($datos[$i]->id_cliente)
                , $datos[$i]->fecha
                , $auxIdGestion
                , $datos[$i]->tit_aval
                , trim($datos[$i]->nombre_gestion)
                , '00'
                , $datos[$i]->id_usuario
                , $datos[$i]->comentario
                , trim($datos[$i]->numero_tel)
                , '00000000'
            ];
            $vector[$i] = [$dato];
        }
        return $vector;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            'Grupo',
            'Cliente unico',
            'Fecha y Hora de Actividad (mmddyyyy)',
            'No. de secuencia',
            'Codigo de Accion',
            'Codigo de Resultado',
            'Codigo de Carta',
            'Id Agente',
            'Comentario para TXT',
            'Telefono',
            'Extension',
        ];
    }
}
