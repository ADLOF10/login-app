<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoAlumno extends Model
{
    use HasFactory;

    protected $table = 'grupo_alumno';

    protected $fillable = ['grupo_id', 'alumno_id'];




    public function grupo()
{
    return $this->belongsTo(Grupo::class, 'grupo_id'); // Relación con Grupo
}

public function alumno()
{
    return $this->belongsTo(Alumno::class, 'alumno_id'); // Relación con Alumno
}


}

