<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToMateriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('materias', 'user_id')) {
            Schema::table('materias', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->after('clave')->nullable();
            });
        }
    }
    
    
    public function down()
    {
        if (Schema::hasColumn('materias', 'user_id')) {
            Schema::table('materias', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
    
    

}
