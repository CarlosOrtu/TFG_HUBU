<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnicasRealizadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tecnicas_realizadas', function (Blueprint $table) {
            $table->id('id_tecnica');
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
        Schema::dropIfExists('tecnicas_realizadas');
    }
}
