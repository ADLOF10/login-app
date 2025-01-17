<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('qr_codes')) {
            Schema::create('qr_codes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('grupo_id');
                $table->string('tipo');
                $table->time('hora_clase');
                $table->time('fin_clase');
                $table->text('codigo');
                $table->datetime('expira_at');
                $table->unsignedBigInteger('materia_id');
                $table->timestamps();

                
                $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
                $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');
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
        if (Schema::hasTable('qr_codes')) {
            Schema::dropIfExists('qr_codes');
        }
    }

}
