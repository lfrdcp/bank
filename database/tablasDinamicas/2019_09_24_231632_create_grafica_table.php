<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraficaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Grafica', function (Blueprint $table) {
            $table->bigIncrements('id_grafica');
            $table->timestamp('fecha');
            $table->text('sujeto')->nullable();
            $table->float('monto')->nullable();
            $table->float('acumulado')->nullable();
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
        Schema::dropIfExists('grafica');
    }
}
