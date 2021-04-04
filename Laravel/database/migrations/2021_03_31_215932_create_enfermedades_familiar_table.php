<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnfermedadesFamiliarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enfermedades_familiar', function (Blueprint $table) {
            $table->id('id_enfermedad_f');
            $table->unsignedBigInteger('id_antecedente_f');
            $table->string('tipo');
            $table->foreign('id_antecedente_f')->references('id_antecedente_f')->on('antecedentes_familiares')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enfermedades_familiar');
    }
}
