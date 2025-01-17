@extends('layouts.app')

@section('title', 'Generar Código QR')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Generar Nuevo Código QR</h1>
        <a href="{{ route('qr_codes.index') }}" class="btn btn-secondary">Volver a la lista</a>
    </div>
    <form action="{{ route('qr_codes.store') }}" method="POST" class="card p-4 shadow">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @csrf
        <div class="mb-3">
            <label for="grupo_id" class="form-label">Grupo:</label>
            <select name="grupo_id" id="grupo_id" class="form-select" required>
                <option value="">Selecciona un grupo</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                        {{ $grupo->nombre_grupo }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="materia_nombre" class="form-label">Materia:</label>
            <input 
                type="text" 
                id="materia_nombre" 
                class="form-control" 
                readonly 
                placeholder="La materia se llenará automáticamente"
                value="{{ old('materia_nombre') }}"
            >
        </div>
        <input type="hidden" name="materia_id" id="materia_id" value="{{ old('materia_id') }}">

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de Código:</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="asistencia" {{ old('tipo') == 'asistencia' ? 'selected' : '' }}>Asistencia</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha_clase" class="form-label">Fecha de Clase:</label>
            <input 
                type="date" 
                name="fecha_clase" 
                id="fecha_clase" 
                class="form-control" 
                required 
                min="{{ date('Y-m-d') }}"
                value="{{ old('fecha_clase') }}"
            >
        </div>
        <div class="mb-3">
            <label for="hora_clase" class="form-label">Hora de Clase:</label>
            <input 
                type="time" 
                name="hora_clase" 
                id="hora_clase" 
                class="form-control" 
                required 
                min="07:00" 
                max="18:00"
                title="La hora debe estar entre las 07:00 y las 18:00"
                value="{{ old('hora_clase') }}"
            >
        </div>
        <div class="mb-3">
            <label for="fin_clase" class="form-label">
                Hora de Fin de Clase: 
                <small>(Debe ser al menos 1 hora después de la hora de inicio y no más de 4 horas después, ni exceder las 19:00.)</small>
            </label>
            <input 
                type="time" 
                name="fin_clase" 
                id="fin_clase" 
                class="form-control" 
                required
                title="La hora de fin debe ser al menos 1 hora después y no más de 4 horas después de la hora de inicio, ni exceder las 19:00."
                value="{{ old('fin_clase') }}"
            >
        </div>
        <div class="mb-3">
            <label for="asistencia" class="form-label">
                Minutos para Asistencia: <small>(Mínimo 5 y máximo 10 minutos.)</small>
            </label>
            <input 
                type="number" 
                name="asistencia" 
                id="asistencia" 
                class="form-control" 
                required
                placeholder="Ejemplo: 5"
                min="5"
                max="10"
                value="{{ old('asistencia') }}"
            >
        </div>
        <div class="mb-3">
            <label for="retardo" class="form-label">
                Minutos para Retardo: <small>(Mínimo 1 y máximo 10 minutos.)</small>
            </label>
            <input 
                type="number" 
                name="retardo" 
                id="retardo" 
                class="form-control" 
                required
                placeholder="Ejemplo: 10"
                min="1"
                max="10"
                value="{{ old('retardo') }}"
            >
        </div>
        <div class="mb-3">
            <label for="inasistencia" class="form-label">
                Minutos para Inasistencia: <small>(Mínimo 1 y máximo 10 minutos.)</small>
            </label>
            <input 
                type="number" 
                name="inasistencia" 
                id="inasistencia" 
                class="form-control" 
                required
                placeholder="Ejemplo: 20"
                min="1"
                max="10"
                value="{{ old('inasistencia') }}"
            >
        </div>        
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success-custom">Generar Código QR</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fechaClase = document.getElementById('fecha_clase');
        const currentDate = new Date();

        // Obtener la hora local y verificar si son las 18:00 o más
        const currentHour = currentDate.getHours();

        // Crear una nueva fecha mínima
        let minDate;

        if (currentHour >= 18) {
            // Si son las 18:00 o más, incrementar la fecha al día siguiente
            minDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() + 1);
        } else {
            // Si es antes de las 18:00, usar la fecha actual
            minDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate());
        }

        // Formatear la fecha mínima a 'YYYY-MM-DD'
        const formattedMinDate = minDate.toISOString().split('T')[0];
        fechaClase.setAttribute('min', formattedMinDate);

        // Manejar el cambio en el campo "grupo_id"
        document.getElementById('grupo_id').addEventListener('change', function () {
            const grupoId = this.value;

            if (!grupoId) {
                document.getElementById('materia_nombre').value = '';
                document.getElementById('materia_id').value = '';
                return;
            }

            fetch('{{ route("materia.by-grupo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ grupo_id: grupoId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    document.getElementById('materia_nombre').value = '';
                    document.getElementById('materia_id').value = '';
                } else {
                    document.getElementById('materia_nombre').value = data.materia_nombre;
                    document.getElementById('materia_id').value = data.materia_id;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>



@endsection
