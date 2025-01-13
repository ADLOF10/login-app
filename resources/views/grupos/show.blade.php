@extends('layouts.app')

@section('title', 'Detalle del Grupo')

@section('content')
<div class="container mt-4">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-4">Detalles del Grupo</h1> <a href="{{ route('grupos.index') }}" class="btn btn-secondary">Volver a la lista</a>
</div>
    <div class="card p-4 shadow">
        <p><strong>ID:</strong> {{ $grupo->id }}</p>
        <p><strong>Nombre del Grupo:</strong> {{ $grupo->nombre_grupo }}</p>
        <p><strong>Materia:</strong> {{ $grupo->materia->nombre }}</p>
    </div>
    <h2 class="mt-4">Alumnos en este grupo</h2>
    @if($grupo->alumnos->isEmpty())
        <p>No hay alumnos asignados a este grupo.</p>
    @else
        <ul class="list-group mb-4">
            @foreach($grupo->alumnos as $alumno)
            <li class="list-group-item">{{ $alumno->nombre }} {{ $alumno->apellidos }}</li>
            @endforeach
        </ul>
    @endif
    <h2>Asignar Alumnos</h2>
    @if ($errors->has('alumnos'))
        <div class="alert alert-danger">
            {{ $errors->first('alumnos') }}
        </div>
    @endif
    <form action="{{ route('grupos.assign-alumnos', $grupo->id) }}" method="POST" class="card p-4 shadow">
        @csrf
        <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <label for="alumnos" class="form-label">Selecciona Alumnos:</label> <button type="submit" class="btn btn-custom">Asignar</button>
        </div>
            <select name="alumnos[]" id="alumnos" class="form-select" multiple required>
                @foreach($alumnos as $alumno)
                <option value="{{ $alumno->id }}" {{ $grupo->alumnos->contains($alumno->id) ? 'selected' : '' }}>
                    {{ $alumno->nombre }} {{ $alumno->apellidos }}
                </option>
                @endforeach
            </select>
        </div>
    </form>
</div>
@endsection
