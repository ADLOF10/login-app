<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias';

    protected $fillable = ['nombre', 'clave', 'user_id'];

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'materia_id');
    }

    public function docente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}