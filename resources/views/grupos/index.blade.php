@extends('layouts.app')

@section('title', 'Grupos')

@section('content')
<div class="container">
<div class="d-flex justify-content-between align-items-center mb-4">
<h1>Lista de Grupos</h1>
<a href="{{ route('grupos.create') }}" class="btn btn-success-custom" >Crear Nuevo Grupo</a>
</div>
<table class="table table-striped table-bordered">
    <thead class="text-white" style="background-color: #004d40;">
        <tr>
            <th>ID</th>
            <th>Nombre del Grupo</th>
            <th>Materia</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($grupos as $grupo)
        <tr>
            <td>{{ $grupo->id }}</td>
            <td>{{ $grupo->nombre_grupo }}</td>
            <td>{{ $grupo->materia->nombre }}</td>
            <td>
                <a href="{{ route('grupos.show', $grupo->id) }}" class="btn btn-info btn-sm" >Detalles</a>
                <a href="{{ route('grupos.edit', $grupo->id) }}" class="btn btn-warning btn-sm" >Editar</a>
                <form action="{{ route('grupos.destroy', $grupo->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" >Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
