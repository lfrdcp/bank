<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActualizaConvenioDiarioTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE OR REPLACE PROCEDURE actualizaConvenioDiario()
        LANGUAGE plpgsql    
        AS $$ 
        BEGIN
            update "Convenio" set convenio_estado=false from "CalendarioPagos" 
            where "CalendarioPagos".folio="Convenio".id_convenio and 
            (select count(*) filter(where pagado is null) from "CalendarioPagos"
            where DATE_PART(\'week\', now())
            - DATE_PART(\'week\', "CalendarioPagos".fecha_pago_esperada::date)>2
            and "CalendarioPagos".folio="Convenio".id_convenio)>1;
        
            update "Convenio" set convenio_estado=null from "CalendarioPagos" 
            where "CalendarioPagos".folio="Convenio".id_convenio and 
            (select count(*) filter(where pagado is null) from "CalendarioPagos"
            where DATE_PART(\'day\', now())
            - DATE_PART(\'day\', "CalendarioPagos".fecha_pago_esperada::date)=1 
            and "CalendarioPagos".folio="Convenio".id_convenio)>=1;
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
        Schema::dropIfExists('actualiza_convenio_diario_trigger');
    }
}
