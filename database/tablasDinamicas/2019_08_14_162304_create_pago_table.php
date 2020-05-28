<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Pago', function (Blueprint $table) {
            $table->bigIncrements('id_pago');
            $table->text('id_cliente');
            $table->text('clasificacion')->nullable();
            $table->text('atraso_max')->nullable();
            $table->text('saldo')->nullable();
            $table->text('moratorios')->nullable();
            $table->text('total')->nullable();
            $table->text('dia_de_pago')->nullable();
            $table->text('fecha_pago_ultimo')->nullable();
            $table->text('importe_pago_ultimo')->nullable();
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
        Schema::dropIfExists('Pago');
    }
}
