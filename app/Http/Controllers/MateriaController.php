<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\User;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::with('docente')->get();
        return view('materias.index', compact('materias'));
        
    }

    public function create()
    {
    
        $docentes = User::where('role', 'profesor')->get();
        return view('materias.create', compact('docentes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:35',
            'clave' => 'required|string|regex:/^[a-zA-Z0-9]+$/|max:20|unique:materias',
            'user_id' => 'required|exists:users,id',
        ], [
            'nombre.regex' => 'El campo Nombre de la Materia solo puede contener letras y espacios.',
            'clave.regex' => 'El campo Clave solo puede contener letras y números.',
            'clave.unique' => 'Esa clave de materia ya fue creada. Intenta con otra.',
        ]);

        Materia::create($request->all()); 
        return redirect()->route('materias.index')->with('success', 'Materia creada exitosamente.');
    }

    public function edit(Materia $materia)
    {
        $docentes = User::where('role', 'profesor')->get(); 
        return view('materias.edit', compact('materia', 'docentes'));
    }

    public function update(Request $request, Materia $materia)
    {
        $request->validate([
            'nombre' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:35',
            'clave' => 'required|string|regex:/^[a-zA-Z0-9]+$/|max:20|unique:materias,clave,' . $materia->id,
            'user_id' => 'required|exists:users,id',
        ], [
            'nombre.regex' => 'El campo Nombre de la Materia solo puede contener letras y espacios.',
            'clave.regex' => 'El campo Clave solo puede contener letras y números.',
            'clave.unique' => 'Esa clave de materia ya fue creada. Intenta con otra.',
        ]);

        $materia->update($request->all()); 
        return redirect()->route('materias.index')->with('success', 'Materia actualizada exitosamente.');
    }

    public function show(Materia $materia)
    {
        $materia->load('grupos', 'docente');
        return view('materias.show', compact('materia'));
    }

    public function destroy(Materia $materia)
    {
        $materia->delete();
        return redirect()->route('materias.index')->with('success', 'Materia eliminada exitosamente.');
    }
}