<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';

    protected $fillable = ['nombre_grupo', 'materia_id'];

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'grupo_alumno', 'grupo_id', 'alumno_id');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'grupo_id');
    }
}