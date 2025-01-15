@extends('layouts.app')

@section('title', 'Registrar Nuevo Alumno')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Registrar Nuevo Alumno</h1>

    @if ($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif

    <!-- Formulario para buscar al alumno -->
    <form id="search-student-form" action="{{ route('alumnos.search') }}" method="POST" class="card p-4 shadow">
        @csrf
        <div class="mb-3">
            <label for="correo_institucional" class="form-label">Correo Institucional:</label>
            <input 
                type="email" 
                name="correo_institucional" 
                id="correo_institucional" 
                class="form-control @error('correo_institucional') is-invalid @enderror" 
                maxlength="35" 
                value="{{ old('correo_institucional') }}" 
                required 
                placeholder="Ejemplo: alumno@alumno.uaemex.wip"
                pattern="^[a-zA-Z._%+-]+@([a-zA-Z]+\.)?uaemex\.wip$"
                title="El correo debe tener un formato válido: Sin caracteres especiales antes del @."
            >
            @error('correo_institucional')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="numero_cuenta" class="form-label">Número de Cuenta:</label>
            <input 
                type="text" 
                name="numero_cuenta" 
                id="numero_cuenta" 
                class="form-control @error('numero_cuenta') is-invalid @enderror" 
                maxlength="7" 
                value="{{ old('numero_cuenta') }}" 
                required 
                placeholder="Ejemplo: 1234567"
                pattern="^\d{7}$"
                title="El número de cuenta debe tener exactamente 7 dígitos."
            >
            @error('numero_cuenta')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Buscar Alumno</button>
            <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>

    <div id="student-info" class="mt-4 d-none">
        <h2 class="mb-3">Información del Alumno</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Semestre</th>
                    <th>Grupo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="nombre"></td>
                    <td id="apellidos"></td>
                    <td id="semestre"></td>
                    <td>
                        <form id="add-student-form" action="{{ route('alumnos.add') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="alumno_id" id="alumno_id">
                            <div class="mb-3">
                                <label for="grupo_id" class="form-label">Grupo:</label>
                                <select name="grupo_id" id="grupo_id" class="form-select" required>
                                    <option value="">Selecciona un grupo</option>
                                    @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id }}">{{ $grupo->nombre_grupo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">Agregar Alumno</button>
                                <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                        
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    


<script>
    document.getElementById('search-student-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                // Validaciones en el servidor
                return response.json().then(errors => {
                    if (errors.errors) {
                        // Mostrar errores específicos de validación
                        if (errors.errors.correo_institucional) {
                            alert(errors.errors.correo_institucional.join('\n'));
                        }
                        if (errors.errors.numero_cuenta) {
                            alert(errors.errors.numero_cuenta.join('\n'));
                        }
                    } else if (errors.error) {
                        // Error genérico (ej. alumno no encontrado)
                        alert(errors.error);
                    }
                });
            }
            return response.json();
        })
        .then(data => {
            if (data && !data.error) {
                // Mostrar datos del alumno en la tabla
                document.getElementById('alumno_id').value = data.id;
                document.getElementById('nombre').textContent = data.nombre;
                document.getElementById('apellidos').textContent = data.apellidos;
                document.getElementById('semestre').textContent = data.semestre;

                document.getElementById('student-info').classList.remove('d-none');
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection
