<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenerarFolioIntencionTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
       CREATE OR REPLACE FUNCTION generarFolioIntencion()
  RETURNS trigger AS
  $BODY$
		DECLARE
	folio VARCHAR(100);
	inicialesGestor VARCHAR(100);
	nombreGestor VARCHAR(200);
	apellidoGestor VARCHAR(200);
	inicialNombre VARCHAR(100);
	inicialApellido VARCHAR(100);
	id_usuario_aux INTEGER;
	despacho VARCHAR(100);
	formato VARCHAR(10);
	idDespacho VARCHAR(200);
	BEGIN
	
	
	  id_usuario_aux:=(SELECT id_usuario from "Gestion" WHERE id_gestion=NEW.id_gestion);
	  despacho:=(SELECT * FROM dblink(\'dbname=B4nC0 user=B4nC0\', \'SELECT "users".despacho FROM "users" WHERE id= \'|| id_usuario_aux) AS t1(name VARCHAR(255)));
	  nombreGestor:=(SELECT * FROM dblink(\'dbname=B4nC0 user=B4nC0\', \'SELECT "users".username FROM "users" WHERE id= \' || id_usuario_aux) AS t1(name VARCHAR(255)));
	  idDespacho:=(SELECT id_despacho_externo FROM dblink(\'dbname=B4nC0 user=B4nC0\', \'SELECT "Despacho".id_despacho_externo,"Despacho".nombre FROM "Despacho" \') AS t1(id_despacho_externo TEXT,nombre TEXT) where nombre = despacho);
	  inicialesGestor:=concat(nombreGestor,\'_\',idDespacho);
	  
	
	  folio:=concat(\'PI\',\'_\',NEW.id_intencion,\'_\',NEW.id_cliente,\'_\',substring(concat(\'\',NEW.created_at),1,10),\'_\',
	    substring(concat(\'\',NEW.created_at),12,5),\'_\',inicialesGestor);
	  UPDATE "Intencion" SET "folioGen"=folio WHERE id_intencion=NEW.id_intencion;
	   RETURN NEW;
	END;
	
	$BODY$ 
LANGUAGE plpgsql;


CREATE TRIGGER generarFolioIntencion
    AFTER INSERT ON "Intencion"
    FOR EACH ROW
    EXECUTE PROCEDURE generarFolioIntencion();

        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generar_folio_intencion_trigger');
    }
}
