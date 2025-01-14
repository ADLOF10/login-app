<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGruposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_grupo');
            $table->unsignedBigInteger('materia_id');
            $table->timestamps();
        
            $table->foreign('materia_id')->references('id')->on('materias');

            $table->unique(['grupo_id', 'materia_id'], 'unique_grupo_materia');

            $table->unique(['nombre_grupo', 'materia_id']);

        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupos');
    }
}
