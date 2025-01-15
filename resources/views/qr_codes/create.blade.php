@extends('layouts.app')

@section('title', 'Generar Código QR')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Generar Nuevo Código QR</h1>
        <a href="{{ route('qr_codes.index') }}" class="btn btn-secondary">Volver a la lista</a>
    </div>
    <form action="{{ route('qr_codes.store') }}" method="POST" class="card p-4 shadow">
        @csrf
        <div class="mb-3">
            <label for="grupo_id" class="form-label">
                Grupo: <small>(Selecciona el grupo para el que se generará el QR.)</small>
            </label>
            <select name="grupo_id" id="grupo_id" class="form-select" required>
                <option value="">Selecciona un grupo</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->nombre_grupo }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="materia_nombre" class="form-label">
                Materia: <small>(Se llenará automáticamente según el grupo seleccionado.)</small>
            </label>
            <input 
                type="text" 
                id="materia_nombre" 
                class="form-control" 
                readonly 
                placeholder="La materia se llenará automáticamente"
            >
        </div>
        <input type="hidden" name="materia_id" id="materia_id">

        <div class="mb-3">
            <label for="tipo" class="form-label">
                Tipo de Código: <small>(Selecciona el tipo de código, solo se permite "Asistencia".)</small>
            </label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="asistencia">Asistencia</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha_clase" class="form-label">
                Fecha de Clase: <small>(Debe ser una fecha futura y no mayor a 6 meses desde hoy.)</small>
            </label>
            <input 
                type="date" 
                name="fecha_clase" 
                id="fecha_clase" 
                class="form-control" 
                required 
                min="{{ date('Y-m-d') }}"
            >
        </div>
        <div class="mb-3">
            <label for="hora_clase" class="form-label">
                Hora de Clase: <small>(Debe estar entre las 07:00 y las 18:00.)</small>
            </label>
            <input 
                type="time" 
                name="hora_clase" 
                id="hora_clase" 
                class="form-control" 
                required 
                min="07:00" 
                max="18:00"
                title="La hora debe estar entre las 07:00 y las 18:00"
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
            >
        </div>
        <div class="mb-3">
            <label for="asistencia" class="form-label">
                Minutos para Asistencia: <small>(Tiempo en minutos para marcar asistencia, mínimo 5 minutos.)</small>
            </label>
            <input 
                type="number" 
                name="asistencia" 
                id="asistencia" 
                class="form-control" 
                required
                placeholder="Ejemplo: 5"
                min="5"
            >
        </div>
        <div class="mb-3">
            <label for="retardo" class="form-label">
                Minutos para Retardo: <small>(Tiempo en minutos para marcar retardo después de la asistencia.)</small>
            </label>
            <input 
                type="number" 
                name="retardo" 
                id="retardo" 
                class="form-control" 
                required
                placeholder="Ejemplo: 10"
                min="1"
            >
        </div>
        <div class="mb-3">
            <label for="inasistencia" class="form-label">
                Minutos para Inasistencia: <small>(Tiempo en minutos para marcar inasistencia después del retardo.)</small>
            </label>
            <input 
                type="number" 
                name="inasistencia" 
                id="inasistencia" 
                class="form-control" 
                required
                placeholder="Ejemplo: 20"
                min="1"
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
        const horaClase = document.getElementById('hora_clase');
        const finClase = document.getElementById('fin_clase');
        const form = document.querySelector('form');

        const currentDate = new Date();
        const currentHour = currentDate.getHours();

        let minDate = currentHour >= 18
            ? new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() + 1)
            : currentDate;

        let maxDate = new Date(minDate);
        maxDate.setMonth(maxDate.getMonth() + 6);

        fechaClase.setAttribute('min', minDate.toISOString().split('T')[0]);
        fechaClase.setAttribute('max', maxDate.toISOString().split('T')[0]);

        function validateTimes() {
            const horaInicio = horaClase.value;
            const horaFin = finClase.value;

            if (horaInicio && horaFin) {
                const [startHours, startMinutes] = horaInicio.split(':').map(Number);
                const [endHours, endMinutes] = horaFin.split(':').map(Number);

                const startInMinutes = startHours * 60 + startMinutes;
                const endInMinutes = endHours * 60 + endMinutes;

                const diffInMinutes = endInMinutes - startInMinutes;
                const limiteMaximo = 19 * 60; // 19:00 en minutos

                if (diffInMinutes < 60) {
                    alert("La hora de fin debe ser al menos 1 hora después de la hora de inicio.");
                    finClase.value = "";
                    return;
                }

                if (diffInMinutes > 240) {
                    alert("La hora de fin no puede ser más de 4 horas después de la hora de inicio.");
                    finClase.value = "";
                    return;
                }

                if (endInMinutes > limiteMaximo) {
                    alert("La hora de fin no puede exceder las 19:00.");
                    finClase.value = "";
                }
            }
        }

        horaClase.addEventListener('change', validateTimes);
        finClase.addEventListener('change', validateTimes);

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

        // Restablecer valores al cargar
        horaClase.value = '';
        finClase.value = '';

        // Forzar limpieza de caché al enviar
        form.addEventListener('submit', function () {
            horaClase.value = horaClase.value.trim();
            finClase.value = finClase.value.trim();
        });

        // Validar valores en tiempo real
        horaClase.addEventListener('input', function () {
            horaClase.value = horaClase.value.trim();
        });

        finClase.addEventListener('input', function () {
            finClase.value = finClase.value.trim();
        });

        // Deshabilitar caché del formulario
        form.setAttribute('autocomplete', 'off');
        horaClase.setAttribute('autocomplete', 'off');
        finClase.setAttribute('autocomplete', 'off');
    });
</script>

@endsection
