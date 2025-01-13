<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsistenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    if (!Schema::hasTable('asistencias')) {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('grupo_id');
            $table->unsignedBigInteger('qr_code_id');
            $table->date('fecha');
            $table->time('hora_registro');
            $table->string('tipo');
            $table->string('estado');
            $table->timestamps();

            
            $table->foreign('alumno_id')->references('id')->on('alumnos')->onDelete('cascade');
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
            $table->foreign('qr_code_id')->references('id')->on('qr_codes')->onDelete('cascade');
        });
    }
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('asistencias')) {
            Schema::dropIfExists('asistencias');
        }
    }

}
