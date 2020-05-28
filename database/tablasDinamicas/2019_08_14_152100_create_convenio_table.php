<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConvenioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Convenio', function (Blueprint $table) {
            $table->bigIncrements('id_convenio');
            $table->integer('id_gestion');
            $table->text('id_cliente');
            $table->boolean('convenio_estado')->nullable();
            $table->float('primer_pago_cantidad');
            $table->boolean('primer_pago_estado');
            $table->timestamp('primer_pago_fecha');
            $table->float('deuda_total');
            $table->float('deuda_total_original');
            $table->integer('numero_pagos');
            $table->text('folioGen')->nullable();
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
        Schema::dropIfExists('Convenio');
    }
}
