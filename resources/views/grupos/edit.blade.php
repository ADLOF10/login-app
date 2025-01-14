@extends('layouts.app')

@section('title', 'Editar Grupo')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-4">Editar Grupo</h1>
    </div>
    <form action="{{ route('grupos.update', $grupo->id) }}" method="POST" class="card p-4 shadow">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nombre_grupo" class="form-label">
                Nombre del Grupo: <small>(Máximo 15 caracteres. Letras, números, espacios y guiones.)</small>
            </label>
            <input 
                type="text" 
                name="nombre_grupo" 
                id="nombre_grupo" 
                class="form-control" 
                value="{{ old('nombre_grupo', $grupo->nombre_grupo) }}" 
                required 
                pattern="[a-zA-Z0-9\s\-]+" 
                maxlength="15" 
                placeholder="Ejemplo: Grupo A-1"
            >
            @error('nombre_grupo')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="materia_id" class="form-label">Materia:</label>
            <select name="materia_id" id="materia_id" class="form-select" required>
                <option value="">Selecciona una materia</option>
                @foreach($materias as $materia)
                <option value="{{ $materia->id }}" {{ old('materia_id', $grupo->materia_id) == $materia->id ? 'selected' : '' }}>
                    {{ $materia->nombre }}
                </option>
                @endforeach
            </select>
            @error('materia_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-custom">Actualizar Grupo</button>
            <a href="{{ route('grupos.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
        </div>
    </form>
</div>
@endsection
