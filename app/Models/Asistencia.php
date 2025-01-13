<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'alumno_id',
        'grupo_id',
        'materia_id',
        'fecha',
        'hora_clase',
        'hora_registro',
        'fecha_clase',
        'tipo',
        'estado',
        'qr_code_id', 
    ];
    
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function qrCode()
    {
        return $this->belongsTo(QrCode::class, 'qr_code_id');
    }
}
