<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiomarcadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biomarcadores', function (Blueprint $table) {
            $table->id('id_biomarcador');
            $table->unsignedBigInteger('id_enfermedad');
            $table->string('nombre');
            $table->string('tipo')->nullable();
            $table->string('subtipo')->nullable();
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
        Schema::dropIfExists('biomarcadores');
    }
}
