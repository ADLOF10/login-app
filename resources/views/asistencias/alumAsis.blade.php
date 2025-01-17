@extends('layouts.app')

@section('title', 'Lista de Asistencias')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Asistencia por Alumno en este grupo</h1>
        <td>
            <a href="{{ route('asistencias.index') }}" class="btn btn-info btn-sm">Regresar</a>
        </td>
    </div>

    @if($alumnos->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            Primero debes agregar alumnos a este grupo para poder visualizar las asistencias.
        </div>
    @else
        <table class="table table-striped table-bordered">
            <thead class="text-white" style="background-color: #004d40;">
                <tr>
                    <th>ID</th>
                    <th>Grupo</th>
                    <th>Alumno</th>
                    <th>Apellidos</th>
                    <th>Correo Institucional</th>
                    <th>Numero de Cuenta</th>
                    <th>Consultar asistencias por alumnos</th> 
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
                        <a href="{{ route('alumnos.show', $alumno->alumno_id) }}" class="btn btn-info btn-sm">Ver Gráfica</a>
                        <a href="{{ route('alumListaasis', $alumno->alumno_id) }}" class="btn btn-warning btn-sm">Historial en Lista</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
