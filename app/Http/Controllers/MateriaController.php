<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MateriaController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id(); // Obtén el ID del usuario autenticado
        $search = $request->input('search');

        // Filtrar materias según el usuario autenticado
        $materias = Materia::with('docente')
            ->where('user_id', $userId)
            ->when($search, function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%")
                    ->orWhere('clave', 'like', "%{$search}%")
                    ->orWhereHas('docente', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->get();

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

        Materia::create([
            'nombre' => $request->nombre,
            'clave' => $request->clave,
            'user_id' => Auth::id(), // Asocia automáticamente al usuario autenticado
        ]);

        return redirect()->route('materias.index')->with('success', 'Materia creada exitosamente.');
    }

    public function edit(Materia $materia)
    {
        $this->authorizeMateria($materia); // Verifica que el usuario tenga acceso

        $docentes = User::where('role', 'profesor')->get();
        return view('materias.edit', compact('materia', 'docentes'));
    }

    public function update(Request $request, Materia $materia)
    {
        $this->authorizeMateria($materia); // Verifica que el usuario tenga acceso

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
        $this->authorizeMateria($materia); // Verifica que el usuario tenga acceso

        $materia->load('grupos', 'docente');
        return view('materias.show', compact('materia'));
    }

    public function destroy(Materia $materia)
    {
        $this->authorizeMateria($materia); // Verifica que el usuario tenga acceso

        $materia->delete();
        return redirect()->route('materias.index')->with('success', 'Materia eliminada exitosamente.');
    }

    /**
     * Autoriza que el usuario autenticado solo pueda acceder a sus materias.
     */
    private function authorizeMateria(Materia $materia)
    {
        if ($materia->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para acceder a esta materia.');
        }
    }
}
