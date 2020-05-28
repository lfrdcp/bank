<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuxCalendario extends Model
{
    protected $fillable = ['name', 'details', 'start', 'end', 'color'];
}
