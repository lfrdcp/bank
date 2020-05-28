<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDireccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Direccion', function (Blueprint $table) {
            $table->bigIncrements('id_direccion');
            $table->text('id_cliente');
            $table->text('cuadrante')->nullable();
            $table->text('zona_geo')->nullable();
            $table->text('direccion')->nullable();
            $table->text('num_ext')->nullable();
            $table->text('num_int')->nullable();
            $table->text('tipo_direccion')->nullable();
            $table->text('cp')->nullable();
            $table->text('colonia')->nullable();
            $table->text('poblacion')->nullable();
            $table->text('estado')->nullable();
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
        Schema::dropIfExists('Direccion');
    }
}
