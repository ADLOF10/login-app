<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlumnoProfileController extends Controller
{
    public function getGruposAsignados(Request $request)
    {
        $user = $request->user(); 
    
        if ($user->role !== 'alumno') {
            return response()->json(['error' => 'No autorizado'], 403);
        }
    
       
        $alumno = $user->alumno;
    
        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }
    
        
        $grupos = $alumno->grupos()->with('materia')->get();
    
        return response()->json($grupos);
    }
    
    public function getQrCodes(Request $request)
    {
        $user = $request->user(); 

        if ($user->role !== 'alumno') {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $alumno = $user->alumno;

        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }

        
        $qrCodes = \App\Models\QrCode::whereIn('grupo_id', $alumno->grupos->pluck('id'))
            ->whereIn('materia_id', $alumno->grupos->pluck('materia_id'))
            ->with(['grupo', 'materia'])
            ->get();

        return response()->json($qrCodes);
    }

}