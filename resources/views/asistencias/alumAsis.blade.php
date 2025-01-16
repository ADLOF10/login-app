@extends('layouts.app')

@section('title', 'Lista de Asistencias')


@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>lista de alumnos en este grupo</h1>
    
    </div>
    <table class="table table-striped table-bordered">
    <thead class="text-white" style="background-color: #004d40;">
        <tr>
            <th>ID</th>
            <th>nombre alumno</th>
            <th>apellidos</th>
            <th>consultar alumnos</th> 
        </tr>
    </thead>
    <tbody>
        @foreach($alumnos as $alumno)
        <tr>
            <td>{{ $alumno->id }}</td>
            <td>{{ $alumno->nombre }}</td>
            <td>{{ $alumno->apellidos }}</td>
            <td>
                <a href="{{ route('alumListaasis', $alumno->id) }}" class="btn btn-info btn-sm" >Detalles</a>
                
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
    </div>

@endsection