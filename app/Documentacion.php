<?php

namespace App;

use App\Traits\ModeloEstandar;
use Illuminate\Database\Eloquent\Model;

class Documentacion extends Model
{
    use ModeloEstandar;
    protected $primaryKey = 'id_documento';
    protected $table = 'Documento';
}
