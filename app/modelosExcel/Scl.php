<?php


namespace App\modelosExcel;


use App\Gestion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Scl implements FromCollection, ShouldAutoSize
{
    private $fecha;
    private $idExternoDespacho;

    public function __construct(string $fecha, string $idExternoDespacho)
    {
        $this->fecha = $fecha;
        $this->idExternoDespacho = $idExternoDespacho;
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


    public function prepararDatos($fecha)
    {
        $folios = Gestion::obtenerFoliosGen($fecha);
        $anio = substr($fecha, 0, 4);
        $mes = substr($fecha, 5, 2);
        $dia = substr($fecha, 8, 2);
        $vector1 = [];
        for ($i = 0; $i < count($folios); $i++) {
            $comentario = str_replace(",", " ", $folios[$i]->comentario);
            $dato = trim(
                    $folios[$i]->id_cliente) .
                '|' . $folios[$i]->id_tipo_gestion_ssl .
                '|' . preg_replace("/\r|\n/", "", $comentario) .
                '|' . $this->idExternoDespacho .
                '|' . $this->idExternoDespacho .
                '|' . $dia . '-' . $mes . '-' . $anio;
            $vector1[$i] = [$dato];
        }
        return $vector1;
    }
}
