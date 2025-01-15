<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class GrupoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $grupos = Grupo::with('materia')
            ->when($search, function ($query, $search) {
                $query->where('nombre_grupo', 'like', "%$search%")
                    ->orWhereHas('materia', function ($query) use ($search) {
                        $query->where('nombre', 'like', "%$search%");
                    });
            })
            ->get();

        return view('grupos.index', compact('grupos'));
    }


    public function create()
    {
       // $materias = Materia::all(); 
       $user_prof=Auth::user()->name;
        $materias = DB::table('materias')
        ->join('users', 'materias.user_id', '=', 'users.id')
        ->select('materias.id','materias.nombre')
        ->where('users.name',$user_prof)
        ->get();
        return view('grupos.create', compact('materias','user_prof'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_grupo' => [
                'required',
                'string',
                'max:15',
                'regex:/^[a-zA-Z0-9\s\-]+$/', // Solo letras, números, espacios y guiones
                'unique:grupos,nombre_grupo',
            ],
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
            'nombre_grupo' => [
                'required',
                'string',
                'max:15',
                'regex:/^[a-zA-Z0-9\s\-]+$/',
                Rule::unique('grupos', 'nombre_grupo')->ignore($grupo->id),
            ],
            'materia_id' => 'required|exists:materias,id',
        ], [
            'nombre_grupo.required' => 'El nombre del grupo es obligatorio.',
            'nombre_grupo.regex' => 'El nombre del grupo solo puede contener letras, números, espacios y guiones.',
            'nombre_grupo.unique' => 'Ya existe un grupo con este nombre.',
            'materia_id.required' => 'La materia es obligatoria.',
        ]);

        $grupo->update($request->only(['nombre_grupo', 'materia_id']));

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

    public function removeAlumno(Request $request, Grupo $grupo)
    {
        $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
        ]);

        $grupo->alumnos()->detach($request->alumno_id);

        return response()->json(['success' => true]);
    }

}