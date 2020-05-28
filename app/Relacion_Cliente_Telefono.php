<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModeloEstandar;
class Relacion_Cliente_Telefono extends Model
{
	use ModeloEstandar;
    protected $primaryKey = 'id_r_c_t';
	protected $table = 'Relacion_Cliente_Telefono';
}
