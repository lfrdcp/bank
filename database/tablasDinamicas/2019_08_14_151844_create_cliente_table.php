<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cliente', function (Blueprint $table) {
            $table->text('id_cliente')->primary();
            $table->text('nombre');
            $table->text('rfc')->nullable();
            $table->text('gerencia')->nullable();
            $table->text('encargado')->nullable();
            $table->text('id_grupo')->nullable();
            $table->text('nombre_grupo')->nullable();
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
        Schema::dropIfExists('Cliente');
    }
}
