@extends('layouts.app')

@section('title', 'Editar Materia')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Editar Materia</h1>
        <a href="{{ route('materias.index') }}" class="btn btn-secondary">Volver a la lista</a>
    </div>
    <form action="{{ route('materias.update', $materia->id) }}" method="POST" class="card p-4 shadow">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Materia:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $materia->nombre }}" required>
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Clave:</label>
            <input type="text" name="clave" id="clave" class="form-control" value="{{ $materia->clave }}" required>
        </div>
        <div class="mb-3">
            <label for="user_id" class="form-label">Docente:</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">Selecciona un docente</option>
                @foreach($docentes as $docente)
                <option value="{{ $docente->id }}" {{ $materia->user_id == $docente->id ? 'selected' : '' }}>
                    {{ $docente->name }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success-custom">Actualizar Materia</button>
    </form>
</div>
@endsection
