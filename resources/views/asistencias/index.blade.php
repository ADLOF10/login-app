@extends('layouts.app')

@section('title', 'Lista de Asistencias')

@section('content')
<div class="container">
<div class="d-flex justify-content-between align-items-center mb-4">
<h1>Lista de Asistencias</h1>

</div>
<table class="table table-striped table-bordered">
<thead class="text-white" style="background-color: #004d40;">
    <tr>
        <th>ID</th>
        <th>Alumno</th>
        <th>Grupo</th>
        <th>Materia</th> 
        <th>Fecha de Clase</th> 
        <th>Hora de Clase</th> 
        <th>Fecha</th>
        <th>Hora de Registro</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
</thead>
<tbody>
    @foreach($asistencias as $asistencia)
    <tr>
        <td>{{ $asistencia->id }}</td>
        <td>{{ $asistencia->alumno->nombre }} {{ $asistencia->alumno->apellidos }}</td>
        <td>{{ $asistencia->grupo->nombre_grupo }}</td>
        <td>{{ $asistencia->materia->nombre }}</td> 
        <td>{{ $asistencia->fecha_clase }}</td> 
        <td>{{ $asistencia->hora_clase }}</td> 
        <td>{{ $asistencia->fecha }}</td>
        <td>{{ $asistencia->hora_registro }}</td>
        <td>{{ ucfirst($asistencia->tipo) }}</td>
        <td>{{ ucfirst($asistencia->estado) }}</td>
        <td>
            <form action="{{ route('asistencias.destroy', $asistencia->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</tbody>
</table>
</div>
@endsection
