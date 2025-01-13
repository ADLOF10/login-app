@extends('layouts.app')

@section('title', 'Registrar Alumno')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center ">
            <h2 class="mb-4">Registrar Nuevo Alumno</h2>
        </div>
    <div class="card shadow">
   
        <div class="card-body">
            <form action="{{ route('alumnos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" 
                        placeholder="Escribe el nombre del alumno" value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos:</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" 
                        placeholder="Escribe los apellidos del alumno" value="{{ old('apellidos') }}" required>
                    @error('apellidos')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="correo_institucional" class="form-label">Correo Institucional:</label>
                    <input type="email" name="correo_institucional" id="correo_institucional" class="form-control" 
                        placeholder="Ejemplo: alumno@alumno.uaemex.wip" value="{{ old('correo_institucional') }}" required>
                    @error('correo_institucional')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="numero_cuenta" class="form-label">Número de Cuenta:</label>
                    <input type="text" name="numero_cuenta" id="numero_cuenta" class="form-control" 
                        placeholder="Ejemplo: 1234567" 
                        value="{{ old('numero_cuenta') }}" 
                        maxlength="7" 
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')" 
                        required>
                    @error('numero_cuenta')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="semestre" class="form-label">Semestre:</label>
                    <input type="text" name="semestre" id="semestre" class="form-control" 
                        placeholder="Ejemplo: 8vo" value="{{ old('semestre') }}" required>
                    @error('semestre')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success-custom">Registrar Alumno</button>
                    <a href="{{ route('alumnos.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
