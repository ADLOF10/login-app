@extends('layouts.app')

@section('title', 'Eliminar Alumno')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-danger text-white">
            <h2 class="mb-0">Eliminar Alumno</h2>
        </div>
        <div class="card-body">
            <p>¿Estás seguro de que deseas eliminar al alumno <strong>{{ $alumno->nombre }} {{ $alumno->apellidos }}</strong>?</p>
            <form action="{{ route('alumnos.destroy', $alumno->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
