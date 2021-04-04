<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnfermedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enfermedades', function (Blueprint $table) {
            $table->id('id_enfermedad');
            $table->unsignedBigInteger('id_paciente');
            $table->timestamp('fecha_primera_consulta');
            $table->timestamp('fecha_diagnostico');
            $table->integer('ECOG');
            $table->integer('T');
            $table->integer('T_tamano');
            $table->integer('N');
            $table->string('N_afectacion');
            $table->integer('M');
            $table->integer('num_afec_metas');
            $table->integer('TNM');
            $table->string('tipo_muestra');
            $table->string('histologia_tipo');
            $table->string('histologia_subtipo');
            $table->string('histologia_grado')->nullable();
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
        Schema::dropIfExists('enfermedades');
    }
}
