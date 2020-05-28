<?php

use Illuminate\Database\Seeder;
use \App\Gestionado;

class GestionadoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gestionado1 = new Gestionado();
        $gestionado1->nombre = "Familiar";
        $gestionado1->save();

        $gestionado2 = new Gestionado();
        $gestionado2->nombre = "Conocido";
        $gestionado2->save();

        $gestionado3 = new Gestionado();
        $gestionado3->nombre = "Otro";
        $gestionado3->save();

    }
}
