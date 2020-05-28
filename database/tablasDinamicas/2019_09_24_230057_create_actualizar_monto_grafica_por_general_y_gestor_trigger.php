<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActualizarMontoGraficaPorGeneralYGestorTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(/** @lang text */ '
CREATE OR REPLACE FUNCTION actualizamontorealgrl()
  RETURNS trigger AS
  $BODY$
  DECLARE 
  montoActual NUMERIC;
  montoActualGestor NUMERIC;
  semana INTEGER;
  anio INTEGER;
  userName TEXT;
  nombreUsuario TEXT;
  folio INTEGER;
  idUsuario INTEGER;
  idGestion INTEGER;
  sujetoAux TEXT;
  apellido TEXT;
  montoActualEncargado NUMERIC;
  nombreEncargado TEXT;
  idCliente TEXT;
  BEGIN
    semana:=extract(\'week\' from NEW.fecha_pago_realizada);
    anio:=extract(\'year\' from NEW.fecha_pago_realizada);
    folio:=NEW.folio;
    idGestion:=(SELECT id_gestion FROM "Convenio" WHERE id_convenio=folio);
    idUsuario:=(SELECT id_usuario FROM "Gestion" WHERE id_gestion=idGestion);
    idCliente:=(SELECT id_cliente FROM "Gestion" WHERE id_gestion=idGestion);
    nombreEncargado:=(SELECT encargado from "Cliente" WHERE id_cliente = idCliente);
    
    nombreUsuario:=(SELECT * FROM dblink(\'dbname=B4nC0 user=B4nC0\', \'SELECT "users".name FROM "users" WHERE id= \'|| idUsuario) AS t1(name VARCHAR(255)));
    userName:=(SELECT * FROM dblink(\'dbname=B4nC0 user=B4nC0\', \'SELECT "users".username FROM "users" WHERE id= \'|| idUsuario) AS t1(name VARCHAR(255)));
    apellido:=(SELECT * FROM dblink(\'dbname=B4nC0 user=B4nC0\', \'SELECT "users".last_name FROM "users" WHERE id= \'|| idUsuario) AS t1(name VARCHAR(255)));
    montoActual:=(SELECT acumulado FROM "Grafica" WHERE extract(\'week\' from fecha)=semana AND
      extract(\'year\' from fecha)=anio AND sujeto=\'general\');
  sujetoAux:=concat(userName,\' - \',nombreUsuario,\' \',apellido);  
  montoActualGestor:=(SELECT acumulado FROM "Grafica" WHERE extract(\'week\' from fecha)=semana AND
      extract(\'year\' from fecha)=anio AND sujeto=sujetoAux);
      
      
      
      
      
      montoActualEncargado:=(SELECT acumulado FROM "Grafica" WHERE extract(\'week\' from fecha)=semana AND
      extract(\'year\' from fecha)=anio AND sujeto=nombreEncargado);
    
      
      montoActualEncargado:=montoActualEncargado+NEW.pago_realizado;
    
    
    montoActual:=montoActual+NEW.pago_realizado;
  montoActualGestor:=montoActualGestor+NEW.pago_realizado;
  
    UPDATE "Grafica" SET acumulado=montoActual WHERE extract(\'week\' from fecha)=semana AND
      extract(\'year\' from fecha)=anio AND sujeto=\'general\';

    UPDATE "Grafica" SET acumulado=montoActualGestor WHERE extract(\'week\' from fecha)=semana AND
      extract(\'year\' from fecha)=anio AND sujeto=sujetoAux;
      
      UPDATE "Grafica" SET acumulado=montoActualEncargado WHERE extract(\'week\' from fecha)=semana AND
      extract(\'year\' from fecha)=anio AND sujeto=nombreEncargado;
      
      
      RETURN NEW;
  END;

$BODY$ LANGUAGE plpgsql;

CREATE TRIGGER actualizamontorealgrl
        AFTER UPDATE ON "CalendarioPagos"
        FOR EACH ROW
        EXECUTE PROCEDURE actualizamontorealgrl();
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actualizar_monto_grafica_por_general_y_gestor_trigger');
    }
}
