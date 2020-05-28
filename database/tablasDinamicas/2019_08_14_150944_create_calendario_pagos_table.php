<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarioPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CalendarioPagos', function (Blueprint $table) {
            $table->bigIncrements('id_calendario');
            $table->text('id_cliente');
            $table->integer('folio');
            $table->timestamp('fecha_pago_esperada');
            $table->timestamp('fecha_pago_realizada')->nullable();
            $table->boolean('pagado')->nullable();
            $table->float('pago_esperado');
            $table->float('pago_realizado')->nullable();
            $table->text('folio_ingresado')->nullable();
            $table->text('comentario')->nullable();
            $table->boolean('check')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CalendarioPagos');
    }
}
