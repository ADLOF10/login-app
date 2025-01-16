@extends('layouts.app')

@section('title', 'Lista de Asistencias por grupo')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Lista de asistencia por grupos</h1>
    
    </div>
    <table class="table table-striped table-bordered">
    <thead class="text-white" style="background-color: #004d40;">
        <tr>
            <th>ID</th>
            <th>nombre de Grupo</th>
            <th>materia</th>
            <th>consultar alumnos</th> 
        </tr>
    </thead>
    <tbody>
        @foreach($grupos as $grupo)
        <tr>
            <td>{{ $grupo->id }}</td>
            <td>{{ $grupo->nombre_grupo }}</td>
            <td>{{ $grupo->materia->nombre }}</td>
            <td>
                <a href="{{ route('alum.asis', $grupo->id) }}" class="btn btn-info btn-sm" >Detalles</a>
                
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
    </div>

@endsection
