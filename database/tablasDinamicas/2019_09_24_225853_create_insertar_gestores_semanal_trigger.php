<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsertarGestoresSemanalTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
CREATE OR REPLACE PROCEDURE insertarGestoresSemanales()
LANGUAGE plpgsql    
AS $$
DECLARE
  tam INTEGER;
  despachoAux TEXT;
 
BEGIN
	
	INSERT INTO public."Grafica"
	(fecha, sujeto, monto, acumulado,created_at,updated_at)VALUES
	(now(),\'gestor\',0,0,now(),now());
	
	despachoAux:=(SELECT current_database());
	INSERT INTO "Grafica" (fecha,sujeto,monto,acumulado,created_at,updated_at) 
	
	SELECT now(),
	concat(username,\' - \',name_gestor,\' \',last_name),0,0,now(),now() FROM dblink(\'dbname=B4nC0 user=B4nC0\',
	 \'SELECT 
	 "users".despacho,
	 "users".name,
	 "users".username,
	 "users".last_name,"users".tipo
	  FROM "users" \') 
	 AS t1
	 (
	 	despacho VARCHAR(255),
	 	name_gestor VARCHAR(255),
	 	username VARCHAR(255),
	 	last_name VARCHAR(255), 
		 tipo VARCHAR(255)
	 	)
	 where despacho=despachoAux and tipo=\'Gestor\';
	COMMIT;
END;
$$;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insertar_gestores_semanal_trigger');
    }
}
