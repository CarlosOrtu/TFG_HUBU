<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntecedentesOncologicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedentes_oncologicos', function (Blueprint $table) {
            $table->id('id_antecedente_o');
            $table->unsignedBigInteger('id_paciente');
            $table->string('tipo');
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
        Schema::dropIfExists('antecedentes_oncologicos');
    }
}
