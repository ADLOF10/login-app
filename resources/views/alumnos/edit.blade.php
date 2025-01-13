@extends('layouts.app')

@section('title', 'Editar Alumno')

@section('content')
<div class="container mt-5">
<div class="d-flex justify-content-between align-items-center ">
    <h1 class="mb-4">Editar Alumno</h1> 
</div>
    <div class="card shadow">
        
        <div class="card-body">
            <form action="{{ route('alumnos.update', $alumno->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $alumno->nombre) }}" required>
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos:</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" value="{{ old('apellidos', $alumno->apellidos) }}" required>
                </div>
                <div class="mb-3">
                    <label for="correo_institucional" class="form-label">Correo Institucional:</label>
                    <input type="email" name="correo_institucional" id="correo_institucional" class="form-control" value="{{ old('correo_institucional', $alumno->correo_institucional) }}" required>
                </div>
                <div class="mb-3">
                    <label for="numero_cuenta" class="form-label">Número de Cuenta:</label>
                    <input type="text" name="numero_cuenta" id="numero_cuenta" class="form-control" value="{{ old('numero_cuenta', $alumno->numero_cuenta) }}" required>
                </div>
                <div class="mb-3">
                    <label for="semestre" class="form-label">Semestre:</label>
                    <input type="text" name="semestre" id="semestre" class="form-control" value="{{ old('semestre', $alumno->semestre) }}">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success-custom">Guardar Cambios</button>
                    <a href="{{ route('alumnos.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
