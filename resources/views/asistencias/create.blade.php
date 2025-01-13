@extends('layouts.app')

@section('title', 'Registrar Asistencia')

@section('content')
<h1>Registrar Nueva Asistencia</h1>
<form action="{{ route('asistencias.store') }}" method="POST">
    @csrf
    <label for="alumno_id">Alumno:</label>
    <select name="alumno_id" id="alumno_id" required>
        @foreach($alumnos as $alumno)
        <option value="{{ $alumno->id }}">{{ $alumno->nombre }} {{ $alumno->apellidos }}</option>
        @endforeach
    </select>

    <label for="grupo_id">Grupo:</label>
    <select name="grupo_id" id="grupo_id" required>
        @foreach($grupos as $grupo)
        <option value="{{ $grupo->id }}">{{ $grupo->nombre_grupo }}</option>
        @endforeach
    </select>

    <label for="fecha">Fecha:</label>
    <input type="date" name="fecha" id="fecha" required>

    <label for="hora_registro">Hora de Registro:</label>
    <input type="time" name="hora_registro" id="hora_registro" required>

    <label for="tipo">Tipo:</label>
    <select name="tipo" id="tipo">
        <option value="asistencia">Asistencia</option>
        <option value="retardo">Retardo</option>
    </select>

    <label for="estado">Estado:</label>
    <select name="estado" id="estado">
        <option value="presente">Presente</option>
        <option value="ausente">Ausente</option>
        <option value="justificado">Justificado</option>
    </select>

    <button type="submit">Registrar</button>
</form>
@endsection