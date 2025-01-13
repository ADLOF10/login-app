<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';

    protected $fillable = [
        'grupo_id', 
        'tipo', 
        'hora_clase', 
        'fecha_clase', 
        'codigo', 
        'expira_at', 
        'materia_id', 
        'asistencia', 
        'retardo', 
        'inasistencia'
    ];
    
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'qr_code_id');
    }
}