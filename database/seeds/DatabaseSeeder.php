<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /*$this->call(UsuariosTableSeeder::class);*/
        $this->call(GestionadoTableSeeder::class);
        $this->call(Tipo_GestionTableSeeder::class);
        $this->call(Tipo_Gestion_sslTableSeeder::class);
    }
}
