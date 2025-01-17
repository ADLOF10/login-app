@extends('layouts.app')

@section('title', 'Lista de Alumnos')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Lista de Alumnos</h1>
        <div>
            <a href="{{ route('alumnos.create') }}" class="btn btn-success-custom">Registrar Alumno</a>
            <a href="#" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#estructuraCSVModal">Subir Alumnos (CSV o XLSX)</a>
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

    <div class="mb-4">
        <input 
            type="text" 
            id="searchAlumno" 
            class="form-control" 
            placeholder="Buscar por nombre, correo o ID" 
            oninput="filterAlumnos()"
        >
    </div>

    <form method="GET" action="{{ route('alumnos.index') }}" class="mb-4">
        <label for="Grupoo_id">Seleccionar Grupo:</label>
        <select name="Grupoo_id" id="Grupoo_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- Todos los Grupos --</option>
            @foreach($materias as $materia)
                <option value="{{ $materia->id }}" {{ $GrupoId == $materia->id ? 'selected' : '' }}>
                    {{ $materia->nombre_grupo }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="container mt-3">
        <h3 class="mb-4 text-center">Gráficas Generales de Asistencias</h3>
        <div class="row d-flex justify-content-between align-items-center">
            <div class="col-md-7">
                <div class="chart-container" style="overflow-x: auto; overflow-y: auto; max-height: 400px;">
                    <canvas id="graficaBarras" style="min-width: 800px; height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered mt-4" id="alumnosTable">
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
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- <div class="modal fade" id="estructuraCSVModal" tabindex="-1" aria-labelledby="estructuraCSVLabel" aria-hidden="true">
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
</div> --}}

<div class="modal fade" id="estructuraCSVModal" tabindex="-1" aria-labelledby="estructuraCSVLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="estructuraCSVLabel">Subir Alumnos desde CSV o XLSX</h5>
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
                <form action="{{ route('alumnos.csv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="archivo_csv" class="form-label">Selecciona el archivo CSV o XLSX:</label>
                        <input type="file" class="form-control" name="archivo_csv" id="archivo_csv" accept=".csv, .xlsx" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Subir y Registrar Alumnos</button>
                </form>
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
    const datosGraficaBarras = {!! json_encode($datosGraficaBarras) !!};

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

    function filterAlumnos() {
        const searchInput = document.getElementById('searchAlumno').value.toLowerCase();
        const table = document.getElementById('alumnosTable');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
            if (rowText.includes(searchInput)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
