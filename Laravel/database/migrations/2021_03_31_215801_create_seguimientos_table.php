<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeguimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id('id_seguimiento');
            $table->unsignedBigInteger('id_paciente');
            $table->date('fecha');
            $table->string('estado');
            $table->string('fallecido_motivo')->nullable();
            $table->date('fecha_fallecimiento')->nullable();
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
        Schema::dropIfExists('seguimientos');
    }
}
