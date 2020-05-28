<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModeloEstandar;
use Illuminate\Support\Facades\DB;

class Telefono_Cliente extends Model
{
    use ModeloEstandar;
    protected $primaryKey = 'id_tel';
    protected $table = 'Telefono_Cliente';

    public static function consultaTelClixTel($numero)
    {
        return DB::SELECT('SELECT id_tel FROM "Telefono_Cliente" WHERE numero_tel = ? ', [$numero]);
    }

}
