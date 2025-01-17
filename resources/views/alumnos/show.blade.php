@extends('layouts.app')

@section('title', 'Detalles del Alumno')

@section('content')

<div class="container mt-5">
    <div class="text-center">
        <h3>{{ $alumno->nombre }} {{ $alumno->apellidos }}</h3>
        <p><strong>Porcentaje de Asistencia:</strong> {{ $porcentajeAsistencia }}%</p>
    </div>
    <div class="d-flex justify-content-center align-items-center mt-4">
        <div class="chart-container" style="width: 60%; max-width: 500px;">
            <canvas id="graficaAsistencias"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficaAsistencias').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($datosGrafica->keys()) !!},
            datasets: [{
                data: {!! json_encode($datosGrafica->values()) !!},
                backgroundColor: ['#ff9800', '#4caf50', '#f44336'], 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 14 },
                        padding: 20
                    }
                }
            }
        }
    });
</script>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-custom text-green">
            <h2 class="mb-0">Detalles del Alumno</h2>
        </div>
        <div class="card-body text-center ">
        <img src="{{ asset('storage/' . $alumno->foto_perfil) }}" alt="Foto de Perfil" style="width: 150px; height: 150px; border-radius: 50%; margin-bottom: 20px;">

            <ul class="list-group  text-start">
                <li class="list-group-item"><strong>Nombre:</strong> {{ $alumno->nombre }}</li>
                <li class="list-group-item"><strong>Apellidos:</strong> {{ $alumno->apellidos }}</li>
                <li class="list-group-item"><strong>Correo Institucional:</strong> {{ $alumno->correo_institucional }}</li>
                <li class="list-group-item"><strong>Número de Cuenta:</strong> {{ $alumno->numero_cuenta }}</li>
                <li class="list-group-item"><strong>Semestre:</strong> {{ $alumno->semestre }}</li>
            </ul>
            <div class="mt-4">
                <a href="{{ route('asistencias.index') }}" class="btn btn-secondary">Regresar</a>
            </div>
        </div>
    </div>
</div>
@endsection
