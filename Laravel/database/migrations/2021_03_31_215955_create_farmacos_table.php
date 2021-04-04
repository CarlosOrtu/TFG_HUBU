<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmacosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmacos', function (Blueprint $table) {
            $table->id('id_farmaco');
            $table->unsignedBigInteger('id_intencion');
            $table->string('tipo');
            $table->foreign('id_intencion')->references('id_intencion')->on('intenciones')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmacos');
    }
}
