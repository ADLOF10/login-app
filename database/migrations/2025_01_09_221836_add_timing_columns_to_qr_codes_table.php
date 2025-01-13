<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimingColumnsToQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->integer('asistencia')->after('materia_id')->nullable();
            $table->integer('retardo')->after('asistencia')->nullable();
            $table->integer('inasistencia')->after('retardo')->nullable();
        });
    }

    public function down()
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropColumn(['asistencia', 'retardo', 'inasistencia']);
        });
    }
}
