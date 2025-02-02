@extends('layouts.app')

@section('title', 'Lista de Códigos QR')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Lista de Códigos QR</h1>
        <a href="{{ route('qr_codes.create') }}" class="btn btn-success-custom">Generar Nuevo Código QR</a>
    </div>
    <table class="table table-striped table-bordered">
            <thead class="text-white" style="background-color: #004d40;">
            <tr>
                <th>ID</th>
                <th>Grupo</th>
                <th>Materia</th>
                <th>Fecha de Clase</th> 
                <th>Hora de Clase</th>
                <th>Tipo</th>
                <th>Código</th>
                <th>Asistencia</th>
                <th>Retardo</th>
                <th>Inasistencia</th>
                <th>Expira</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($qrCodes as $qrCode)
            <tr>
                <td>{{ $qrCode->id }}</td>
                <td>{{ $qrCode->nombre_grupo }}</td>
                <td>{{ $qrCode->nombre }}</td>
                <td>{{ $qrCode->fecha_clase }}</td> 
                <td>{{ $qrCode->hora_clase }}</td>
                <td>{{ ucfirst($qrCode->tipo) }}</td>
                <td>
                    <img src="data:image/png;base64,{{ $qrCode->codigo }}" alt="Código QR" style="width: 70px; height: auto;">
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#qrModal-{{ $qrCode->id }}">Ver</button>
                </td>
                <td>{{ $qrCode->asistencia }}</td>
                <td>{{ $qrCode->retardo }}</td>
                <td>{{ $qrCode->inasistencia }}</td>
                <td>{{ $qrCode->expira_at }}</td>
                <td>
                    <form action="{{ route('qr_codes.destroy', $qrCode->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>

            <div class="modal fade" id="qrModal-{{ $qrCode->id }}" tabindex="-1" aria-labelledby="qrModalLabel-{{ $qrCode->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="qrModalLabel-{{ $qrCode->id }}">Código QR</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="data:image/png;base64,{{ $qrCode->codigo }}" alt="Código QR" class="img-fluid">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
