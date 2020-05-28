<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConvenioEDtMsTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        
CREATE OR REPLACE FUNCTION convenio_e_dt_ms()
  RETURNS trigger AS
$BODY$
DECLARE 
	cantidadApagar NUMERIC;
	nuevaDeudaAPagar NUMERIC;
	deuda_total NUMERIC;
	cantidadPagos INT;
	auxId_Convenio INT;
	auxIDCliente CHARACTER(50);
BEGIN
	if NEW."pagado"=false THEN
		UPDATE "Convenio" SET estado_convenio=false WHERE id_gestion=NEW.folio;
	ELSE
		if NEW."pagado"=true THEN
			deuda_total:=(SELECT "Convenio".deuda_total from "Convenio" WHERE id_gestion=NEW.folio);
			nuevaDeudaAPagar:=deuda_total-NEW.pago_realizado;
			UPDATE "Convenio" SET deuda_total=nuevaDeudaAPagar WHERE id_gestion=NEW.folio;
			cantidadPagos:=(SELECT numero_pagos from "Convenio" WHERE id_gestion=NEW.folio);
			cantidadApagar:=nuevaDeudaAPagar/cantidadPagos;
			auxId_Convenio:=(SELECT id_gestion FROM "Convenio" WHERE id_gestion=NEW.folio);
			auxIDCliente:= (SELECT id_cliente FROM "Convenio" WHERE id_gestion=NEW.folio);
		END if;
	END if;
   	RETURN NEW;
END;
$BODY$ LANGUAGE plpgsql;

CREATE TRIGGER actualizaConvenioEstado
    AFTER UPDATE ON "CalendarioPagos"
    FOR EACH ROW
    EXECUTE PROCEDURE convenio_e_dt_ms();
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('convenio_e_dt_ms_trigger');
    }
}
