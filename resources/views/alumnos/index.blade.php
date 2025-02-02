@extends('layouts.app')

@section('title', 'Lista de Alumnos')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Lista de Alumnos</h1>
        <div>
            <a href="{{ route('alumnos.create') }}" class="btn btn-success-custom">Registrar Alumno</a>
            <a href="#" class="btn btn-secondary" onclick="mostrarEstructuraCSV()">Subir Alumnos (CSV o XLSX)</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {!! session('error') !!}
        </div>
    @endif

    <div class="container mt-3">
        <h3 class="mb-4 text-center">Gráficas Generales de Asistencias</h3>
        <div class="row d-flex justify-content-between align-items-center">
            <div class="col-md-5 d-flex justify-content-center">
                <canvas id="graficaDona" style="max-width: 300px;"></canvas>
            </div>
            <div class="col-md-7">
                <div class="chart-container" style="overflow-x: auto; overflow-y: auto; max-height: 400px;">
                    <canvas id="graficaBarras" style="min-width: 800px; height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered mt-4">
        <thead class="text-white" style="background-color: #004d40;">
            <tr>
                <th>Foto</th>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo Institucional</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($alumnos as $alumno)
            <tr>
                <td>
                    <img src="{{ asset('storage/' . $alumno->foto_perfil) }}" alt="Foto de Perfil" style="width: 50px; height: 50px; border-radius: 50%;">
                </td>
                <td>{{ $alumno->id }}</td>
                <td>{{ $alumno->nombre }} {{ $alumno->apellidos }}</td>
                <td>{{ $alumno->correo_institucional }}</td>
                <td>
                    <a href="{{ route('alumnos.show', $alumno->id) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('alumnos.edit', $alumno->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('alumnos.destroy', $alumno->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este alumno?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="estructuraCSVModal" tabindex="-1" aria-labelledby="estructuraCSVLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="estructuraCSVLabel">Estructura del CSV o XLSX</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Asegúrate de que el archivo CSV o XLSX tenga la siguiente estructura:</p>
        <ul>
          <li><strong>Nombre:</strong> Nombre del alumno.</li>
          <li><strong>Apellidos:</strong> Apellidos del alumno.</li>
          <li><strong>Correo Institucional:</strong> Debe tener el formato <code>@alumno.uaemex.wip</code>.</li>
          <li><strong>Número de Cuenta:</strong> Número único del alumno.</li>
          <li><strong>Semestre:</strong> Semestre actual del alumno.</li>
        </ul>
        <p><strong>Nota:</strong> Si algún campo no cumple con los requisitos, el archivo no se procesará.</p>
      </div>
      <div class="modal-footer">
        <a href="{{ route('alumnos.plantilla') }}" class="btn btn-success-custom" download="Plantilla_Alumnos.xlsx">Descargar Plantilla</a>
        <button type="button" class="btn btn-primary" onclick="subirCSV()">Subir CSV o XLSX</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<form action="{{ route('alumnos.csv') }}" method="POST" enctype="multipart/form-data" id="formCSV" class="d-none">
    @csrf
    <input type="file" name="archivo_csv" id="archivoCSV" accept=".csv, .xlsx" onchange="document.getElementById('formCSV').submit();">
</form>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const datosGraficaDona = {!! json_encode($datosGraficaDona) !!};
    const datosGraficaBarras = {!! json_encode($datosGraficaBarras) !!};

    const ctxDona = document.getElementById('graficaDona').getContext('2d');
    new Chart(ctxDona, {
        type: 'doughnut',
        data: {
            labels: ['Asistencias', 'Retardos', 'Inasistencias'],
            datasets: [{
                data: [
                    datosGraficaDona.asistencias,
                    datosGraficaDona.retardos,
                    datosGraficaDona.inasistencias
                ],
                backgroundColor: ['#4CAF50', '#FFC107', '#FF6384'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
            }
        }
    });

    const ctxBarras = document.getElementById('graficaBarras').getContext('2d');
    new Chart(ctxBarras, {
        type: 'bar',
        data: {
            labels: datosGraficaBarras.map(alumno => alumno.nombre.split(' ').map(word => word[0]).join('')), 
            datasets: [{
                label: 'Porcentaje de Asistencias',
                data: datosGraficaBarras.map(alumno => alumno.porcentaje),
                backgroundColor: '#36A2EB',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const index = context.dataIndex;
                            return datosGraficaBarras[index].nombre + ': ' + context.raw + '%';
                        }
                    }
                }
            },
            scales: {
                x: { ticks: { font: { size: 12 }, maxRotation: 0, minRotation: 0 } },
                y: { beginAtZero: true, ticks: { font: { size: 12 } } },
            }
        }
    });

    function mostrarEstructuraCSV() {
        const modal = new bootstrap.Modal(document.getElementById('estructuraCSVModal'));
        modal.show();
    }

    function subirCSV() {
        document.getElementById('archivoCSV').click();
    }
</script>
@endsection
