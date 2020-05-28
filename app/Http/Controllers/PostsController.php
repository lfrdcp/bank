<?php

namespace App\Http\Controllers;

use App\BaseDinamica;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    public function index()
    {
        BaseDinamica::connexionDynamicSon('B4nC0');
        BaseDinamica::connexionDynamicSon();
        return DB::table('Cliente')
            ->orderBy('id_cliente', 'desc')
            ->get();
    }
}
