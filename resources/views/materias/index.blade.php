@extends('layouts.app')

@section('title', 'Lista de Materias')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Materias</h1>
        <a href="{{ route('materias.create') }}" class="btn btn-success-custom">Registrar Nueva Materia</a>
    </div>
    <form id="search-form" action="{{ route('materias.index') }}" method="GET" class="mb-3 d-flex">
        <input 
            type="text" 
            name="search" 
            id="search-input"
            class="form-control me-2" 
            placeholder="Buscar por nombre o clave..." 
            value="{{ request('search') }}"
        >
        <a href="{{ route('materias.index') }}" class="btn btn-secondary">Limpiar</a>
    </form>
    <table class="table table-striped table-bordered">
        <thead class="text-white" style="background-color: #004d40;">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Clave</th>
                <th>Docente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materias as $materia)
            <tr>
                <td>{{ $materia->id }}</td>
                <td>{{ $materia->nombre }}</td>
                <td>{{ $materia->clave }}</td>
                <td>{{ Auth::user()->name }}</td>
                <td>
                    <a href="{{ route('materias.show', $materia->id) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('materias.edit', $materia->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('materias.destroy', $materia->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta materia?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>        
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('search-form');

        searchInput.addEventListener('input', function () {
            searchForm.submit();
        });
    });
</script>
@endsection


