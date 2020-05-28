<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use App\ConsultaSQL;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Home;
use Illuminate\Support\Facades\Schema;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function debePagar(Request $request)
    {
        if (auth()->user()->tipo == "Superadministrador") return Home::viewAndData('home');
        if (User::verificarPagoControlador(auth()->user()->despacho)) return Home::viewAndData('home');
        $request->session()->flash('msjPago', '¡No puedes hacer uso del sistema web porque existe un saldo pendiente en tu cuenta!');
        return Home::viewAndData('debePagar');
    }


    public function index()
    {
        return Home::viewAndData('home');
    }
}
