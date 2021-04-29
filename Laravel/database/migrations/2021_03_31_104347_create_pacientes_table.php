<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id('id_paciente');
            $table->string('nombre',30);
            $table->string('apellidos',30);
            $table->string('sexo',10);
            $table->string('raza',30);
            $table->date('nacimiento');
            $table->string('profesion',30);
            $table->string('fumador',30);
            $table->integer('num_tabaco_dia',)->nullable();
            $table->string('bebedor',30);
            $table->date('ultima_modificacion');
            $table->string('carcinogenos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pacientes');
    }
}
