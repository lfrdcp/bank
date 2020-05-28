<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tipo_GestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Tipo_Gestion')->insert([
            ['nombre_gestion' => 'AB', 'descripcion_gestion' => 'CLIENTE ABANDONA LA LLAMADA'],
            ['nombre_gestion' => 'BP', 'descripcion_gestion' => 'PROMESA ROTA'],
            ['nombre_gestion' => 'MQ', 'descripcion_gestion' => 'BLOQUEO DE TELÉFONO POR CONFIDENCIALIDAD'],
            ['nombre_gestion' => 'CI', 'descripcion_gestion' => 'CLIENTE NO DEFINE'],
            ['nombre_gestion' => 'CL', 'descripcion_gestion' => 'CLIENTE NEGOCIÓ CON OTRA AGENCIA'],
            ['nombre_gestion' => 'CN', 'descripcion_gestion' => 'NEGOCIÓ CON EL BANCO'],
            ['nombre_gestion' => 'CP', 'descripcion_gestion' => 'DISMINUCIÓN DE INGRESOS'],
            ['nombre_gestion' => 'DN', 'descripcion_gestion' => 'DESASTRE NATURAL'],
            ['nombre_gestion' => 'DS', 'descripcion_gestion' => 'DESEMPLEO'],
            ['nombre_gestion' => 'EQ', 'descripcion_gestion' => 'TEL EQUIVOCADO'],
            ['nombre_gestion' => 'EF', 'descripcion_gestion' => 'INCAPACIDAD / ENFERMEDAD'],
            ['nombre_gestion' => 'FP', 'descripcion_gestion' => 'TEL EQUIVOCADO'],
            ['nombre_gestion' => 'MQ', 'descripcion_gestion' => 'MAQUINA CONTESTADORA'],
            ['nombre_gestion' => 'MT', 'descripcion_gestion' => 'MENSAJE CON TERCEROS'],
            ['nombre_gestion' => 'NC', 'descripcion_gestion' => 'NO CONTACTO'],
            ['nombre_gestion' => 'NE', 'descripcion_gestion' => 'TELÉFONO NO EXISTE'],
            ['nombre_gestion' => 'NI', 'descripcion_gestion' => 'ACTUALIZACIÓN DE INFORMACIÓN'],
            ['nombre_gestion' => 'NP', 'descripcion_gestion' => 'SE NIEGA A PAGAR/SIN VOLUNTAD DE PAGO'],
            ['nombre_gestion' => 'OC', 'descripcion_gestion' => 'TEL OCUPADO'],
            ['nombre_gestion' => 'PG', 'descripcion_gestion' => 'CLIENTE PAGANDO PS/AZ'],
            ['nombre_gestion' => 'PP', 'descripcion_gestion' => 'PROMESA DE PAGO'],
            ['nombre_gestion' => 'RF', 'descripcion_gestion' => 'RECADO CON FAMILIAR'],
            ['nombre_gestion' => 'SG', 'descripcion_gestion' => 'CONTACTO DE SEGUIMIENTO A NEGOCIACIÓN'],
            ['nombre_gestion' => 'VL', 'descripcion_gestion' => 'YA NO TRABAJA AHÍ'],

            ['nombre_gestion' => 'NL', 'descripcion_gestion' => null],
            ['nombre_gestion' => 'SG', 'descripcion_gestion' => null],
            ['nombre_gestion' => 'FA', 'descripcion_gestion' => null],
            ['nombre_gestion' => 'IL', 'descripcion_gestion' => null],

        ]);

    }
}
