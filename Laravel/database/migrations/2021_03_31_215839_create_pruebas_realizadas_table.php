<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePruebasRealizadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pruebas_realizadas', function (Blueprint $table) {
            $table->id('id_prueba');
            $table->unsignedBigInteger('id_enfermedad');
            $table->string('tipo');
            $table->foreign('id_enfermedad')->references('id_enfermedad')->on('enfermedades')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pruebas_realizadas');
    }
}
