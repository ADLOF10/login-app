@extends('layouts.app')

@section('title', 'Registrar Materia')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Registrar Nueva Materia</h1>
        <a href="{{ route('materias.index') }}" class="btn btn-secondary">Volver a la lista</a>
    </div>
    <form action="{{ route('materias.store') }}" method="POST" class="card p-4 shadow">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">
                Nombre: <small>(Máximo 35 caracteres. Solo letras y espacios.)</small>
            </label>
            <input 
                type="text" 
                name="nombre" 
                id="nombre" 
                class="form-control" 
                value="{{ old('nombre') }}" 
                required 
                pattern="[a-zA-Z\s]+" 
                maxlength="35" 
                placeholder="Ejemplo: Matemáticas Discretas" 
                title="Solo se permiten letras y espacios."
            >
            @error('nombre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>        
        <div class="mb-3">
            <label for="clave" class="form-label">
                Clave: <small>(Máximo 20 caracteres. Solo letras y números.)</small>
            </label>
            <input 
                type="text" 
                name="clave" 
                id="clave" 
                class="form-control" 
                value="{{ old('clave') }}" 
                required 
                pattern="[a-zA-Z0-9]+" 
                maxlength="20" 
                placeholder="Ejemplo: ABC123" 
                title="Solo se permiten letras y números. Máximo 20 caracteres."
            >
            @error('clave')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- Campo oculto que asigna automáticamente el usuario autenticado -->
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <div class="mb-3">
            <label class="form-label">Docente Asignado:</label>
            <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
        </div>
        <button type="submit" class="btn btn-success-custom">Registrar Materia</button>
    </form>
</div>
@endsection
