<?php


namespace App\modelosExcel;

use App\Gestion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SclXlsx implements FromCollection, WithHeadings, ShouldAutoSize
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

    /**
     * @return array
     */
    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            'NUMERO CLIENTE ÚNICO',
            'IDENTIFICADOR DEL TIPO GESTIÓN',
            'COMENTARIO',
            'ID DEL DESPACHO',
            'ID DEL GESTOR/USUARIO',
            'FECHA GESTION'
        ];
    }

    public function prepararDatos($fecha)
    {
        $vector = [];
        $folios = Gestion::obtenerFoliosGen($fecha);
        for ($i = 0; $i < count($folios); $i++) {
            $vector[$i] = [trim($folios[$i]->id_cliente),$folios[$i]->id_tipo_gestion_ssl,$folios[$i]->comentario,$this->idExternoDespacho,$folios[$i]->id_usuario,$fecha];
        }
        return $vector;
    }
}
