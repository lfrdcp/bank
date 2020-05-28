<?php

use Illuminate\Database\Seeder;

class Tipo_Gestion_sslTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "SE ENTREGO NOTIFICACION";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "DEUDOR YA NO VIVE EN EL DOMICLIO";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "AVAL YA NO VIVE EN EL DOMICLIO";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "PROMESA DE PAGO";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "REALIZO PAGO";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "SE RECOGIO MERCANCIA";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "SE REVISO EXPEDIENTE DIGITALIZADO";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "SE REVISO EXPEDIENTE FISICO";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "GESTION TELEFONO";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "FINADO";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "ACLARACION";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "CONSULTA EN OTRAS BASES DE DATOS";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "POSIBLE FRAUDE";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "SE NOTIFICO DEDUCCION FISCAL";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "SE CONFIRMA SOLVENCIA DEL DEUDOR";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "SE CONFIRMA SOLVENCIA DEL AVAL";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "SE CONFIRMA QUE EL DEUDOR VIVE EN EL DOMICILIO";
        $tipoGestionSsl->save();

        $tipoGestionSsl = new \App\Tipo_Gestion_ssl();
        $tipoGestionSsl->descripcion_ssl = "POSIBLE FRAUDE";
        $tipoGestionSsl->save();
    }
}
