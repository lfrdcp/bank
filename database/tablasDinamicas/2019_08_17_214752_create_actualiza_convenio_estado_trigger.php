<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActualizaConvenioEstadoTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE OR REPLACE FUNCTION actualizaConvenioEstado()
  RETURNS trigger AS
$BODY$
BEGIN
	UPDATE "Convenio" SET convenio_estado=false WHERE id_cliente=NEW.id_cliente AND id_convenio!=NEW.id_convenio;
	RETURN NEW;
END;
$BODY$ LANGUAGE plpgsql;

CREATE TRIGGER actualizaConvenioEstado
    AFTER INSERT ON "Convenio"
    FOR EACH ROW
    EXECUTE PROCEDURE actualizaConvenioEstado();
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actualiza_convenio_estado_trigger');
    }
}
