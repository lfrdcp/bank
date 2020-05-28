<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenerarFolioGestionTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
    CREATE OR REPLACE FUNCTION generarFolioGestion()
  RETURNS trigger AS
$BODY$
DECLARE
id_usuario_aux INTEGER;
folio VARCHAR(100);
inicialesGestor VARCHAR(200);
nombreGestor VARCHAR(200);
idDespacho VARCHAR(200);
inicialNombre VARCHAR(5);
inicialApellido VARCHAR(5);
despacho VARCHAR(100);
BEGIN
  id_usuario_aux:=NEW.id_usuario;
  
  
      despacho:=(SELECT * FROM dblink(\'dbname=B4nC0 user=B4nC0\', \'SELECT "users".despacho FROM "users" WHERE id= \'|| id_usuario_aux) AS t1(name VARCHAR(255)));
  nombreGestor:=(SELECT * FROM dblink(\'dbname=B4nC0 user=B4nC0\', \'SELECT "users".username FROM "users" WHERE id= \'|| id_usuario_aux) AS t1(name VARCHAR(255)));
  idDespacho:=(SELECT id_despacho_externo FROM dblink(\'dbname=B4nC0 user=B4nC0\', \'SELECT "Despacho".id_despacho_externo,"Despacho".nombre FROM "Despacho" \') AS t1(id_despacho_externo TEXT,nombre TEXT) where nombre = despacho);
  
  inicialesGestor:=concat(nombreGestor,\'_\',idDespacho);
  folio:=concat(\'G\',\'_\',NEW.id_gestion,\'_\',NEW.id_cliente,\'_\',substring(concat(\'\',NEW.created_at),1,10),\'_\',
    substring(concat(\'\',NEW.created_at),12,5),\'_\',inicialesGestor);
  UPDATE "Gestion" SET "folioGen"=folio WHERE id_gestion=NEW.id_gestion;
  UPDATE "Gestion" SET "check" = true WHERE id_cliente = NEW.id_cliente AND id_gestion != NEW.id_gestion;
   RETURN NEW;
END;
$BODY$ LANGUAGE plpgsql;


CREATE TRIGGER generarFolioGestion
    AFTER INSERT ON "Gestion"
    FOR EACH ROW
    EXECUTE PROCEDURE generarFolioGestion();
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generar_folio_gestion_trigger');
    }
}
