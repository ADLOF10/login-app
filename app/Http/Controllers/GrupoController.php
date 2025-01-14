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
        'nombre_grupo' => 'required|string|regex:/^[a-zA-Z0-9\s\-]+$/|max:15|unique:grupos,nombre_grupo',
        'materia_id' => 'required|exists:materias,id',
    ], [
        'nombre_grupo.required' => 'El nombre del grupo es obligatorio.',
        'nombre_grupo.regex' => 'El nombre del grupo solo puede contener letras, números, espacios y guiones.',
        'nombre_grupo.max' => 'El nombre del grupo no puede exceder los 15 caracteres.',
        'nombre_grupo.unique' => 'El nombre del grupo ya está registrado.',
        'materia_id.required' => 'Debe seleccionar una materia.',
        'materia_id.exists' => 'La materia seleccionada no es válida.',
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
        'nombre_grupo' => 'required|string|regex:/^[a-zA-Z0-9\s\-]+$/|max:15|unique:grupos,nombre_grupo,' . $grupo->id . ',id,materia_id,' . $request->materia_id,
        'materia_id' => 'required|exists:materias,id',
    ], [
        'nombre_grupo.required' => 'El nombre del grupo es obligatorio.',
        'nombre_grupo.regex' => 'El nombre del grupo solo puede contener letras, números, espacios y guiones.',
        'nombre_grupo.max' => 'El nombre del grupo no puede exceder los 15 caracteres.',
        'nombre_grupo.unique' => 'El grupo ya está registrado para esta materia.',
        'materia_id.required' => 'Debe seleccionar una materia.',
        'materia_id.exists' => 'La materia seleccionada no es válida.',
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