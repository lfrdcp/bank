<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntencionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Intencion', function (Blueprint $table) {
            $table->bigIncrements('id_intencion');
            $table->integer('id_gestion');
            $table->text('id_cliente');
            $table->timestamp('fecha');
            $table->float('pago');
            $table->text('folioGen');
            $table->text('estado');
            $table->timestamp('fecha_realizada');
            $table->float('pago_realizado');
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
        Schema::dropIfExists('Intencion');
    }
}
