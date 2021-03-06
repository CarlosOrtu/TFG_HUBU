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
            $table->date('fecha_primera_consulta');
            $table->date('fecha_diagnostico');
            $table->integer('ECOG');
            $table->integer('T');
            $table->double('T_tamano',11,2);
            $table->integer('N');
            $table->string('N_afectacion');
            $table->string('M');
            $table->string('num_afec_metas');
            $table->string('TNM');
            $table->string('tipo_muestra');
            $table->string('histologia_tipo');
            $table->string('histologia_subtipo');
            $table->string('histologia_grado')->nullable();
            $table->boolean('tratamiento_dirigido');
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
