<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumnos'; 

    protected $fillable = ['nombre', 'apellidos', 'correo_institucional', 'numero_cuenta', 'semestre', 'foto_perfil', 'user_id'];

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'alumno_id');
    }

    public function asistenciasTotales()
    {
        return $this->hasMany(Asistencia::class, 'alumno_id');
    }

    public function calcularPorcentajeAsistencia()
    {
        $totalAsistencias = $this->asistenciasTotales()->count();
        $totalClases = QrCode::whereIn('grupo_id', $this->grupos->pluck('id'))->count();

        return $totalClases > 0 ? round(($totalAsistencias / $totalClases) * 100, 2) : 0;
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_alumno', 'alumno_id', 'grupo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}