<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHoraClaseToAsistenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->time('hora_clase')->nullable(); 
        });
    }
    
    public function down()
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropColumn('hora_clase');
        });
    }
    
}
