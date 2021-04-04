<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntencionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intenciones', function (Blueprint $table) {
            $table->id('id_intencion');
            $table->unsignedBigInteger('id_tratamiento');
            $table->string('ensayo')->nullable();
            $table->integer('ensayo_fase')->nullable();
            $table->boolean('tratamiento_acceso_expandido')->nullable();
            $table->boolean('tratamiento_fuera_indicacion')->nullable();
            $table->boolean('medicacion_extranjera')->nullable();
            $table->string('esquema')->nullable();
            $table->string('modo_administracion')->nullable();
            $table->string('tipo_farmaco')->nullable();
            $table->integer('numero_ciclos')->nullable();
            $table->foreign('id_tratamiento')->references('id_tratamiento')->on('tratamientos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intenciones');
    }
}
