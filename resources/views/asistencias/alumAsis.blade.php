@extends('layouts.app')

@section('title', 'Lista de Asistencias')


@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>lista de alumnos en este grupo</h1>
        <td>
            <a href="{{ route('asistencias.index') }}" class="btn btn-info btn-sm" >Regresar</a>
        </td>
    
    </div>
    <table class="table table-striped table-bordered">
    <thead class="text-white" style="background-color: #004d40;">
        <tr>
            <th>ID</th>
            <th>Grupo</th>
            <th>Alumno</th>
            <th>Apellidos</th>
            <th>Correo Institucional</th>
            <th>Numero de Cuanta</th>
            <th>Consultar asistencias por  alumnos</th> 
        </tr>
    </thead>
    <tbody>
        @foreach($alumnos as $alumno)
        <tr>
            <td>{{ $alumno->id }}</td>
            <td>{{ $alumno->grupo->nombre_grupo }}</td>
            <td>{{ $alumno->alumno->nombre }}</td>
            <td>{{ $alumno->alumno->apellidos }}</td>
            <td>{{ $alumno->alumno->correo_institucional }}</td>
            <td>{{ $alumno->alumno->numero_cuenta }}</td>
            <td>
                <a href="{{ route('alumListaasis', $alumno->alumno_id) }}" class="btn btn-info btn-sm" >Detalles</a>
                
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
    </div>

@endsection