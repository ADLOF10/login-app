@extends('layouts.app')

@section('title', 'Detalle de la Materia')

@section('content')
<div class="container mt-4">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-4">Detalle de la Materia</h1> <a href="{{ route('materias.index') }}" class="btn btn-secondary">Volver a la lista</a>
</div>
    <div class="card p-4 shadow">
        <p><strong>ID:</strong> {{ $materia->id }}</p>
        <p><strong>Nombre:</strong> {{ $materia->nombre }}</p>
        <p><strong>Clave:</strong> {{ $materia->clave }}</p>
        <p><strong>Docente:</strong> {{ $materia->docente->name ?? 'Sin docente asignado' }}</p>
    </div>

    <h2 class="mt-4">Grupos Asociados</h2>
    @if($materia->grupos->isEmpty())
        <p class="text-muted">No hay grupos asociados a esta materia.</p>
    @else
        <ul class="list-group">
            @foreach($materia->grupos as $grupo)
                <li class="list-group-item">{{ $grupo->nombre_grupo }}</li>
            @endforeach
        </ul>
    @endif
</div>
@endsection

