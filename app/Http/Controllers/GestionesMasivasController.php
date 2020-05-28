<?php

namespace App\Http\Controllers;

use App\GestionesMasivas;
use Illuminate\Http\Request;

class GestionesMasivasController extends Controller
{
    public function index()
    {
        return GestionesMasivas::viewAndData('GestionesMasivas.index');
    }

    public function otras_gestiones()
    {
        return GestionesMasivas::viewAndData('GestionesMasivas.otras_gestiones');
    }
}
