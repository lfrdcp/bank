<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Gestion', function (Blueprint $table) {
            $table->bigIncrements('id_gestion');
            $table->integer('id_usuario');
            $table->text('username')->nullable();
            $table->text('id_cliente');
            $table->text('tit_aval')->nullable();
            $table->integer('id_tipo_gestion')->nullable();
            $table->integer('id_tipo_gestion_ssl')->nullable();
            $table->integer('id_gestionado')->nullable();
            $table->timestamp('fecha_hora_contactar')->nullable();
            $table->text('comentario')->nullable();
            $table->text('folioGen')->nullable();
            $table->boolean('migrado')->nullable();
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
        Schema::dropIfExists('Gestion');
    }
}
