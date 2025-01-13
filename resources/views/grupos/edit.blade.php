@extends('layouts.app')

@section('title', 'Editar Grupo')

@section('content')
<div class="container mt-4">
<div class="d-flex justify-content-between align-items-center ">
    <h1 class="mb-4">Editar Grupo</h1> 
</div>
    <form action="{{ route('grupos.update', $grupo->id) }}" method="POST" class="card p-4 shadow">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nombre_grupo" class="form-label">Nombre del Grupo:</label>
            <input type="text" name="nombre_grupo" id="nombre_grupo" value="{{ $grupo->nombre_grupo }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="materia_id" class="form-label">Materia:</label>
            <select name="materia_id" id="materia_id" class="form-select" required>
                @foreach($materias as $materia)
                <option value="{{ $materia->id }}" {{ $materia->id == $grupo->materia_id ? 'selected' : '' }}>
                    {{ $materia->nombre }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-custom">Actualizar Grupo</button>
    </form>
</div>
@endsection
