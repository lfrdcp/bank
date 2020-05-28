<?php

namespace App\Http\Controllers;

use App\GestionesMasivas;
use Illuminate\Http\Request;

class BaseDatosController extends Controller
{
    public function index()
    {
        return GestionesMasivas::viewAndData('BaseDatos.index');
    }
}
