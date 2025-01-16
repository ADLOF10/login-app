@extends('layouts.app')

@section('title', 'Registrar Nuevo Alumno')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Registrar Nuevo Alumno</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('alumnos.store') }}" method="POST" class="card p-4 shadow">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input 
                type="text" 
                name="nombre" 
                id="nombre" 
                class="form-control @error('nombre') is-invalid @enderror" 
                value="{{ old('nombre') }}" 
                required 
                maxlength="255"
                placeholder="Ingresa el nombre del alumno"
            >
            @error('nombre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos:</label>
            <input 
                type="text" 
                name="apellidos" 
                id="apellidos" 
                class="form-control @error('apellidos') is-invalid @enderror" 
                value="{{ old('apellidos') }}" 
                required 
                maxlength="255"
                placeholder="Ingresa los apellidos del alumno"
            >
            @error('apellidos')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="correo_institucional" class="form-label">Correo Institucional:</label>
            <input 
                type="email" 
                name="correo_institucional" 
                id="correo_institucional" 
                class="form-control @error('correo_institucional') is-invalid @enderror" 
                value="{{ old('correo_institucional') }}" 
                required 
                maxlength="255"
                placeholder="Ejemplo: alumno@alumno.uaemex.wip"
                pattern="^[a-zA-Z0-9._%+-]+@alumno\.uaemex\.wip$"
                title="Debe tener el formato: alumno@alumno.uaemex.wip"
            >
            @error('correo_institucional')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="numero_cuenta" class="form-label">Número de Cuenta:</label>
            <input 
                type="text" 
                name="numero_cuenta" 
                id="numero_cuenta" 
                class="form-control @error('numero_cuenta') is-invalid @enderror" 
                value="{{ old('numero_cuenta') }}" 
                required 
                maxlength="7"
                placeholder="Ejemplo: 1234567"
                pattern="^\d{7}$"
                title="Debe contener exactamente 7 dígitos"
            >
            @error('numero_cuenta')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="semestre" class="form-label">Semestre:</label>
            <input 
                type="text" 
                name="semestre" 
                id="semestre" 
                class="form-control @error('semestre') is-invalid @enderror" 
                value="{{ old('semestre') }}" 
                maxlength="10"
                placeholder="Ejemplo: 8vo"
            >
            @error('semestre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="real_email" class="form-label">Correo Personal:</label>
            <input 
                type="email" 
                name="real_email" 
                id="real_email" 
                class="form-control @error('real_email') is-invalid @enderror" 
                value="{{ old('real_email') }}" 
                required 
                maxlength="255"
                placeholder="Correo personal del alumno"
            >
            @error('real_email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Registrar Alumno</button>
            <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
