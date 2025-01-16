@extends('layouts.app')

@section('title', 'Registrar Nuevo Alumno')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Registrar Nuevo Alumno</h1>

    <!-- Mensaje de error para correo o contraseña inválidos -->
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <form action="{{ route('alumnos.store') }}" method="POST" class="card p-4 shadow">
        @csrf

        <!-- Validación para Nombre -->
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input 
                type="text" 
                name="nombre" 
                id="nombre" 
                class="form-control @error('nombre') is-invalid @enderror" 
                value="{{ old('nombre') }}" 
                required 
                maxlength="35"
                pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                placeholder="Ejemplo: Juan Carlos"
                title="El nombre debe contener solo letras y espacios, máximo 35 caracteres."
            >
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Validación para Apellidos -->
        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos:</label>
            <input 
                type="text" 
                name="apellidos" 
                id="apellidos" 
                class="form-control @error('apellidos') is-invalid @enderror" 
                value="{{ old('apellidos') }}" 
                required 
                maxlength="40"
                pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                placeholder="Ejemplo: López Martínez"
                title="Los apellidos deben contener solo letras y espacios, máximo 40 caracteres."
            >
            @error('apellidos')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Validación para Correo Institucional -->
        <div class="mb-3">
            <label for="correo_institucional" class="form-label">Correo Institucional:</label>
            <input 
                type="email" 
                name="correo_institucional" 
                id="correo_institucional" 
                class="form-control @error('correo_institucional') is-invalid @enderror" 
                value="{{ old('correo_institucional') }}" 
                required 
                maxlength="50"
                placeholder="Ejemplo: alumno@alumno.uaemex.wip"
                pattern="^[a-zA-Z0-9._%+-]+@alumno\.uaemex\.wip$"
                title="Debe tener el formato: alumno@alumno.uaemex.wip"
            >
            @error('correo_institucional')
                <div class="invalid-feedback">
                    {{ $message == 'The real email has already been taken.' ? 'El correo personal ya está registrado.' : $message }}
                </div>
            @enderror
        </div>        

        <!-- Validación para Número de Cuenta -->
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
                pattern="^\d{7}$"
                placeholder="Ejemplo: 1234567"
                title="Debe contener exactamente 7 dígitos numéricos."
            >
            @error('numero_cuenta')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Validación para Semestre -->
        <div class="mb-3">
            <label for="semestre" class="form-label">Semestre:</label>
            <input 
                type="text" 
                name="semestre" 
                id="semestre" 
                class="form-control @error('semestre') is-invalid @enderror" 
                value="{{ old('semestre') }}" 
                maxlength="10"
                pattern="^[a-zA-Z0-9\s]+$"
                placeholder="Ejemplo: 8vo"
                title="El semestre no debe contener caracteres especiales."
            >
            @error('semestre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Validación para Correo Personal -->
        <div class="mb-3">
            {{-- <label for="real_email" class="form-label">Correo Personal:</label>
            <input 
                type="email" 
                name="real_email" 
                id="real_email" 
                class="form-control @error('real_email') is-invalid @enderror" 
                value="{{ old('real_email') }}" 
                required 
                maxlength="50"
                pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                placeholder="Ejemplo: correo.personal@gmail.com"
                title="Debe ser un correo electrónico válido, máximo 50 caracteres. No se permiten caracteres especiales antes del @."
            >
            @error('real_email')
                <div class="invalid-feedback">
                    {{ $message == 'The real email has already been taken.' ? 'El correo personal ya está registrado.' : $message }}
                </div>
            @enderror --}}
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Registrar Alumno</button>
            <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
