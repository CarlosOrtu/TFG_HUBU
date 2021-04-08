<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReevaluacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reevaluaciones', function (Blueprint $table) {
            $table->id('id_reevaluacion');
            $table->unsignedBigInteger('id_paciente');
            $table->date('fecha');
            $table->string('estado');
            $table->string('progresion_localizacion')->nullable();
            $table->string('tipo_tratamiento')->nullable();
            $table->foreign('id_paciente')->references('id_paciente')->on('pacientes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reevaluaciones');
    }
}
