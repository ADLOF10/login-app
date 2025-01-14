@extends('layouts.app')

@section('title', 'Detalle del Grupo')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-4">Detalles del Grupo</h1>
        <a href="{{ route('grupos.index') }}" class="btn btn-secondary">Volver a la lista</a>
    </div>
    <div class="card p-4 shadow">
        <p><strong>ID:</strong> {{ $grupo->id }}</p>
        <p><strong>Nombre del Grupo:</strong> {{ $grupo->nombre_grupo }}</p>
        <p><strong>Materia:</strong> {{ $grupo->materia->nombre }}</p>
    </div>

    <!-- Alumnos en este grupo -->
    <h2 class="mt-4">Alumnos en este grupo</h2>
    <div class="mb-3">
        <input 
            type="text" 
            id="search-alumnos-grupo" 
            class="form-control" 
            placeholder="Buscar alumnos en este grupo..."
        >
    </div>
    <div id="no-alumnos-message" class="{{ $grupo->alumnos->isEmpty() ? '' : 'd-none' }}">
        <p>No hay alumnos asignados a este grupo.</p>
    </div>
    @if(!$grupo->alumnos->isEmpty())
        <table class="table table-bordered" id="alumnos-grupo-table">
            <thead>
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
                            Eliminar
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Asignar Alumnos -->
    <h2>Asignar Alumnos</h2>
    @if ($errors->has('alumnos'))
        <div class="alert alert-danger">
            {{ $errors->first('alumnos') }}
        </div>
    @endif
    <form id="assign-student-form" action="{{ route('grupos.assign-alumnos', $grupo->id) }}" method="POST" class="card p-4 shadow">
        @csrf
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <label for="alumnos" class="form-label">Selecciona Alumnos:</label>
                <button type="submit" class="btn btn-custom">Asignar</button>
            </div>
            <div class="mb-3">
                <input 
                    type="text" 
                    id="search-alumnos-asignar" 
                    class="form-control mb-2" 
                    placeholder="Buscar alumnos para asignar..."
                >
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

<script>
    // Función para permitir solo números y letras
    function allowAlphanumeric(input) {
        input.value = input.value.replace(/[^a-zA-Z0-9]/g, '');
    }

    // Filtrar alumnos en este grupo
    document.getElementById('search-alumnos-grupo').addEventListener('input', function() {
        allowAlphanumeric(this);
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#alumnos-grupo-table tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Filtrar alumnos en el selector múltiple
    document.getElementById('search-alumnos-asignar').addEventListener('input', function() {
        allowAlphanumeric(this);
        const filter = this.value.toLowerCase();
        const opciones = document.querySelectorAll('#alumnos option');
        opciones.forEach(opcion => {
            const text = opcion.textContent.toLowerCase();
            opcion.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Eliminar alumnos del grupo dinámicamente
    document.querySelectorAll('.remove-alumno').forEach(button => {
        button.addEventListener('click', function() {
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
                    // Eliminar de la tabla
                    const row = document.querySelector(`#alumnos-grupo-table tbody tr[data-alumno-id="${alumnoId}"]`);
                    row.remove();

                    // Verificar si ya no hay alumnos en la tabla
                    const remainingRows = document.querySelectorAll('#alumnos-grupo-table tbody tr');
                    if (remainingRows.length === 0) {
                        document.getElementById('alumnos-grupo-table').classList.add('d-none');
                        document.getElementById('no-alumnos-message').classList.remove('d-none');
                    }

                    // Agregar al selector múltiple
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
