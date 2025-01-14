@extends('layouts.app')

@section('title', 'Detalle del Grupo')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-4">Detalles del Grupo</h1>
        <a href="{{ route('grupos.index') }}" class="btn btn-secondary">Volver a la lista</a>
    </div>
    <div class="card p-4 shadow">
        <p><strong>ID:</strong> {{ $grupo->id }}</p>
        <p><strong>Nombre del Grupo:</strong> {{ $grupo->nombre_grupo }}</p>
        <p><strong>Materia:</strong> {{ $grupo->materia->nombre }}</p>
    </div>

    <!-- Alumnos en este grupo -->
    <h2 class="mt-4">Alumnos en este grupo</h2>
    <div class="mb-3">
        <input 
            type="text" 
            id="search-alumnos-grupo" 
            class="form-control" 
            placeholder="Buscar alumnos en este grupo..."
        >
    </div>
    @if($grupo->alumnos->isEmpty())
        <p>No hay alumnos asignados a este grupo.</p>
    @else
        <ul class="list-group mb-4" id="alumnos-grupo-list">
            @foreach($grupo->alumnos as $alumno)
            <li class="list-group-item">{{ $alumno->nombre }} {{ $alumno->apellidos }}</li>
            @endforeach
        </ul>
    @endif

    <!-- Asignar Alumnos -->
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
                <label for="alumnos" class="form-label">Selecciona Alumnos:</label>
                <button type="submit" class="btn btn-custom">Asignar</button>
            </div>
            <div class="mb-3">
                <input 
                    type="text" 
                    id="search-alumnos-asignar" 
                    class="form-control mb-2" 
                    placeholder="Buscar alumnos para asignar..."
                >
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

<script>
    // Filtrar alumnos en este grupo
    document.getElementById('search-alumnos-grupo').addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const alumnos = document.querySelectorAll('#alumnos-grupo-list .list-group-item');
        alumnos.forEach(alumno => {
            const text = alumno.textContent.toLowerCase();
            alumno.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Filtrar alumnos en el selector múltiple
    document.getElementById('search-alumnos-asignar').addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const opciones = document.querySelectorAll('#alumnos option');
        opciones.forEach(opcion => {
            const text = opcion.textContent.toLowerCase();
            opcion.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection
