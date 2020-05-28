<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsertadatoscalendariopagosTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE OR REPLACE FUNCTION insertadatoscalendariopagos()
  RETURNS trigger AS
$BODY$
DECLARE 
	contador INTEGER = 0;
	fecha_incremental INTEGER =7;
	es_primera_fecha BOOLEAN = true;
	fecha_actualizada TIMESTAMP;
	sumatoria_fecha INTEGER =0;
	cadena VARCHAR(50);
	pago_esperado NUMERIC;
	id_gestion_aux INTEGER;
	comentario TEXT;
	num_ciclos INTEGER;
	
BEGIN
	if NEW."convenio_estado"=true THEN
		cadena:= CONCAT(sumatoria_fecha,\' \',\'days\');
		id_gestion_aux:=NEW.id_gestion;
		comentario:=(SELECT "Gestion".comentario from "Gestion" WHERE id_gestion=id_gestion_aux);
		if NEW.numero_pagos=0 THEN
			pago_esperado:=NEW.deuda_total;
			INSERT INTO public."CalendarioPagos"(folio, fecha_pago_esperada, fecha_pago_realizada, pagado, id_cliente,pago_esperado,comentario)
			VALUES (NEW.id_convenio, NEW.primer_pago_fecha, NULL,NULL, NEW.id_cliente,pago_esperado,comentario);
		ELSE
			pago_esperado:=(NEW.deuda_total-NEW.primer_pago_cantidad)/NEW.numero_pagos;
		END IF;
		if NEW.numero_pagos!=0 THEN
			num_ciclos:=1+(NEW.numero_pagos);
			WHILE contador != num_ciclos LOOP
				if es_primera_fecha=true THEN
					INSERT INTO public."CalendarioPagos"(folio, fecha_pago_esperada, fecha_pago_realizada, pagado, id_cliente,pago_esperado,comentario)
					VALUES (NEW.id_convenio,NEW.primer_pago_fecha, NULL,NULL, NEW.id_cliente,NEW.primer_pago_cantidad,comentario);
					es_primera_fecha:=false;
				ELSE
					cadena:= CONCAT(sumatoria_fecha,\' \',\'days\');
					fecha_actualizada:=NEW.primer_pago_fecha::TIMESTAMP+cadena::INTERVAL;
					INSERT INTO public."CalendarioPagos"(folio, fecha_pago_esperada, fecha_pago_realizada, pagado, id_cliente,pago_esperado,comentario)
					VALUES (NEW.id_convenio,fecha_actualizada,NULL, NULL, NEW.id_cliente,pago_esperado,comentario);
				END IF;
				sumatoria_fecha:=sumatoria_fecha+7;
				contador := contador + 1;
			END LOOP;
		END if;
	END if;
   	RETURN NEW;
END;
$BODY$ LANGUAGE plpgsql;
    
    CREATE TRIGGER insertadatoscalendariopagos
        AFTER INSERT ON "Convenio"
        FOR EACH ROW
        EXECUTE PROCEDURE insertadatoscalendariopagos();
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insertadatoscalendariopagos_trigger');
    }
}
