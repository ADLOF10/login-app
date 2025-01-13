<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechaClaseToAsistenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->date('fecha_clase')->nullable()->after('hora_clase'); 
        });
    }
    
    public function down()
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropColumn('fecha_clase');
        });
    }
    
}
