<?php

use Illuminate\Database\Seeder;
use App\User;

class UsuariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuario = new User();
        $usuario->name = 'Manuel';
        $usuario->last_name = 'Lopez';
        $usuario->despacho = '';
        $usuario->tipo = 'Superadministrador';
        $usuario->username = 'mlcsa';
        $usuario->email = '1';
        $usuario->password = bcrypt('mlcsa');
        $usuario->session_id = '';
        $usuario->save();


        $usuario = new User();
        $usuario->name = 'Manuel';
        $usuario->last_name = 'Lopez';
        $usuario->despacho = 'bd_prueba';
        $usuario->tipo = 'Supervisor';
        $usuario->username = 'mlcs';
        $usuario->email = '2';
        $usuario->password = bcrypt('mlcs');
        $usuario->session_id = '';
        $usuario->save();

        $usuario = new User();
        $usuario->name = 'Manuel';
        $usuario->last_name = 'Lopez';
        $usuario->despacho = 'bd_prueba';
        $usuario->tipo = 'Administrador';
        $usuario->username = 'mlca';
        $usuario->email = '3';
        $usuario->password = bcrypt('mlca');
        $usuario->session_id = '';
        $usuario->save();

        $usuario = new User();
        $usuario->name = 'Manuel';
        $usuario->last_name = 'Lopez';
        $usuario->despacho = 'bd_prueba';
        $usuario->tipo = 'Gestor';
        $usuario->username = 'mlcg';
        $usuario->email = '4';
        $usuario->password = bcrypt('mlcg');
        $usuario->session_id = '';
        $usuario->save();

        $usuario = new User();
        $usuario->name = 'Alfredo';
        $usuario->last_name = 'Castañeda';
        $usuario->despacho = 'bd_prueba';
        $usuario->tipo = 'Gestor';
        $usuario->username = 'acpg';
        $usuario->email = '5';
        $usuario->password = bcrypt('acpg');
        $usuario->session_id = '';
        $usuario->save();

        $usuario = new User();
        $usuario->name = 'Santiago';
        $usuario->last_name = 'Castañeda';
        $usuario->despacho = 'bd_prueba';
        $usuario->tipo = 'Gestor';
        $usuario->username = 'scpg';
        $usuario->email = '6';
        $usuario->password = bcrypt('scpg');
        $usuario->session_id = '';
        $usuario->save();

    }
}
