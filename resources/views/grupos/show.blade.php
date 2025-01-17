@extends('layouts.app')

@section('title', 'Detalle del Grupo')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-dark"><i class="fas fa-info-circle"></i> Detalles del Grupo</h1>
        <a href="{{ route('grupos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la lista
        </a>
    </div>

    <div class="card shadow-sm p-4 mb-4" style="background-color: #f8f9fa;">
        <h2 class="text-dark">Información del Grupo</h2>
        <p><strong>ID:</strong> {{ $grupo->id }}</p>
        <p><strong>Nombre del Grupo:</strong> {{ $grupo->nombre_grupo }}</p>
        <p><strong>Materia:</strong> {{ $grupo->materia->nombre }}</p>
    </div>

    <!-- Alumnos en este grupo -->
    <div class="card shadow-sm p-4 mb-4" style="background-color: #f8f9fa;">
        <h2 class="text-dark"><i class="fas fa-users"></i> Alumnos en este grupo</h2>
        <div class="mb-3">
            <input 
                type="text" 
                id="search-alumnos-grupo" 
                class="form-control mb-2" 
                placeholder="Buscar alumnos en este grupo..."
                style="border-color: #004d40;"
            >
        </div>
        <div id="no-alumnos-message" class="alert alert-warning {{ $grupo->alumnos->isEmpty() ? '' : 'd-none' }}">
            <i class="fas fa-exclamation-circle"></i> No hay alumnos asignados a este grupo.
        </div>
        @if(!$grupo->alumnos->isEmpty())
            <table class="table table-striped table-hover table-bordered" id="alumnos-grupo-table">
                <thead style="background-color: #004d40; color: white;">
                    <tr>
                        <th>Nombre</th>
                        <th>Correo Institucional</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grupo->alumnos as $alumno)
                    <tr data-alumno-id="{{ $alumno->id }}">
                        <td>{{ $alumno->nombre }} {{ $alumno->apellidos }}</td>
                        <td>{{ $alumno->correo_institucional }}</td>
                        <td>
                            <button 
                                class="btn btn-danger btn-sm remove-alumno" 
                                data-alumno-id="{{ $alumno->id }}" 
                                data-alumno-nombre="{{ $alumno->nombre }} {{ $alumno->apellidos }}" 
                                data-alumno-correo="{{ $alumno->correo_institucional }}"
                            >
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Asignar Alumnos -->
    <div class="card shadow-sm p-4" style="background-color: #f8f9fa;">
        <h2 class="text-dark"><i class="fas fa-user-plus"></i> Asignar Alumnos</h2>
        @if ($errors->has('alumnos'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> {{ $errors->first('alumnos') }}
            </div>
        @endif
        <form id="assign-student-form" action="{{ route('grupos.assign-alumnos', $grupo->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="alumnos" class="form-label">Selecciona Alumnos:</label>
                <div class="d-flex align-items-center mb-3">
                    <input 
                        type="text" 
                        id="search-alumnos-asignar" 
                        class="form-control me-2" 
                        placeholder="Buscar alumnos para asignar..."
                        style="border-color: #004d40;"
                    >
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-check"></i> Asignar
                    </button>
                </div>
                <select name="alumnos[]" id="alumnos" class="form-select" multiple required>
                    @foreach($alumnos as $alumno)
                    @unless($grupo->alumnos->contains($alumno->id))
                    <option value="{{ $alumno->id }}" data-alumno-id="{{ $alumno->id }}">
                        {{ $alumno->nombre }} {{ $alumno->apellidos }} - {{ $alumno->correo_institucional }}
                    </option>
                    @endunless
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>


<script>
    document.getElementById('search-alumnos-asignar').addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('#alumnos option').forEach(option => {
            option.style.display = option.textContent.toLowerCase().includes(filter) ? '' : 'none';
        });
    });

    document.getElementById('search-alumnos-grupo').addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('#alumnos-grupo-table tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
        });
    });

    document.querySelectorAll('.remove-alumno').forEach(button => {
        button.addEventListener('click', function () {
            const alumnoId = this.dataset.alumnoId;
            const nombre = this.dataset.alumnoNombre;
            const correo = this.dataset.alumnoCorreo;

            fetch('{{ route('grupos.remove-alumno', $grupo->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ alumno_id: alumnoId }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.querySelector(`#alumnos-grupo-table tbody tr[data-alumno-id="${alumnoId}"]`);
                        row.remove();
                        const remainingRows = document.querySelectorAll('#alumnos-grupo-table tbody tr');
                        if (remainingRows.length === 0) {
                            document.getElementById('alumnos-grupo-table').classList.add('d-none');
                            document.getElementById('no-alumnos-message').classList.remove('d-none');
                        }
                        const select = document.getElementById('alumnos');
                        const option = document.createElement('option');
                        option.value = alumnoId;
                        option.textContent = `${nombre} - ${correo}`;
                        select.appendChild(option);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>
@endsection
