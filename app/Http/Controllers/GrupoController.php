<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::with('materia')->get(); 
        return view('grupos.index', compact('grupos'));
    }

    public function create()
    {
        $materias = Materia::all(); 
        return view('grupos.create', compact('materias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_grupo' => 'required|string|max:255',
            'materia_id' => 'required|exists:materias,id',
        ]);

        Grupo::create($request->all()); 
        return redirect()->route('grupos.index')->with('success', 'Grupo creado exitosamente.');
    }

    public function edit(Grupo $grupo)
    {
        $materias = Materia::all(); 
        return view('grupos.edit', compact('grupo', 'materias')); 
    }

    public function assignAlumnos(Request $request, Grupo $grupo)
    {
        $request->validate([
            'alumnos' => 'required|array', 
            'alumnos.*' => 'exists:alumnos,id', 
        ]);

        
        $grupo->alumnos()->syncWithoutDetaching($request->alumnos);

        return redirect()->route('grupos.show', $grupo->id)->with('success', 'Alumnos asignados exitosamente.');
    }

    public function update(Request $request, Grupo $grupo)
    {
        $request->validate([
            'nombre_grupo' => 'required|string|max:255',
            'materia_id' => 'required|exists:materias,id',
        ]);

        $grupo->update($request->all()); 
        return redirect()->route('grupos.index')->with('success', 'Grupo actualizado exitosamente.');
    }

    public function show(Grupo $grupo)
    {
        $grupo->load('materia', 'alumnos');
        $alumnos = \App\Models\Alumno::all(); 
        return view('grupos.show', compact('grupo', 'alumnos'));
    }
    
    public function destroy(Grupo $grupo)
    {
        $grupo->delete(); 
        return redirect()->route('grupos.index')->with('success', 'Grupo eliminado exitosamente.');
    }
}