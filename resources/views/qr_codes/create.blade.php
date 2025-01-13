@extends('layouts.app')

@section('title', 'Generar Código QR')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Generar Nuevo Código QR</h1>
        <a href="{{ route('qr_codes.index') }}" class="btn btn-secondary">Volver a la lista</a>
    </div>
    <form action="{{ route('qr_codes.store') }}" method="POST" class="card p-4 shadow">
        @csrf
        <div class="mb-3">
            <label for="grupo_id" class="form-label">Grupo:</label>
            <select name="grupo_id" id="grupo_id" class="form-select" required>
                <option value="">Selecciona un grupo</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->nombre_grupo }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="materia_id" class="form-label">Materia:</label>
            <select name="materia_id" id="materia_id" class="form-select" required>
                <option value="">Selecciona una materia</option>
                @foreach($materias as $materia)
                    <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de Código:</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="asistencia">Asistencia</option>
                
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha_clase" class="form-label">Fecha de Clase:</label>
            <input type="date" name="fecha_clase" id="fecha_clase" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="hora_clase" class="form-label">Hora de Clase:</label>
            <input type="time" name="hora_clase" id="hora_clase" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="asistencia" class="form-label">Minutos para Asistencia:</label>
            <input type="number" name="asistencia" id="asistencia" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="retardo" class="form-label">Minutos para Retardo:</label>
            <input type="number" name="retardo" id="retardo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="inasistencia" class="form-label">Minutos para Inasistencia:</label>
            <input type="number" name="inasistencia" id="inasistencia" class="form-control" required>
        </div>
        <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success-custom">Generar Código QR</button>
        </div>
    </form>
</div>
@endsection
