@extends('layouts.app')

@section('title', 'Crear Grupo')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center ">
        <h1 class="mb-4" >Crear Nuevo Grupo</h1>
    </div>
    <form action="{{ route('grupos.store') }}" method="POST" class="card p-4 shadow">
        @csrf
        <div class="mb-3">
            <label for="nombre_grupo" class="form-label">Nombre del Grupo:</label>
            <input type="text" name="nombre_grupo" id="nombre_grupo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="materia_id" class="form-label">Materia:</label>
            <select name="materia_id" id="materia_id" class="form-select" required>
                @foreach($materias as $materia)
                <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success-custom">Crear Grupo</button>
        <a href="{{ route('grupos.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
        </div>
    </form>
</div>
@endsection
