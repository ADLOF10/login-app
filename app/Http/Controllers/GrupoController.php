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
        $userId = Auth::id(); // ID del usuario autenticado

        // Filtrar grupos según el profesor autenticado
        $grupos = Grupo::with('materia')
            ->whereHas('materia', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
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
        $userId = Auth::id(); // ID del usuario autenticado

        // Solo las materias asociadas al usuario autenticado
        $materias = Materia::where('user_id', $userId)->get();

        return view('grupos.create', compact('materias'));
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
            'materia_id' => [
                'required',
                'exists:materias,id',
                function ($attribute, $value, $fail) {
                    $userId = Auth::id();
                    $materia = Materia::find($value);

                    if (!$materia || $materia->user_id !== $userId) {
                        $fail('No tienes permiso para asignar esta materia.');
                    }
                },
            ],
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
        $this->authorizeGrupo($grupo); // Verifica que el usuario tenga acceso

        $materias = Materia::where('user_id', Auth::id())->get();
        return view('grupos.edit', compact('grupo', 'materias'));
    }

    public function update(Request $request, Grupo $grupo)
    {
        $this->authorizeGrupo($grupo); // Verifica que el usuario tenga acceso

        $request->validate([
            'nombre_grupo' => [
                'required',
                'string',
                'max:15',
                'regex:/^[a-zA-Z0-9\s\-]+$/',
                Rule::unique('grupos', 'nombre_grupo')->ignore($grupo->id),
            ],
            'materia_id' => [
                'required',
                'exists:materias,id',
                function ($attribute, $value, $fail) {
                    $userId = Auth::id();
                    $materia = Materia::find($value);

                    if (!$materia || $materia->user_id !== $userId) {
                        $fail('No tienes permiso para asignar esta materia.');
                    }
                },
            ],
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
        $this->authorizeGrupo($grupo); // Verifica que el usuario tenga acceso

        $grupo->load('materia', 'alumnos');
        $alumnos = $grupo->alumnos;
        return view('grupos.show', compact('grupo', 'alumnos'));
    }

    public function destroy(Grupo $grupo)
    {
        $this->authorizeGrupo($grupo); // Verifica que el usuario tenga acceso

        $grupo->delete();
        return redirect()->route('grupos.index')->with('success', 'Grupo eliminado exitosamente.');
    }

    /**
     * Autoriza que el usuario autenticado solo pueda acceder a sus grupos.
     */
    private function authorizeGrupo(Grupo $grupo)
    {
        if ($grupo->materia->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para acceder a este grupo.');
        }
    }
}
